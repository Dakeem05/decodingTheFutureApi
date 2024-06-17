<?php

namespace App\Models;

use App\Traits\Api\V1\ApiResponseTrait;
use App\Traits\CreateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventRegistration extends Model
{
    use HasFactory, CreateUuid, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_att' => 'datetime',
        ];
    }
}
