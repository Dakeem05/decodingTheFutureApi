<?php

namespace App\Models;

use App\Traits\CreateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserQuest extends Model
{
    use HasFactory, CreateUuid, SoftDeletes;

    protected $guarded = [];
    
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
