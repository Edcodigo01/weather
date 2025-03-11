<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    protected $fillable = [
        "user_id",
        "location",
        "region",
        "country",
        "localtime",
        "temp_c",
        "is_day",
        "condition_text",
        "condition_icon",
        "wind_dir",
        "wind_kph",
        "feelslike_c",
        "gust_kph",
    ];
}
