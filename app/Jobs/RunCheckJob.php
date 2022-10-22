<?php

namespace App\Jobs;

use App\Checks\CheckInterface;
use App\Checks\TooManyAttemptsException;
use App\Models\Check;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunCheckJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Check $check)
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
        $class_name = $this->check->type;

        /** @var CheckInterface $instance */
        $instance = new $class_name($this->check);

        try {
            $this->check->max_score = $instance->getMaxScore();
            $this->check->score = $instance->getScore();
            $this->check->status = 'success';
        } catch (LockTimeoutException $e) {
            $this->release(15);
        } catch (TooManyAttemptsException $e) {
            $this->release(30);
        } catch (\Exception $e) {
            $this->check->status = 'failed';
        }

        $this->check->save();
    }
}
