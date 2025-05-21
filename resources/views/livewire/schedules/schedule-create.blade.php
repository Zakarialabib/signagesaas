<div>
    <x-modal wire:model="showModal" title="Create Schedule">
        <form wire:submit="create" class="space-y-6">
            <div class="p-6">
                <!-- Name -->
                <div>
                    <x-label for="name" value="Name" />
                    <input wire:model="name" id="name" type="text" class="mt-1 block w-full" required />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <!-- Description -->
                <div class="mt-4">
                    <x-label for="description" value="Description" />
                    <textarea wire:model="description" id="description" class="mt-1 block w-full" rows="3"></textarea>
                    <x-input-error for="description" class="mt-2" />
                </div>

                <!-- Screen -->
                <div class="mt-4">
                    <x-label for="screen_id" value="Screen" />
                    <select wire:model.live="screen_id" id="screen_id" class="mt-1 block w-full" required>
                        <option value="">Select Screen</option>
                        @foreach ($this->screens as $screen)
                            <option value="{{ $screen->id }}">{{ $screen->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="screen_id" class="mt-2" />
                </div>

                <!-- Status -->
                <div class="mt-4">
                    <x-label for="status" value="Status" />
                    <select wire:model="status" id="status" class="mt-1 block w-full" required>
                        @foreach ($this->statuses as $status)
                            <option value="{{ $status['value'] }}">{{ $status['label'] }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="status" class="mt-2" />
                </div>

                <!-- Date Range -->
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-label for="start_date" value="Start Date" />
                        <input wire:model="start_date" id="start_date" type="date" class="mt-1 block w-full"
                            required />
                        <x-input-error for="start_date" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="end_date" value="End Date" />
                        <input wire:model="end_date" id="end_date" type="date" class="mt-1 block w-full" />
                        <x-input-error for="end_date" class="mt-2" />
                    </div>
                </div>

                <!-- Time Range -->
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-label for="start_time" value="Start Time" />
                        <input wire:model="start_time" id="start_time" type="time" class="mt-1 block w-full"
                            required />
                        <x-input-error for="start_time" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="end_time" value="End Time" />
                        <input wire:model="end_time" id="end_time" type="time" class="mt-1 block w-full" required />
                        <x-input-error for="end_time" class="mt-2" />
                    </div>
                </div>

                <!-- Days of Week -->
                <div class="mt-4">
                    <x-label value="Days of Week" />
                    <div class="mt-2 grid grid-cols-4 gap-4">
                        @php
                            $days = [
                                0 => 'Sunday',
                                1 => 'Monday',
                                2 => 'Tuesday',
                                3 => 'Wednesday',
                                4 => 'Thursday',
                                5 => 'Friday',
                                6 => 'Saturday',
                            ];
                        @endphp
                        @foreach ($days as $value => $label)
                            <label class="inline-flex items-center">
                                <input type="checkbox" wire:model="days_of_week" value="{{ $value }}"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error for="days_of_week" class="mt-2" />
                </div>

                <!-- Priority -->
                <div class="mt-4">
                    <x-label for="priority" value="Priority" />
                    <input wire:model="priority" id="priority" type="number" class="mt-1 block w-full" min="0"
                        required />
                    <x-input-error for="priority" class="mt-2" />
                </div>

                <!-- Content Selection -->
                @if ($screen_id)
                    <div class="mt-4">
                        <x-label value="Content" />
                        <div class="mt-2 border rounded-md divide-y">
                            @forelse($this->availableContents as $content)
                                <div class="p-4 flex items-center space-x-4">
                                    <input type="checkbox" wire:model="selected_contents" value="{{ $content->id }}"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $content->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $content->type }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-sm text-gray-500">
                                    No content available for this screen.
                                </div>
                            @endforelse
                        </div>
                        <x-input-error for="selected_contents" class="mt-2" />
                    </div>
                @endif
            </div>

            <div class="px-6 py-4 bg-gray-50 text-right">
                <x-button type="button" color="secondary" wire:click="close">
                    Cancel
                </x-button>
                <x-button type="submit" color="primary" class="ml-3">
                    Create Schedule
                </x-button>
            </div>
        </form>
    </x-modal>
</div>
