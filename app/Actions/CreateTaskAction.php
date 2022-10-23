<?php

namespace App\Actions;

use App\Jobs\CreateTaskJob;
use App\Models\Task;
use App\Models\User;

class CreateTaskAction
{
    public function execute(User $user, string $url): Task
    {
        $task = new Task();
        $task->url = $url;
        $task->status = 'pending';
        $user->tasks()->save($task);

        CreateTaskJob::dispatch($task);

        return $task;
    }
}
