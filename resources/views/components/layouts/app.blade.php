<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="icon" href="{{ asset("favicon.ico") }}?v={{ date("ymdh") }}">

        <title>{{ config('app.name') }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" >

        @filamentStyles
        @vite('resources/css/app.css')

    </head>

    <body class="antialiased">

        <x-partials.admin-sidebar-navbar>
            {{ $slot }}
        </x-partials.admin-sidebar-navbar>

        @livewire('notifications')

        <script src="{{ asset('js/global.js') }}"></script>
        @filamentScripts

        {{ $scripts ?? null }}
    </body>
</html>
