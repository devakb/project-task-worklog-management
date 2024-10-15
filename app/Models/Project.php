<?php

namespace App\Models;

use App\ProjectIsAssignedAndAllocated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, ProjectIsAssignedAndAllocated;

    protected $fillable = [
        "name",
        "code",
        "start_date",
        "end_date",
        "client_name",
        "client_email",
        "client_phone",
        "status",
    ];

    const SELECT_STATUS_TYPES = [
        'upcoming' => 'Upcoming',
        'active' => "Active",
        'gsr' => "GSR",
        'closed' => "Closed",
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function members(){
        return $this->hasMany(ProjectMember::class);
    }



}
