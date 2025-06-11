<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Tenant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form wire:submit.prevent="saveTenant">
                    <div class="mb-4">
                        <x-label for="name" value="{{ __('Tenant Name') }}" />
                        <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" required autofocus />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-label for="email" value="{{ __('Admin Email') }}" />
                        <x-input id="email" type="email" class="mt-1 block w-full" wire:model="email" required />
                        <x-input-error for="email" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-label for="domain" value="{{ __('Domain (e.g., yourcompany.signagesaas.com)') }}" />
                        <x-input id="domain" type="text" class="mt-1 block w-full" wire:model="domain" required />
                        <x-input-error for="domain" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-label for="plan_id" value="{{ __('Subscription Plan') }}" />
                        <select id="plan_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="plan_id" required>
                            <option value="">{{ __('Select a plan') }}</option>
                            @foreach($plans as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="plan_id" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button type="submit">
                            {{ __('Create Tenant') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>