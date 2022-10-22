<?php

namespace App\Checks;

use Illuminate\Support\Str;

class KeywordDomainCheck extends AbstractCheck
{
    public function getScore(): int
    {
        $domain = parse_url($this->check->task->url, PHP_URL_HOST);
        return Str::contains($domain, ['bank', 'loteria', 'lotto']) ? 2 : 0;
    }

    public function getMaxScore(): int
    {
        return 1;
    }

}

