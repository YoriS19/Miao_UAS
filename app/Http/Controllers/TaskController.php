<?php
namespace App\Http\Controllers;

use App\Models\DailyFocus;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private function own(Task $t): Task { abort_unless($t->user_id === auth()->id(), 404); return $t; }

    private function rules(): array
    {
        return [
            'task_title' => 'required|max:150',
            'task_description' => 'nullable|max:1000',
            'task_category' => 'required|in:Academic,Personal,Work,Other',
            'task_priority' => 'required|in:Low,Medium,High',
            'task_deadline' => 'required|date',
        ];
    }

    public function dashboard()
    {
        $tasks = auth()->user()->tasks()->with('subtasks')->get();
        $ids = DailyFocus::whereIn('task_id', $tasks->pluck('task_id'))->whereDate('focus_date', today())->pluck('task_id');
        $open = $tasks->where('task_status', '!=', 'Completed');
        return view('dashboard', [
            'focus' => $tasks->whereIn('task_id', $ids),
            'total' => $tasks->count(),
            'done' => $tasks->count() - $open->count(),
            'upcoming' => $open->sortBy('task_deadline')->take(3),
            'active' => $open->sortByDesc('task_id')->take(5),
        ]);
    }

    public function index(Request $r)
    {
        $tasks = auth()->user()->tasks()->with('subtasks')
            ->when($r->q, fn($q, $s) => $q->where('task_title', 'like', "%$s%"))
            ->latest('task_id')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create() { return view('tasks.create'); }

    public function store(Request $r)
    {
        $d = $r->validate($this->rules() + ['subtasks.*' => 'nullable|string|max:150']);
        $task = auth()->user()->tasks()->create(collect($d)->except('subtasks')->all());
        $pos = 0;
        foreach (array_filter(array_map('trim', $r->input('subtasks', []))) as $title) {
            $task->subtasks()->create(['subtask_title' => $title, 'subtask_position' => ++$pos]);
        }
        $task->syncStatus();
        return redirect()->route('tasks.show', $task)->with('ok', 'Task created');
    }

    public function show(Task $task)
    {
        $task = $this->own($task)->load('subtasks');
        $num = auth()->user()->tasks()->where('task_id', '<=', $task->task_id)->count();
        return view('tasks.show', compact('task', 'num'));
    }

    public function update(Request $r, Task $task)
    {
        $this->own($task)->update($r->validate($this->rules()));
        return redirect()->route('tasks.show', $task)->with('ok', 'Task updated');
    }

    public function destroy(Task $task)
    {
        $this->own($task)->delete();
        return redirect()->route('tasks.index')->with('ok', 'Task deleted');
    }
}
