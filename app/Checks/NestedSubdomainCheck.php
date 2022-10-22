<?php

namespace App\Checks;

use Illuminate\Support\Str;

class NestedSubdomainCheck extends AbstractCheck
{
    public function getScore(): int
    {
        $domain = parse_url($this->check->task->url, PHP_URL_HOST);
        $dots = Str::substrCount($domain, '.');

        if(Str::startsWith($domain, 'www.')) {
            $dots--;
        }

        return $dots >= 3 ? 1 : 0;
    }

    public function getMaxScore(): int
    {
        return 1;
    }
}
