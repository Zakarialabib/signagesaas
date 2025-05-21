<div>
    <x-modal wire:model="showModal" title="Schedule Details">
        @if($schedule)
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $schedule->name }}</dd>
                    </div>

                    @if($schedule->description)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $schedule->description }}</dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Screen</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $schedule->screen->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $schedule->status->getColor() }}-100 text-{{ $schedule->status->getColor() }}-800">
                                {{ $schedule->status->label() }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $schedule->start_date->format('M d, Y') }}</dd>
                    </div>

                    @if($schedule->end_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">End Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $schedule->end_date->format('M d, Y') }}</dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Time</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Priority</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $schedule->priority }}</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Days of Week</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $days = [
                                        0 => 'Sunday',
                                        1 => 'Monday',
                                        2 => 'Tuesday',
                                        3 => 'Wednesday',
                                        4 => 'Thursday',
                                        5 => 'Friday',
                                        6 => 'Saturday'
                                    ];
                                @endphp
                                @foreach($schedule->days_of_week as $day)
                                    <span
                                        class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                        {{ $days[$day] }}
                                    </span>
                                @endforeach
                            </div>
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Content</dt>
                        <dd class="mt-1">
                            @if($schedule->contents->isNotEmpty())
                                <ul role="list" class="border rounded-md divide-y">
                                    @foreach($schedule->contents as $content)
                                        <li class="p-4 flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $content->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $content->type }}</p>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Order: {{ $content->pivot->order }}
                                                <br>
                                                Duration: {{ $content->pivot->duration }}s
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500">No content assigned to this schedule.</p>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="px-6 py-4 bg-gray-50 text-right">
                <x-button type="button" color="secondary" wire:click="close">
                    Close
                </x-button>
                <x-button type="button" color="primary" class="ml-3"
                    wire:click="$dispatch('editSchedule', { id: '{{ $schedule->id }}' })">
                    Edit Schedule
                </x-button>
            </div>
        @endif
    </x-modal>
</div> 