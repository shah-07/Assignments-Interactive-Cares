<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'image',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
