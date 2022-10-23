<?php

namespace App\Jobs;

use App\Checks\BadSeoLinkCheck;
use App\Checks\DeadWebsiteCheck;
use App\Checks\FakeDomainCheck;
use App\Checks\IdnDomainCheck;
use App\Checks\KeywordDomainCheck;
use App\Checks\LinksCheck;
use App\Checks\LoginFormCheck;
use App\Checks\LongDomainCheck;
use App\Checks\NestedSubdomainCheck;
use App\Checks\TropicalDomainCheck;
use App\Models\Check;
use App\Models\Task;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

class CreateTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected Task $task)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $jobs = collect([
            IdnDomainCheck::class,
            LoginFormCheck::class,
            BadSeoLinkCheck::class,
            LongDomainCheck::class,
            NestedSubdomainCheck::class,
            FakeDomainCheck::class,
            TropicalDomainCheck::class,
            KeywordDomainCheck::class,
            LinksCheck::class,
            DeadWebsiteCheck::class,
        ])->map(function (string $checkClass) {
            $check = new Check;
            $check->type = $checkClass;
            $this->task->checks()->save($check);

            return $check;
        })->map(function (Check $check) {
            return new RunCheckJob($check);
        });

        $batch = Bus::batch($jobs->toArray())->then(function (Batch $batch) {
            // All jobs completed successfully...
        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
        })->finally(function (Batch $batch) {
            FinishTaskJob::dispatch($this->task);
        })->name('Task '.$this->task->id)->dispatch();

        $this->task->batch_id = $batch->id;
        $this->task->save();
    }
}
