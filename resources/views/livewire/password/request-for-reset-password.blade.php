<form class="space-y-4 md:space-y-6" action="#">
    <div>
        @if(!empty($this->success_message))
            <div class="mb-4 flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                    <span class="sr-only">Info</span>
                <div>
                <span>{{ $this->success_message }}</span>
                </div>
            </div>
        @endif

        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
        <input type="email" name="email" id="email" wire:model.live="email" class="bg-gray-50 border @error("email") border-danger-600 @else border-gray-300 @enderror text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="">
        @error("email")
            <div class="fi-fo-field-wrp-error-message text-sm text-danger-600 dark:text-danger-400">{{ $message }}</div>
        @enderror
    </div>
    <div class="flex items-center justify-between">
        {{ $this->passwordResetRequestActionButton() }}
    </div>
</form>

