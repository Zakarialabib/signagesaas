<div>
    {{-- Add ContextualHelpWidget --}}
    @livewire('shared.contextual-help-widget', ['contextKey' => \App\Enums\OnboardingStep::PROFILE_COMPLETED->value])

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-(--breakpoint-xl) mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:items-center mb-8">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Profile Settings</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Manage your personal information, preferences, and account security.
                </p>
            </div>
        </div>

        @if ($showSuccessAlert)
            <div class="mb-6 bg-green-50 dark:bg-green-900 p-4 rounded-md">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            Your profile has been updated successfully!
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button wire:click="dismissAlert" type="button"
                                class="inline-flex rounded-md p-1.5 text-green-500 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-800">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Profile Information -->
            <div
                class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 sm:rounded-xl">
                <div class="px-4 py-6 sm:p-8">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">Profile Information
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                        Update your personal information and preferences.
                    </p>

                    <form wire:submit="updateProfile" class="mt-6 grid grid-cols-1 gap-y-6 sm:grid-cols-6 gap-x-4">
                        <div class="col-span-full">
                            <label for="name"
                                class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                Name
                            </label>
                            <div class="mt-2">
                                <input type="text" id="name" wire:model="name" name="name"
                                    autocomplete="name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-full">
                            <label for="email"
                                class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                Email address
                            </label>
                            <div class="mt-2">
                                <input type="email" id="email" wire:model="email" name="email"
                                    autocomplete="email"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-full">
                            <label for="job_title"
                                class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                Job Title
                            </label>
                            <div class="mt-2">
                                <input type="text" id="job_title" wire:model="job_title" name="job_title"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            @error('job_title')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="timezone"
                                class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                Timezone
                            </label>
                            <div class="mt-2">
                                <select id="timezone" wire:model="timezone" name="timezone"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="UTC">UTC</option>
                                    <option value="America/New_York">Eastern Time (US & Canada)</option>
                                    <option value="America/Chicago">Central Time (US & Canada)</option>
                                    <option value="America/Denver">Mountain Time (US & Canada)</option>
                                    <option value="America/Los_Angeles">Pacific Time (US & Canada)</option>
                                    <option value="Europe/London">London</option>
                                    <option value="Europe/Paris">Paris</option>
                                    <option value="Asia/Tokyo">Tokyo</option>
                                    <option value="Asia/Dubai">Dubai</option>
                                </select>
                            </div>
                            @error('timezone')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="language"
                                class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                Language
                            </label>
                            <div class="mt-2">
                                <select id="language" wire:model="language" name="language"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="en">English</option>
                                    <option value="ar">Arabic</option>
                                </select>
                            </div>
                            @error('language')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-full">
                            <label for="photo"
                                class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                Profile Photo
                            </label>
                            <div class="mt-2 flex items-center gap-x-3">
                                <div class="h-12 w-12 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                    @if (Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                            alt="Profile photo" class="h-full w-full object-cover">
                                    @else
                                        <span
                                            class="flex h-full w-full items-center justify-center text-gray-500 dark:text-gray-400">
                                            {{ Auth::user()->initials ?? 'U' }}
                                        </span>
                                    @endif
                                </div>
                                <input type="file" wire:model="photo" id="photo" accept="image/*"
                                    class="block text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-200 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800">
                            </div>
                            <div wire:loading wire:target="photo"
                                class="mt-2 text-sm text-indigo-600 dark:text-indigo-400">
                                Uploading...
                            </div>
                            @error('photo')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-full flex justify-end mt-4">
                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Save Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div
                class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 sm:rounded-xl">
                <div class="px-4 py-6 sm:p-8">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">Change Password</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                        Update your password to keep your account secure.
                    </p>

                    <form wire:submit="updatePassword" class="mt-6 grid grid-cols-1 gap-y-6">
                        <div>
                            <label for="password"
                                class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                New Password
                            </label>
                            <div class="mt-2">
                                <input type="password" id="password" wire:model="password" name="password"
                                    autocomplete="new-password"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                Confirm Password
                            </label>
                            <div class="mt-2">
                                <input type="password" id="password_confirmation" wire:model="password_confirmation"
                                    name="password_confirmation" autocomplete="new-password"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
