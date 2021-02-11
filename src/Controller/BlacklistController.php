<?php

namespace App\Controller;

use App\Client\Model\AllowedActions;
use App\Client\Provider\ClientFromRequestProvider;
use App\ConfigFile\Assert\AssertEmailPattern;
use App\ConfigFile\Command\AddEmailToBlacklist;
use App\ConfigFile\FileManager\FileManagerInterface;
use App\ConfigFile\Parser\BlacklistParserInterface;
use Assert\AssertionFailedException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BlacklistController extends AbstractController
{
    private SerializerInterface $serializer;
    private LoggerInterface $logger;
    private FileManagerInterface $fileManager;
    private BlacklistParserInterface $parser;
    private ClientFromRequestProvider $clientProvider;
    private MessageBusInterface $messageBus;

    public function __construct(
        SerializerInterface $serializer,
        LoggerInterface $logger,
        FileManagerInterface $fileManager,
        BlacklistParserInterface $parser,
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
     * @Route("/blacklist", methods={"GET"})
     */
    public function getList(): JsonResponse
    {
        $client = $this->clientProvider->getClient();

        if (null === $client || !$client->enabled()) {
            return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        if (!$client->allowedActions()->isAllowed(AllowedActions::ACTION_READ)) {
            return new JsonResponse('Not allowed to read', Response::HTTP_FORBIDDEN);
        }

        $this->logger->info(\sprintf('Client %s requested blacklist', $client->name()));

        $emails = $this->parser->getEmailsFromFileContent($this->fileManager->read());

        return new JsonResponse(
            $this->serializer->serialize($emails, 'json'), Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/blacklist", methods={"POST"})
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

        $this->messageBus->dispatch($command = new AddEmailToBlacklist($email));

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
