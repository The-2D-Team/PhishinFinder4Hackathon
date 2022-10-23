<?php

namespace App\Actions;

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
use App\Jobs\CreateTaskJob;
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

        $task->save();

        CreateTaskJob::dispatch($task);

        return $task;
    }
}
