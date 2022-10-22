<?php

namespace App\Checks;

use App\Models\Check;

class AbstractCheck implements CheckInterface
{
    public function __construct(protected Check $check)
    {}

    /**
     * @throws \Exception
     */
    public function getScore(): int
    {
        throw new \Exception('Not implemented');
    }

    public function getMaxScore(): int
    {
        return 100;
    }
}
