<?php

namespace App\Actions;

use App\Models\Check;
use App\Models\User;
use Illuminate\Support\Str;

class GetTextExportAction
{
    public function execute(User $user): string
    {
        $tasks = $user->tasks()->with('checks')->get();

        $text = '';
        $text_short = '';

        $length = $tasks->pluck('url')->map(fn($url) => strlen($url))->max();

        foreach($tasks as $task) {
            $text_short .= str_pad($task->url, $length) . ' - ' .str_pad($task->score, 3, pad_type: STR_PAD_LEFT) . '%' . PHP_EOL;

            $text .= str_pad('URL:', 11). $task->url . PHP_EOL;
            $text .= str_pad('Score:', 11) . $task->score . '%' . PHP_EOL;
            $text .= str_pad('Status:', 11) . $task->status . PHP_EOL;
            $text .= str_pad('Checks:', 11);

            $first = true;
            $checks = $task->checks->each(function (Check $check) {
                $check->type = Str::afterLast($check->type, '\\');
            });
            $length_chk = $checks->pluck('type')->map(fn($type) => strlen($type))->max();
            foreach($checks as $check) {
                $text .= str_pad('- ', $first ? 1 : 11+2, pad_type: STR_PAD_LEFT) . str_pad($check->type, $length_chk) . ' - ' . str_pad($check->score, 2, pad_type: STR_PAD_LEFT) . '/' . str_pad($check->max_score ?: '~', 2) . PHP_EOL;
                $first = false;
            }

            $text .= PHP_EOL;
            $text .= PHP_EOL;
        }

        return $text_short.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.$text;
    }
}
