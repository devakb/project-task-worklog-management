<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HourLogFormat implements ValidationRule
{

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = trim($value) . " ";
        // Regular expression for matching and extracting weeks, days, hours, and minutes
        $pattern = '/^(?:(?<weeks>\d+)[w]\s+)?(?:(?<days>\d+)[d]\s+)?(?:(?<hours>\d+)[h]\s*)?(?:(?<minutes>\d+)[m]\s*)?$/';

        // Check if the value matches the pattern and extract the groups
        if (preg_match($pattern, $value, $matches)) {
            // Extract the components and ensure they are within valid ranges
            $weeks = isset($matches['weeks']) ? intval($matches['weeks']) : 0;
            $days = isset($matches['days']) ? intval($matches['days']) : 0;
            $hours = isset($matches['hours']) ? intval($matches['hours']) : 0;
            $minutes = isset($matches['minutes']) ? intval($matches['minutes']) : 0;

            // Validate ranges: weeks (<=999), days (<=6), hours (<=23), minutes (<=59)
            if ($weeks > 999 || $days > 6 || $hours > 23 || $minutes > 59) {
                $fail('The :attribute format is invalid.');
             }
         }else{
            $fail('The :attribute format is invalid.');
         }


    }
}
