<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FirebaseToken extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = 'fcm_tokens';
}
