<?php

namespace App\Checks;

use Illuminate\Support\Str;

class DeadWebsiteCheck extends AbstractCheck
{
    use DownloadsUrlTrait;

    public function getScore(): int
    {
        ['headers' => $headers, 'status' => $status, 'body' => $body] = $this->download($this->check->task->url);

        if($status == null || $status >= 400) {
            return 10;
        }

        return 0;
    }

    public function getMaxScore(): int
    {
        return 0;
    }


}
