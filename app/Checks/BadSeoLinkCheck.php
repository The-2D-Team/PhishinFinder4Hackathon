<?php

namespace App\Checks;

use Illuminate\Support\Str;

class BadSeoLinkCheck extends AbstractCheck
{
    public function getScore(): int
    {
        return (Str::contains($this->check->task->url, ['.php', '.asp', '.aspx']) ? 1 : 0) + (Str::substrCount($this->check->task->url, '-') > 1 ? 1 : 0);
    }

    public function getMaxScore(): int
    {
        return 2;
    }

}
