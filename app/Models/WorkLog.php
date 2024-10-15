<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkLog extends Model
{
    use HasFactory;

    protected $fillable = [
        "project_id",
        "task_id",
        "user_id",
        "date",
        "logged_hours_string",
        "logged_minutes_int",
        "description",
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime:Y-m-d',
        ];
    }


    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function task(){
        return $this->belongsTo(Task::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Handle "creating" event
        static::creating(function ($model) {
            $model->logged_minutes_int = self::convertToMinutes($model->logged_hours_string);
        });

        // Handle "updating" event
        static::updating(function ($model) {
            $model->logged_minutes_int = self::convertToMinutes($model->logged_hours_string);
        });
    }

    /**
     * Convert hour log string to total minutes.
     *
     * @param string $hourLog
     * @return int
     */
    public static function convertToMinutes($hourLog)
    {


        // Regular expression for matching the format "2w 8h 2m"
        $pattern = '/^(?:(?<weeks>\d+)[w]\s+)?(?:(?<days>\d+)[d]\s+)?(?:(?<hours>\d+)[h]\s*)?(?:(?<minutes>\d+)[m]\s*)?$/';

        preg_match($pattern, $hourLog, $matches);

        // Initialize weeks, hours, and minutes to zero if not found
        $weeks = isset($matches['weeks']) ? intval($matches['weeks']) : 0;
        $days = isset($matches['days']) ? intval($matches['days']) : 0;
        $hours = isset($matches['hours']) ? intval($matches['hours']) : 0;
        $minutes = isset($matches['minutes']) ? intval($matches['minutes']) : 0;

        // Convert everything to minutes
        $totalMinutes =  ($weeks * 7 * 24 * 60) + ($days * 24 * 60) + ($hours * 60) + $minutes;

        return $totalMinutes;
    }

    public  static function convertMinutesToFormat($totalMinutes) {


        if($totalMinutes == 0 || empty($totalMinutes)){
            return "0h";
        }

        // Constants for time conversions
        $minutesInHour = 60;
        $minutesInDay = 1440; // 24 hours * 60 minutes
        $minutesInWeek = 10080; // 7 days * 24 hours * 60 minutes

        // Calculate weeks, days, hours, and minutes
        $weeks = floor($totalMinutes / $minutesInWeek);
        $totalMinutes %= $minutesInWeek;

        $days = floor($totalMinutes / $minutesInDay);
        $totalMinutes %= $minutesInDay;

        $hours = floor($totalMinutes / $minutesInHour);
        $minutes = $totalMinutes % $minutesInHour;

        // Prepare the result string
        $result = [];

        if ($weeks > 0) {
            $result[] = "{$weeks}w";
        }
        if ($days > 0) {
            $result[] = "{$days}d";
        }
        if ($hours > 0) {
            $result[] = "{$hours}h";
        }
        if ($minutes > 0) {
            $result[] = "{$minutes}m";
        }

        return implode(' ', $result);
    }


    public function getUserAvatar($user){

        if($user instanceof User){
            return $user->getAvatar();
        }else{
            return (new User)->getAvatar();
        }

    }
}
