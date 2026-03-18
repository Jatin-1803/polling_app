<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'question', 'admin_id','is_active'];

    protected static function booted()
    {
        static::creating(function ($poll) {
            if (empty($poll->uuid)) {
                $poll->uuid = (string) Str::uuid();
            }
        });
    }

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}