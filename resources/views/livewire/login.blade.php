<form class="space-y-4 md:space-y-6" action="#">
    <div>
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
        <input type="email" name="email" id="email" wire:model.live="email" class="bg-gray-50 border @error("email") border-danger-600 @else border-gray-300 @enderror text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="">
        @error("email")
            <div class="fi-fo-field-wrp-error-message text-sm text-danger-600 dark:text-danger-400">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
        <input type="password" name="password" id="password" wire:model.live="password" placeholder="••••••••" class="@error("password") border-danger-600 @else border-gray-300 @enderror bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
        @error("password")
            <div class="fi-fo-field-wrp-error-message text-sm text-danger-600 dark:text-danger-400">{{ $message }}</div>
        @enderror
    </div>
    <div class="flex items-center justify-between">
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" required="">
            </div>
            <div class="ml-3 text-sm">
                <label for="remember" class="text-gray-500 dark:text-gray-300">Remember me</label>
            </div>
        </div>
        <a href="{{ route('password.reset') }}" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">Forgot password?</a>
    </div>
    {{
        $this->loginActionButton()
    }}
</form>
