<?php

namespace App\Checks;

class IdnDomainCheck extends AbstractCheck
{
    public function getScore(): int
    {
        $url = $this->check->task->url;

        return idn_to_utf8($url) != $url || idn_to_ascii($url) != $url ? 2 : 0;

    }

    public function getMaxScore(): int
    {
        return 2;
    }

}
