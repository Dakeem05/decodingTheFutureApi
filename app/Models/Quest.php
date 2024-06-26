<?php

namespace App\Models;

use App\Traits\CreateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quest extends Model
{
    use HasFactory, CreateUuid, SoftDeletes;

    protected $guarded = [];
    
    public function userquests(): HasMany
    {
        return $this->hasMany(UserQuest::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
