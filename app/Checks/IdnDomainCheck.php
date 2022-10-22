<?php

namespace App\Checks;

class IdnDomainCheck extends AbstractCheck
{
    public function getScore(): int
    {
        $url = parse_url($this->check->task->url, PHP_URL_HOST);

        return idn_to_utf8($url) != $url || idn_to_ascii($url) != $url ? 2 : 0;
    }

    public function getMaxScore(): int
    {
        return 2;
    }

}
