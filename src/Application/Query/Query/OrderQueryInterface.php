<?php

declare(strict_types=1);

namespace App\Application\Query\Query;

use App\Application\Query\Query\View\Order;
use Symfony\Component\Uid\Ulid;

interface OrderQueryInterface
{
    public function findById(Ulid $id): ?Order;
}
