<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'title',
        'description', 'status', 'priority',
        'due_date', 'start_date', 'streak'
    ];

    protected $casts = [
        'due_date'   => 'date',
        'start_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // PRIORITY PREDICTION ALGORITHM
    public static function predictPriority($due_date): string
    {
        if (!$due_date) return 'medium';

        $daysLeft = Carbon::today()->diffInDays(Carbon::parse($due_date), false);

        if ($daysLeft < 0)  return 'high';
        if ($daysLeft <= 2) return 'high';
        if ($daysLeft <= 7) return 'medium';
        return 'low';
    }

    // REMINDER CHECK - is task due soon or overdue?
    public function getReminderStatusAttribute(): string
    {
        if (!$this->due_date) return 'none';
        if ($this->status === 'completed') return 'none';

        $daysLeft = Carbon::today()->diffInDays($this->due_date, false);

        if ($daysLeft < 0)  return 'overdue';
        if ($daysLeft <= 2) return 'due_soon';
        return 'none';
    }

    // STREAK - calculate streak from completed tasks
    public static function calculateStreak($userId): int
    {
        $streak = 0;
        $date = Carbon::today();

        while (true) {
            $hasTask = self::where('user_id', $userId)
                ->where('status', 'completed')
                ->whereDate('updated_at', $date)
                ->exists();

            if ($hasTask) {
                $streak++;
                $date->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }
}