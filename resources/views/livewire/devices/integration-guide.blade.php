<div>
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Device Integration Guide</h1>
        <div class="mb-6 flex items-center space-x-4">
            <div>
                <label for="device-select" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">Select Device</label>
                <select id="device-select" wire:change="selectDevice($event.target.value)" class="rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                    <option value="">-- Choose a device --</option>
                    @foreach ($devices as $device)
                        <option value="{{ $device['id'] }}" @if($selectedDevice && $selectedDevice->id === $device['id']) selected @endif>
                            {{ $device['name'] }} ({{ $device['type'] }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button wire:click="$set('showRegistrationModal', true)" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Register New Device</button>
        </div>

        @if($selectedDevice)
            <div class="mb-6 bg-gray-50 dark:bg-gray-900 p-4 rounded shadow flex flex-col md:flex-row md:items-center md:space-x-8">
                <div class="flex-1">
                    <div class="mb-2 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $selectedDevice->name }} ({{ $selectedDevice->type }})</div>
                    <div class="mb-2 text-xs text-gray-500 dark:text-gray-400">Hardware ID: {{ $selectedDevice->hardware_id }}</div>
                    <div class="mb-2 flex items-center space-x-2">
                        <span class="text-xs font-medium">Status:</span>
                        @if($selectedDevice->isOnline())
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">Online</span>
                        @else
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">Offline</span>
                        @endif
                    </div>
                    <div class="mb-2 text-xs text-gray-500 dark:text-gray-400">Last Seen: {{ $selectedDevice->last_ping_at ? $selectedDevice->last_ping_at->diffForHumans() : 'Never' }}</div>
                    <div class="mb-2 text-xs text-gray-500 dark:text-gray-400">Last Sync: {{ $selectedDevice->last_sync_at ? $selectedDevice->last_sync_at->diffForHumans() : 'Never' }}</div>
                </div>
                <div class="flex flex-col items-center">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-200 mb-1">Device Token</label>
                    <input type="text" readonly value="{{ $selectedDevice->token }}" class="w-64 px-2 py-1 rounded bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-xs mb-2">
                    <button x-data="{}" @click="$clipboard('{{ $selectedDevice->token }}')" class="text-xs px-2 py-1 bg-indigo-600 text-white rounded mb-2">Copy</button>
                    {!! QrCode::size(96)->backgroundColor(31,41,55)->color(255,255,255)->generate($selectedDevice->token) !!}
                </div>
                <div class="flex-1 mt-4 md:mt-0">
                    <div class="font-semibold mb-2 text-sm text-gray-900 dark:text-gray-100">Onboarding Checklist</div>
                    <ul class="space-y-1 text-xs">
                        <li class="flex items-center space-x-2">
                            <span class="inline-block w-3 h-3 rounded-full {{ $selectedDevice->created_at ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                            <span>Registered</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <span class="inline-block w-3 h-3 rounded-full {{ $selectedDevice->last_ping_at ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                            <span>First Heartbeat</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <span class="inline-block w-3 h-3 rounded-full {{ $selectedDevice->last_sync_at ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                            <span>Content Synced</span>
                        </li>
                    </ul>
                </div>
            </div>
        @endif

        @if($showRegistrationModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-xl w-full max-w-md">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Register New Device</h2>
                    <form wire:submit="registerDevice">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Device Name</label>
                            <input type="text" wire:model="newDeviceName" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Device Type</label>
                            <select wire:model="newDeviceType" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                                @foreach ($platforms as $platform)
                                    <option value="{{ $platform['key'] }}">{{ $platform['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center justify-end gap-x-4">
                            <button type="button" wire:click="$set('showRegistrationModal', false)" class="inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">Cancel</button>
                            <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="mb-6 flex space-x-2">
            @foreach ($platforms as $platform)
                <button wire:click="selectPlatform('{{ $platform['key'] }}')" @class([
                    'px-4 py-2 rounded-t-md font-semibold focus:outline-none',
                    'bg-indigo-600 text-white' => $selectedPlatform === $platform['key'],
                    'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100' =>
                        $selectedPlatform !== $platform['key'],
                ])
                    aria-selected="{{ $selectedPlatform === $platform['key'] ? 'true' : 'false' }}" role="tab">
                    {{ $platform['name'] }}
                </button>
            @endforeach
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-b-md p-6" role="tabpanel">
            @if ($selectedPlatform === 'android')
                @include('livewire.devices.integration.android')
            @elseif ($selectedPlatform === 'raspberry-pi')
                @include('livewire.devices.integration.raspberry-pi')
            @elseif ($selectedPlatform === 'windows')
                @include('livewire.devices.integration.windows')
            @endif
        </div>
    </div>
</div>
