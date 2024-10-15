<?php

namespace App;

use App\Models\ProjectMember;
use Illuminate\Support\Facades\App;

trait ProjectIsAssignedAndAllocated
{
    public static function bootProjectIsAssignedAndAllocated(){

        static::addGlobalScope(function($query){
            if(!App::runningInConsole()){
                if(optional(auth())->check() && !optional(auth())->user()->is_senior_member){
                    $query->whereIn('id', ProjectMember::where('user_id', optional(auth())->id())->where('is_allocated', true)->pluck('project_id'));
                }
            }
        });

    }
}
