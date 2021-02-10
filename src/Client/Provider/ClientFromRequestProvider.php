<?php

namespace App\Client\Provider;

use App\Client\Model\ClientInterface;
use App\Client\Repository\ClientRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class ClientFromRequestProvider
{
    const KEY = 'token';

    private RequestStack $requestStack;
    private ClientRepositoryInterface $repository;

    public function __construct(RequestStack $requestStack, ClientRepositoryInterface $repository)
    {
        $this->requestStack = $requestStack;
        $this->repository = $repository;
    }

    public function getClient(): ?ClientInterface
    {
        if (null === $request = $this->requestStack->getMasterRequest()) {
            return null;
        }

        if (!$token = $request->query->get(self::KEY)) {
            return null;
        }

        return $this->repository->findByToken($token);
    }
}
