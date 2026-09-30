<?php
namespace App\Http\Controllers;

use App\Models\DailyFocus;
use Illuminate\Http\Request;

class FocusController extends Controller
{
    private function todayIds() { return DailyFocus::whereIn('task_id', auth()->user()->tasks()->pluck('task_id'))->whereDate('focus_date', today()); }

    public function index()
    {
        $tasks = auth()->user()->tasks()->get();
        $sel = $this->todayIds()->pluck('task_id')->all();
        return view('focus', [
            'sel' => $sel,
            'mine' => $tasks->whereIn('task_id', $sel),
            'open' => $tasks->filter(fn($t) => $t->task_status !== 'Completed' || in_array($t->task_id, $sel)),
        ]);
    }

    public function save(Request $r)
    {
        $ids = collect($r->input('tasks', []))->map(fn($i) => (int) $i)->unique();
        if ($ids->count() > 3) return back()->withErrors(['tasks' => 'Select up to 3 tasks']);
        $own = auth()->user()->tasks()->whereIn('task_id', $ids)->pluck('task_id');
        $this->todayIds()->delete();
        foreach ($own as $id) DailyFocus::create(['task_id' => $id, 'focus_date' => today()]);
        return redirect()->route('focus')->with('ok', 'Focus saved');
    }
}
