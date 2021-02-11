<?php

namespace App\Controller;

use App\Client\Model\AllowedActions;
use App\Client\Provider\ClientFromRequestProvider;
use App\ConfigFile\Assert\AssertEmailPattern;
use App\ConfigFile\Command\AddEmailToWhitelist;
use App\ConfigFile\FileManager\FileManagerInterface;
use App\ConfigFile\Parser\WhitelistParserInterface;
use Assert\AssertionFailedException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class WhitelistController extends AbstractController
{
    private SerializerInterface $serializer;
    private LoggerInterface $logger;
    private FileManagerInterface $fileManager;
    private WhitelistParserInterface $parser;
    private ClientFromRequestProvider $clientProvider;
    private MessageBusInterface $messageBus;

    public function __construct(
        SerializerInterface $serializer,
        LoggerInterface $logger,
        FileManagerInterface $fileManager,
        WhitelistParserInterface $parser,
        ClientFromRequestProvider $clientProvider,
        MessageBusInterface $messageBus
    ) {
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->fileManager = $fileManager;
        $this->parser = $parser;
        $this->clientProvider = $clientProvider;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/whitelist", methods={"GET"})
     */
    public function getList(Request $request): JsonResponse
    {
        $client = $this->clientProvider->getClient();

        if (null === $client || !$client->enabled()) {
            return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        if (!$client->allowedActions()->isAllowed(AllowedActions::ACTION_READ)) {
            return new JsonResponse('Not allowed to read', Response::HTTP_FORBIDDEN);
        }

        $this->logger->info(\sprintf('Client %s requested whitelist', $client->name()));

        $emails = $this->parser->getEmailsFromFileContent($this->fileManager->read());

        return new JsonResponse(
            $this->serializer->serialize($emails, 'json'), Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/whitelist", methods={"POST"})
     */
    public function addEmail(Request $request): JsonResponse
    {
        $client = $this->clientProvider->getClient();

        if (null === $client || !$client->enabled()) {
            return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        if (!$client->allowedActions()->isAllowed(AllowedActions::ACTION_WRITE)) {
            return new JsonResponse('Not allowed to write', Response::HTTP_FORBIDDEN);
        }

        $email = $request->getContent();

        try {
            AssertEmailPattern::email($email);
        } catch (AssertionFailedException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $this->messageBus->dispatch($command = new AddEmailToWhitelist($email));

        $this->logger->info(\sprintf('Client %s added %s to whitelist', $client->name(), $command->email()));

        $emails = $this->parser->getEmailsFromFileContent($this->fileManager->read());

        return new JsonResponse(
            $this->serializer->serialize($emails, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }
}
