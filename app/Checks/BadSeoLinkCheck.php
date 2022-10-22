<?php

namespace App\Checks;

use Illuminate\Support\Str;

class BadSeoLinkCheck extends AbstractCheck
{
    public function getScore(): int
    {
        return Str::contains($this->check->task->url, ['.php', '.asp', '.aspx']) ? 1 : 0;
    }

    public function getMaxScore(): int
    {
        return 1;
    }

}
