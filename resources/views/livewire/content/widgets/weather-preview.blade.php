<div class="p-4 h-full">
    <div class="bg-gradient-to-br from-blue-400 to-blue-600 dark:from-blue-600 dark:to-blue-800 rounded-lg text-white h-full">
        @if(!empty($data['current']))
            <div class="p-6 h-full flex flex-col">
                <!-- Current Weather -->
                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold mb-2">{{ $data['settings']['location'] ?? 'Unknown Location' }}</h3>
                    
                    <div class="flex items-center justify-center space-x-4">
                        <div class="text-center">
                            @if(!empty($data['current']['icon']))
                                <div class="text-4xl mb-2">{{ $data['current']['icon'] }}</div>
                            @endif
                            <div class="text-3xl font-bold">
                                {{ $data['current']['temperature'] ?? '--' }}°{{ $data['settings']['units'] === 'imperial' ? 'F' : 'C' }}
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-lg capitalize mt-2">{{ $data['current']['description'] ?? 'No description' }}</p>
                    
                    <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                        <div class="text-center">
                            <div class="flex items-center justify-center mb-1">
                                <x-heroicon-o-eye class="w-4 h-4 mr-1" />
                                Feels like
                            </div>
                            <div class="font-semibold">{{ $data['current']['feels_like'] ?? '--' }}°</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="flex items-center justify-center mb-1">
                                <x-heroicon-o-beaker class="w-4 h-4 mr-1" />
                                Humidity
                            </div>
                            <div class="font-semibold">{{ $data['current']['humidity'] ?? '--' }}%</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="flex items-center justify-center mb-1">
                                <x-heroicon-o-arrow-right class="w-4 h-4 mr-1" />
                                Wind
                            </div>
                            <div class="font-semibold">{{ $data['current']['wind_speed'] ?? '--' }} {{ $data['settings']['units'] === 'imperial' ? 'mph' : 'km/h' }}</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="flex items-center justify-center mb-1">
                                <x-heroicon-o-eye class="w-4 h-4 mr-1" />
                                Visibility
                            </div>
                            <div class="font-semibold">{{ $data['current']['visibility'] ?? '--' }} {{ $data['settings']['units'] === 'imperial' ? 'mi' : 'km' }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Forecast -->
                @if(($data['settings']['show_forecast'] ?? true) && !empty($data['forecast']))
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold mb-3 text-center">5-Day Forecast</h4>
                        
                        <div class="grid grid-cols-5 gap-2 text-xs">
                            @foreach(array_slice($data['forecast'], 0, 5) as $day)
                                <div class="text-center bg-white/10 rounded-lg p-2">
                                    <div class="font-medium mb-1">{{ $day['day'] ?? 'Day' }}</div>
                                    @if(!empty($day['icon']))
                                        <div class="text-lg mb-1">{{ $day['icon'] }}</div>
                                    @endif
                                    <div class="font-semibold">{{ $day['high'] ?? '--' }}°</div>
                                    <div class="text-white/70">{{ $day['low'] ?? '--' }}°</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Last Updated -->
                <div class="text-center text-xs text-white/70 mt-4">
                    Last updated: {{ $data['last_updated'] ?? 'Never' }}
                </div>
            </div>
        @else
            <!-- No Data State -->
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <x-heroicon-o-cloud class="mx-auto h-12 w-12 text-white/70 mb-3" />
                    <p class="text-lg font-medium">Weather Data Unavailable</p>
                    <p class="text-sm text-white/70 mt-1">Configure location to see weather information</p>
                    @if(!empty($data['settings']['location']))
                        <p class="text-xs text-white/60 mt-2">Location: {{ $data['settings']['location'] }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>