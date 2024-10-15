<form class="space-y-4 md:space-y-6" action="#">
    @if($this->isPasswordChanged)
        <div class="mb-4 flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
                <span class="sr-only">Info</span>
            <div>
            <span>Password Changed Successfully</span>
            </div>
        </div>

        <button onclick="window.location = '{{route('login')}}'" style="--c-400:var(--info-400);--c-500:var(--info-500);--c-600:var(--info-600);" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-info fi-color-info fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action w-full fi-ac-btn-action" type="button">
            <span class="fi-btn-label">
               Login Now
            </span>
        </button>
    @else
    <div>
        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
        <input type="password" name="password" id="password" wire:model.live="password" placeholder="••••••••" class="@error("password") border-danger-600 @else border-gray-300 @enderror bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
        @error("password")
            <div class="fi-fo-field-wrp-error-message text-sm text-danger-600 dark:text-danger-400">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" wire:model.live="confirm_password" placeholder="••••••••" class="@error("confirm_password") border-danger-600 @else border-gray-300 @enderror bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
        @error("confirm_password")
            <div class="fi-fo-field-wrp-error-message text-sm text-danger-600 dark:text-danger-400">{{ $message }}</div>
        @enderror
    </div>
    <div class="flex items-center justify-between">
        {{
            $this->passwordChangeActionButton()
        }}
    </div>
    @endif


    <script>
        window.addEventListener('passwordChanged', () => {
            setTimeout(() => {
                window.location = "{{route('login')}}";
            }, 5000);
        })
    </script>
</form>
