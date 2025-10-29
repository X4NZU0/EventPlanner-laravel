<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRegistration extends Model
{
    use HasFactory;

    protected $table = 'user_registration';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'user_student_id',
        'user_name',
        'user_email',
        'user_password',
        'user_year_lvl',
        'user_number',
    ];

    protected $hidden = [
        'user_password',
    ];
}