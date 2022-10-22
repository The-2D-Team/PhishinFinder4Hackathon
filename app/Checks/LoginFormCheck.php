<?php

namespace App\Checks;

use Illuminate\Support\Str;

class LoginFormCheck extends AbstractCheck
{
    use DownloadsUrlTrait;

    public function getScore(): int
    {
        ['headers' => $headers, 'status' => $status, 'body' => $body] = $this->download($this->check->task->url);

        if($status == null) {
            return 1;
        }

        return Str::contains($body, '<form') ? 1 : 0;
    }

    public function getMaxScore(): int
    {
        return 1;
    }


}
