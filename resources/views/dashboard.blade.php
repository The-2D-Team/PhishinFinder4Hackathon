<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div>
                <div class="mt-5">
                    <form method="post">
                        @csrf
                        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                            <div class="grid grid-cols-6 gap-6">

                                <!-- Url -->
                                <div class="col-span-12">
                                    <x-jet-label for="urls" value="{{ __('Urls') }}" />
                                    <textarea id="urls" name="urls" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">@include('test_urls')</textarea>
                                    <x-jet-input-error for="urls" class="mt-2" />
                                </div>

                            </div>
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                            <x-jet-button>
                                {{ __('Check') }}
                            </x-jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:tasks-table />
        </div>
    </div>
</x-app-layout>
