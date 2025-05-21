@if(session()->has('impersonated_tenant'))
    @php
        $tenant = \App\Tenant\Models\Tenant::find(session('impersonated_tenant'));
    @endphp
    
    <div class="fixed top-0 right-0 left-0 z-50 bg-linear-to-r from-amber-500 via-amber-600 to-amber-500 text-white py-3 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-y-2">
                <div class="flex items-center space-x-2">
                    <div class="shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-100" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium sm:text-base">
                            You are impersonating <span class="font-bold">{{ $tenant->name ?? 'Unknown' }}</span>
                        </p>
                        <p class="text-xs text-amber-100">Changes will be made as if you were a tenant user</p>
                    </div>
                </div>
                
                <a href="{{ route('impersonate.stop') }}" 
                   class="inline-flex items-center rounded-md bg-white text-amber-700 px-3 py-2 text-sm font-semibold shadow-sm hover:bg-amber-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Return to SuperAdmin
                </a>
            </div>
        </div>
    </div>
    
    <!-- Add margin to body to prevent content overlap with fixed banner -->
    <style>
        body { padding-top: 4rem; }
        @media (min-width: 640px) { body { padding-top: 3.5rem; } }
    </style>
@endif 