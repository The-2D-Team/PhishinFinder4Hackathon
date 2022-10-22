<?php

namespace App\Checks;

use App\Models\Check;

interface CheckInterface
{
    public function __construct(Check $check);

    public function getScore(): int;
    public function getMaxScore(): int;
}


