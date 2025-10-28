<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $timestamps = false;
    protected $fillable = [

        "event_name",
        "event_details",
        "event_location",
        "event_date",
        "event_img",  
    ];
    protected $table = "event";
    protected $primaryKey = "event_id";
}