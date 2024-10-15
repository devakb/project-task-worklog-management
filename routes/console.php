<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());

    // $pattern = '/^(?:(?<weeks>\d+)[w]\s+)?(?:(?<days>\d+)[d]\s+)?(?:(?<hours>\d+)[h]\s*)?(?:(?<minutes>\d+)[m]\s*)?$/';
    // $value = "2w 2w  ";
    // // Check if the value matches the pattern and extract the groups
    // if (preg_match($pattern, $value, $matches)) {
    //     // Extract the components and ensure they are within valid ranges
    //     $weeks = isset($matches['weeks']) ? intval($matches['weeks']) : 0;
    //     $days = isset($matches['days']) ? intval($matches['days']) : 0;
    //     $hours = isset($matches['hours']) ? intval($matches['hours']) : 0;
    //     $minutes = isset($matches['minutes']) ? intval($matches['minutes']) : 0;

    //     // Validate ranges: weeks (<=999), days (<=6), hours (<=23), minutes (<=59)
    //     if ($weeks > 999 || $days > 6 || $hours > 23 || $minutes > 59) {

    //     }

    //     dd('ok :: match');
    // }

    // dd('ok');
})->purpose('Display an inspiring quote')->hourly();
