<?php

namespace App\Checks;

class LongDomainCheck extends AbstractCheck
{
    public function getScore(): int
    {
        $domain = parse_url($this->check->task->url, PHP_URL_HOST);
        $length = strlen($domain);
        return $length > 20 ? 2 : ($length > 12 ? 1 : 0);
    }

    public function getMaxScore(): int
    {
        return 2;
    }

}
