<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Query\QueryInterface;

interface QueryRequestInterface
{
    public function getQuery(): QueryInterface;
}
