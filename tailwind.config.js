import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './resources/**/*.php',
        './resources/views/components/**/*.blade.php',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/livewire/**/*.blade.php',
        './resources/views/livewire/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './app/**/*.php',
    ],

    darkClass: false,
}
