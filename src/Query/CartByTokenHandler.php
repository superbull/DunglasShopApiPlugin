<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Query;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;

final class CartByTokenHandler
{
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(CartByToken $cartByToken): ?OrderInterface
    {
        return $this->orderRepository->findOneBy([
            'tokenValue' => $cartByToken->token(),
            'state' => OrderInterface::STATE_CART,
        ]);
    }
}
