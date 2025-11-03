<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    protected $table = 'super_admin';
    protected $primaryKey = 'super_id';
    public $timestamps = false;

    protected $fillable = ['
    super_name',
    'super_email',
    'super_password'
];
}
