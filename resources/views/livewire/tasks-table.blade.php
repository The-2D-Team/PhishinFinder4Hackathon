<div wire:poll.visible.15s>
    <div class="overflow-hidden bg-white shadow sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            @foreach($tasks as $task)
                <li>
                    <a href="#" class="block hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <p class="truncate text-sm font-medium text-indigo-600">{{ $task->url }}</p>
                                <div class="ml-2 flex flex-shrink-0">
                                    <p class="inline-flex rounded-full {{ $task->status == 'success' ? 'bg-green-100' : '' }} {{ $task->status == 'failed' ? 'bg-red-100' : '' }}  {{ $task->status == 'partial' ? 'bg-blue-100' : '' }}   {{ $task->status == 'pending' ? 'bg-blue-100' : '' }} px-2 text-xs font-semibold leading-5 text-green-800">{{ $task->status }}</p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <!-- Heroicon name: mini/calendar -->
                                    <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
                                    </svg>
                                    <p>
                                        Checked
                                        <time datetime="{{ $task->updated_at->format('Y-m-d H:i:s') }}">{{ $task->updated_at->diffForHumans() }}</time>
                                    </p>
                                </div>
                                <div class="sm:flex">
                                    @if($task->score !== null)
                                        <p class="flex items-center text-sm text-gray-500 sm:ml-6">
                                            <!-- Heroicon name: mini/users -->
                                            <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM1.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 017 18a9.953 9.953 0 01-5.385-1.572zM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 00-1.588-3.755 4.502 4.502 0 015.874 2.636.818.818 0 01-.36.98A7.465 7.465 0 0114.5 16z" />
                                            </svg>
                                            Score: <strong class="font-bold {{ $task->score == 0 ? 'text-green-900' : '' }} {{ $task->score >= 50 && $task->score < 75 ? 'text-orange-900' : '' }} {{ $task->score >= 75 ? 'text-red-900' : '' }}">{{ $task->score }}%</strong>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <p class="mt-2 flex items-center text-sm text-gray-500">
                                    @foreach($task->checks as $check)
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-gray-800 {{ $check->status == 'success' ? 'bg-green-100' : '' }} {{ $check->status == 'failed' ? 'bg-red-100' : '' }}  {{ $check->status == 'partial' ? 'bg-blue-100' : '' }}   {{ $check->status == 'pending' ? 'bg-blue-100' : '' }}">
                                            {{ \Illuminate\Support\Str::afterLast($check->type, '\\') }}
                                            @if($check->score !== null)
                                                {{$check->score}}/{{$check->max_score}}
                                            @endif
                                        </span>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="py-5">
        {{ $tasks->links() }}
    </div>

</div>
