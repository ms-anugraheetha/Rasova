<?php

namespace App\Exceptions;

use Exception;

class OversellException extends Exception
{
    public function __construct(public readonly int $productVariantId, public readonly int $requested, public readonly int $available)
    {
        parent::__construct("Insufficient stock for variant {$productVariantId}: requested {$requested}, available {$available}");
    }
}