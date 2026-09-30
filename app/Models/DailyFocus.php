<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyFocus extends Model
{
    protected $table = 'tb_daily_focus';
    protected $primaryKey = 'focus_id';
    const CREATED_AT = 'focus_created_at';
    const UPDATED_AT = 'focus_updated_at';
    protected $fillable = ['task_id', 'focus_date'];
}
