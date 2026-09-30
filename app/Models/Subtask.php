<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    protected $table = 'tb_subtasks';
    protected $primaryKey = 'subtask_id';
    const CREATED_AT = 'subtask_created_at';
    const UPDATED_AT = 'subtask_updated_at';
    protected $fillable = ['task_id', 'subtask_title', 'subtask_is_completed', 'subtask_position'];
    protected $casts = ['subtask_is_completed' => 'boolean'];
    public function task() { return $this->belongsTo(Task::class, 'task_id'); }
}
