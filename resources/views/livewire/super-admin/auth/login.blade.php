<div>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-12 w-auto" src="{{ asset('images/logo.svg') }}" alt="SignageSaaS">
            <h2 class="mt-8 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900 dark:text-white">
                SuperAdmin Access
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Secure login for system administrators
            </p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form wire:submit="login" class="space-y-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 ring-1 ring-inset ring-gray-300 dark:ring-gray-700">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Email address
                    </label>
                    <div class="mt-2">
                        <input 
                            wire:model="email" 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required
                            @class([
                                'block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6',
                                'text-gray-900 dark:text-gray-100 dark:bg-gray-700 ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-indigo-600' => !$errors->has('email'),
                                'ring-red-300 dark:ring-red-700 focus:ring-red-500' => $errors->has('email')
                            ])
                        >
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Password
                        </label>
                    </div>
                    <div class="mt-2">
                        <input 
                            wire:model="password" 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required
                            @class([
                                'block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6',
                                'text-gray-900 dark:text-gray-100 dark:bg-gray-700 ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-indigo-600' => !$errors->has('password'),
                                'ring-red-300 dark:ring-red-700 focus:ring-red-500' => $errors->has('password')
                            ])
                        >
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center">
                    <input 
                        wire:model="remember" 
                        id="remember" 
                        name="remember" 
                        type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-600 dark:bg-gray-700 dark:checked:bg-indigo-600"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                        Remember me
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
