<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Sylius\ShopApiPlugin\Request\QueryRequestInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class CommandRequestDataPersister implements DataPersisterInterface
{
    use HandleTrait;

    private $commandBus;
    private $queryBus;

    public function __construct(MessageBusInterface $commandBus, MessageBusInterface $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->messageBus = $queryBus; // required by HandleTrait
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data): bool
    {
        return $data instanceof CommandRequestInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function persist($data): object
    {
        if (!$data instanceof CommandRequestInterface) {
            throw new \LogicException(sprintf('Expected data to be "%s".', CommandRequestInterface::class));
        }

        $this->commandBus->dispatch($data->getCommand());

        if ($data instanceof QueryRequestInterface) {
            $data = $this->handle($data->getQuery());

            if (null === $data) {
                throw new \UnexpectedValueException('Query result must not be null.');
            }
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data): void
    {
        if (!$data instanceof CommandRequestInterface) {
            throw new \LogicException(sprintf('Expected data to be "%s".', CommandRequestInterface::class));
        }

        $this->commandBus->dispatch($data->getCommand());
    }
}
