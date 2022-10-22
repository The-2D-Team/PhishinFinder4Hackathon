<?php

namespace App\Actions;

use App\Models\Task;
use App\Models\User;

class CreateOrRetrieveExistingTaskAction extends CreateTaskAction
{
    public function execute(User $user, string $url): Task
    {
        /** @var Task|null $task */
        $task = $user->tasks()
            ->where('url', $url)
            ->first();

        return $task ?? parent::execute($user, $url);
    }

}
