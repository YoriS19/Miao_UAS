<?php
namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    private function ownTask(Task $t): Task { abort_unless($t->user_id === auth()->id(), 404); return $t; }
    private function ownSub(Subtask $s): Subtask { $this->ownTask($s->task); return $s; }

    public function store(Request $r, Task $task)
    {
        $d = $r->validate(['subtask_title' => 'required|max:150']);
        $pos = (Subtask::where('task_id', $this->ownTask($task)->task_id)->max('subtask_position') ?? 0) + 1;
        $task->subtasks()->create($d + ['subtask_position' => $pos]);
        $task->syncStatus();
        return back()->with('ok', 'Subtask added');
    }

    public function update(Request $r, Subtask $subtask)
    {
        $this->ownSub($subtask)->update($r->validate(['subtask_title' => 'required|max:150']));
        return back()->with('ok', 'Subtask updated');
    }

    public function toggle(Subtask $subtask)
    {
        $this->ownSub($subtask)->update(['subtask_is_completed' => !$subtask->subtask_is_completed]);
        $subtask->task->syncStatus();
        return back();
    }

    public function destroy(Subtask $subtask)
    {
        $task = $this->ownSub($subtask)->task;
        $subtask->delete();
        $task->syncStatus();
        return back()->with('ok', 'Subtask deleted');
    }

    /** Dipanggil saat subtask digeser (drag & drop): simpan urutan baru ke subtask_position */
    public function reorder(Request $r, Task $task)
    {
        $this->ownTask($task);
        foreach ($r->input('order', []) as $i => $id) {
            Subtask::where('task_id', $task->task_id)->where('subtask_id', $id)->update(['subtask_position' => $i + 1]);
        }
        return response()->json(['ok' => true]);
    }
}
