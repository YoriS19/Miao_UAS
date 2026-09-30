<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tb_tasks';
    protected $primaryKey = 'task_id';
    const CREATED_AT = 'task_created_at';
    const UPDATED_AT = 'task_updated_at';
    protected $fillable = ['user_id', 'task_title', 'task_description', 'task_deadline', 'task_priority', 'task_status', 'task_category'];
    protected $casts = ['task_deadline' => 'date'];

    public function subtasks() { return $this->hasMany(Subtask::class, 'task_id')->orderBy('subtask_position'); }

    public function getProgressAttribute(): int
    {
        $total = $this->subtasks->count();
        return $total ? (int) round($this->subtasks->where('subtask_is_completed', true)->count() / $total * 100) : 0;
    }

    public function getDueLabelAttribute(): string
    {
        $d = $this->task_deadline;
        return $d->isToday() ? 'Today' : ($d->isTomorrow() ? 'Tomorrow' : ($d->isPast() ? 'Overdue' : $d->format('j F Y')));
    }

    /** Hitung ulang status task dari subtask-nya (Not Started / In Progress / Completed) */
    public function syncStatus(): void
    {
        $subs = $this->subtasks()->get();
        $done = $subs->where('subtask_is_completed', true)->count();
        $this->update(['task_status' => $subs->count() && $done === $subs->count() ? 'Completed' : ($done ? 'In Progress' : 'Not Started')]);
    }
}
