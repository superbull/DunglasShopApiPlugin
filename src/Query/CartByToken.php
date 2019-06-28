<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Query;

class CartByToken implements QueryInterface
{
    protected $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function token(): string
    {
        return $this->token;
    }
}
