<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Cart;

use Ramsey\Uuid\Uuid;
use Sylius\ShopApiPlugin\Command\Cart\PickupCart;
use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Query\CartByToken;
use Sylius\ShopApiPlugin\Query\QueryInterface;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Sylius\ShopApiPlugin\Request\QueryRequestInterface;

class PickupCartRequest implements CommandRequestInterface, QueryRequestInterface
{
    protected $token;
    protected $channelCode;

    public function __construct(string $channelCode = '')
    {
        $this->token = Uuid::uuid4()->toString();
        $this->channelCode = $channelCode;
    }

    /**
     * {@inheritdoc}
     *
     * @return PickupCart
     */
    public function getCommand(): CommandInterface
    {
        return new PickupCart($this->token, $this->channelCode);
    }

    /**
     * {@inheritdoc}
     *
     * @return CartByToken
     */
    public function getQuery(): QueryInterface
    {
        return new CartByToken($this->token);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getChannelCode(): string
    {
        return $this->channelCode;
    }
}
