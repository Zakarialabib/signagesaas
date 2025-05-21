<div>
    <form wire:submit="claimDevice" class="space-y-4">
        <div>
            <label for="registrationCode" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                Registration Code
            </label>
            <div class="mt-2">
                <input
                    type="text"
                    id="registrationCode"
                    wire:model="registrationCode"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    placeholder="Enter 8-character code"
                    maxlength="8"
                >
            </div>
            @error('registrationCode')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
            Claim Device
        </button>
    </form>
</div> 