<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinishTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Task $task)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checks = $this->task->checks;

        $successful = $checks
            ->filter(fn($check) => $check->status == 'success');

        $has_failed = !! $checks
            ->filter(fn($check) => $check->status != 'success')
            ->count();

        if($successful->count() == 0)
        {
            $this->task->status = 'failed';
            $this->task->save();

            return;
        }

        $this->task->status = $has_failed ? 'partial' : 'success';

        $total = $successful->sum('score');
        $of = $successful->sum('max_score');

        $this->task->score = $total / $of * 100;

        $this->task->save();
    }
}
