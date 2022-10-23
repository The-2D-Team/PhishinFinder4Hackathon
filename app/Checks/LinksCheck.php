<?php

namespace App\Checks;

use DOMDocument;
use Illuminate\Support\Str;

class LinksCheck extends AbstractCheck
{
    use DownloadsUrlTrait;

    public function getScore(): int
    {
        ['headers' => $headers, 'status' => $status, 'body' => $body] = $this->download($this->check->task->url);

        if($status == null) {
            return 1;
        }

        $dom = new DOMDocument;

        @$dom->loadHTML($body);

        $links = $dom->getElementsByTagName('a');

        $l = [];
        foreach ($links as $link){
            $l[] = $link->getAttribute('href');
        }

        $x = collect($l)->partition(function($item) {
            return Str::contains($item, '://');
        })->map->count();

        return $x[1] == 0 || $x[0] / $x[1] > 0.2 ? 1 : 0;
    }

    public function getMaxScore(): int
    {
        return 1;
    }


}
