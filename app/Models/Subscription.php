<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'plan_id', 'current_usage'];

    // Relação Subscription-User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relação Subscription-Plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
