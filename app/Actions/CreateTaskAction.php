<?php

namespace App\Actions;

use App\Checks\BadSeoLinkCheck;
use App\Checks\FakeDomainCheck;
use App\Checks\IdnDomainCheck;
use App\Checks\KeywordDomainCheck;
use App\Checks\LoginFormCheck;
use App\Checks\LongDomainCheck;
use App\Checks\NestedSubdomainCheck;
use App\Checks\TropicalDomainCheck;
use App\Jobs\FinishTaskJob;
use App\Jobs\RunCheckJob;
use App\Models\Check;
use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class CreateTaskAction
{
    public function execute(User $user, string $url): Task
    {
        $task = new Task();
        $task->url = $url;
        $task->status = 'pending';
        $user->tasks()->save($task);

        $jobs = collect([
            IdnDomainCheck::class,
            LoginFormCheck::class,
            BadSeoLinkCheck::class,
            LongDomainCheck::class,
            NestedSubdomainCheck::class,
            FakeDomainCheck::class,
            TropicalDomainCheck::class,
            KeywordDomainCheck::class,
        ])->map(function (string $checkClass) use ($task) {
            $check = new Check;
            $check->type = $checkClass;
            $task->checks()->save($check);

            return $check;
        })->map(function (Check $check) {
            return new RunCheckJob($check);
        });

        $batch = Bus::batch($jobs->toArray())->then(function (Batch $batch) {
            // All jobs completed successfully...
        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
        })->finally(function (Batch $batch) use ($task) {
            FinishTaskJob::dispatch($task);
        })->name('Task '.$task->id)->dispatch();

        $task->batch_id = $batch->id;
        $task->save();

        return $task;
    }
}
