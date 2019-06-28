<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Command\Cart;

use Sylius\ShopApiPlugin\Command\CommandInterface;

class PickupCart implements CommandInterface
{
    protected $orderToken;
    protected $channelCode;

    public function __construct(string $orderToken, string $channelCode)
    {
        $this->orderToken = $orderToken;
        $this->channelCode = $channelCode;
    }

    public function orderToken(): string
    {
        return $this->orderToken;
    }

    public function channelCode(): string
    {
        return $this->channelCode;
    }
}
