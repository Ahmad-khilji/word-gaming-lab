<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SevenWordGame extends Model
{
    use HasFactory, HasUuids;

    protected $fillable=['letter', 'date','theme'];
}
