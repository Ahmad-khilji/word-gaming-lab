<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = [
        'target_word', 'attempts', 'is_won', 'time_taken','selected_word'
    ];

}
