<div>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-4 text-gray-900 dark:text-gray-100">
                Start Your Free Trial
            </h2>
            
            @if($selectedPlan)
            <div class="mb-6 bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg border border-indigo-100 dark:border-indigo-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-indigo-700 dark:text-indigo-300">{{ $selectedPlan->name }} Plan</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedPlan->description }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-indigo-700 dark:text-indigo-300">
                            ${{ number_format($billingCycle === 'monthly' ? $selectedPlan->price_monthly : $selectedPlan->price_yearly, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($billingCycle) }} billing</p>
                    </div>
                </div>
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    <p>• {{ $selectedPlan->max_devices }} Devices • {{ $selectedPlan->max_screens }} Screens • {{ $selectedPlan->max_users }} Users</p>
                </div>
            </div>
            @endif

            @if($errors->has('registration'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    {{ $errors->first('registration') }}
                </div>
            @endif

            <form wire:submit="register">
                <div class="space-y-6">
                    <!-- Company Name -->
                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Company Name
                        </label>
                        <div class="mt-1">
                            <input wire:model="company" id="company" type="text" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        @error('company')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Full Name
                        </label>
                        <div class="mt-1">
                            <input wire:model="name" id="name" type="text" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        @error('name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email
                        </label>
                        <div class="mt-1">
                            <input wire:model="email" id="email" type="email" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        @error('email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password
                        </label>
                        <div class="mt-1">
                            <input wire:model="password" id="password" type="password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        @error('password')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Confirm Password
                        </label>
                        <div class="mt-1">
                            <input wire:model="password_confirmation" id="password_confirmation" type="password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        @error('password_confirmation')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Create Account{{ $selectedPlan ? ' & Start Trial' : '' }}
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            Already have an account?
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login') }}"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-indigo-400 dark:hover:bg-gray-600">
                        Sign in to your account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>