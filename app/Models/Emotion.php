<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emotion extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','emotion','confidence','time'];
    protected $casts = ['confidence' => 'decimal:2'];

    public function user(){ return $this->belongsTo(User::class); }
}
