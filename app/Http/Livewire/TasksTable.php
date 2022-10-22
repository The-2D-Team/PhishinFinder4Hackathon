<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class TasksTable extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.tasks-table', [
            'tasks' => auth()->user()->tasks()->latest()->paginate(30),
        ]);
    }
}
