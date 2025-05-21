<div>
    <x-modal id="show-device-modal" title="Device Details" maxWidth="3xl" wire:model="showDeviceModal">
        @if ($device)
            <div class="sm:flex sm:items-center mb-8">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $device->name ?? 'Not specified' }}
                    </h1>
                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                        Device details and management options.
                    </p>
                </div>
                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex sm:space-x-3">
                    <button wire:click="$dispatch('edit-device', {id: '{{ $device?->id }}'})" type="button"
                        class="inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                            <path
                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                        </svg>
                        Edit
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
                <!-- Device Info -->
                <div class="px-4 sm:px-0">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">Device Information
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">
                        Basic details about this device.
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-700 sm:rounded-xl md:col-span-2">
                    <div class="px-4 py-6 sm:p-8">
                        <dl class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Device Name
                                </dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                                    {{ $device->name ?? 'Not specified' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Device Type
                                </dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                                    {{ $device->type->value ?? 'Not specified' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Hardware ID
                                </dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 font-mono">
                                    {{ $device->hardware_id ?? 'Not specified' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Status</dt>
                                <dd class="mt-1 text-sm leading-6">
                                    <span @class([
                                        'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset',
                                        'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20' =>
                                            $device?->status->value === 'online',
                                        'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20' =>
                                            $device?->status->value !== 'online',
                                    ])>
                                        {{ $device?->status->value === 'online' ? 'Online' : 'Offline' }}
                                    </span>
                                    <button wire:click="refreshDeviceStatus"
                                        class="ml-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">IP Address
                                </dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 font-mono">
                                    {{ $device->ip_address ?? 'Not specified' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Screen
                                    Resolution</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 font-mono">
                                    {{ $device->screen_resolution ?? 'Not specified' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Orientation
                                </dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                                    {{ $device->orientation->value ?? 'Not specified' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">OS Version
                                </dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                                    {{ $device->os_version ?? 'Not specified' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">App Version
                                </dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                                    {{ $device->app_version ?? 'Not specified' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Location</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                                    @if (is_array($device->location))
                                        {{ $device->location['address'] ?? 'Not specified' }}
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            @if (isset($device->location['lat']) && isset($device->location['lng']))
                                                ({{ $device->location['lat'] }}, {{ $device->location['lng'] }})
                                            @endif
                                        </div>
                                    @else
                                        Not specified
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Timezone</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                                    {{ $device->timezone ?? 'Not specified' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Last
                                    Connected</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                                    {{ $device?->last_ping_at?->diffForHumans() ?? 'Never' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Last Sync
                                </dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                                    {{ $device?->last_sync_at?->diffForHumans() ?? 'Never' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Settings</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                                    @if (is_array($device->settings))
                                        <ul class="list-disc pl-4">
                                            @foreach ($device->settings as $key => $value)
                                                <li>
                                                    <span class="font-mono">{{ $key }}</span>:
                                                    <span class="font-mono">
                                                        @if (is_bool($value))
                                                            {{ $value ? 'true' : 'false' }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        Not specified
                                    @endif
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Notes</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                                    {{ $device?->notes ?? 'No notes provided.' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Activation Info -->
                <div class="px-4 sm:px-0">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">Activation</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">
                        Device activation and security details.
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-700 sm:rounded-xl md:col-span-2">
                    <div class="px-4 py-6 sm:p-8">
                        <dl class="grid grid-cols-1 gap-x-6 gap-y-6">
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Activation
                                    Token
                                </dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 font-mono">
                                    {{ $device->activation_token ?? 'Not specified' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Activation
                                    URL
                                </dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 break-all font-mono">
                                    @if ($device && $device->activation_token)
                                        {{-- {{ route('api.devices.activate', ['token' => $device->activation_token]) }} --}}
                                    @else
                                        Not specified
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <button wire:click="confirmResetToken" type="button"
                                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Reset Activation Token
                                </button>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Warning: This will invalidate any existing activation for this device.
                                </p>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Reset Token Confirmation Modal -->
            @if ($showResetConfirmation)
                <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div
                        class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity">
                    </div>
                    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <div
                                class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100"
                                            id="modal-title">Reset Activation Token</h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Are you sure you want to reset this device's activation token? This
                                                action
                                                will
                                                revoke access for any currently activated device and cannot be undone.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                    <button wire:click="resetActivationToken" type="button"
                                        class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                        Reset Token
                                    </button>
                                    <button wire:click="cancelReset" type="button"
                                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center text-gray-500 dark:text-gray-400">
                No device found.
            </div>
        @endif
    </x-modal>
</div>
