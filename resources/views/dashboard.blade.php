<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl">
            {{ __('Dashboard') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">
                <x-jet-welcome />
            </div>
        </div>
    </div>
</x-app-layout>
