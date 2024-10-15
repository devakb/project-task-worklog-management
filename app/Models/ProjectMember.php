<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'user_id', 'is_allocated', 'engagement', 'role', 'skills'];

    const SELECT_ENGAGEMENT = [
        "as required" => "As required",
        "half_day_50%" => "Half Day (50%)",
        "dedicated" => "Dedicated (100%)",
        "sporadic" => "Sporadic",
    ];
    const SELECT_STATUS = [
        0 => "Deallocated",
        1 => "Allocated",
    ];

    const SELECT_ROLE = [
        "developer"                      => "Developer",
        "senior_developer"               => "Senior Developer",
        "tech_lead"                      => "Tech Lead",
        "assistant_project_manager"      => "Assistant Project Manager",
        "senior_project_manager"         => "Senior Project Manager",
        "project_coordinator"            => "Project Coordinator",
        "qa_tester"                      => "QA Tester",
        "qa_lead"                        => "QA Lead",
        "admin"                          => "Administrator",
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'skills' => 'array',
        ];
    }

    public function getUserAvatar($user){

        if($user instanceof User){
            return $user->getAvatar();
        }else{
            return (new User)->getAvatar();
        }

    }
}
