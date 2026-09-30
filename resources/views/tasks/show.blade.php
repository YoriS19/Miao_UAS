@extends('layouts.app')
@section('content')
@php $subs=$task->subtasks; $done=$subs->where('subtask_is_completed',true)->count(); $cur=$subs->firstWhere('subtask_is_completed',false)?->subtask_id; @endphp
<section class="card">
<div class="item"><div class="prog"><div class="no">{{ str_pad($num,2,'0',STR_PAD_LEFT) }}</div><div><h1>{{ $task->task_title }}</h1><span class="muted">{{ $task->task_category }} · {{ $task->task_priority }} Priority · {{ $task->task_deadline->format('j F Y') }}</span></div></div>
<div class="acts"><button type="button" class="btn ghost" data-open="editTask">Edit</button>
<form method="POST" action="{{ route('tasks.destroy',$task) }}" data-confirm="Delete this task and its subtasks?">@csrf @method('DELETE')<button class="btn dark">Delete</button></form></div></div>
@if($task->task_description)<p class="row">{{ $task->task_description }}</p>@endif
<div class="row"><b>Progress</b><span class="muted">{{ $done }} / {{ $subs->count() }} subtasks completed</span><div class="prog"><div class="bar"><i data-w="{{ $task->progress }}"></i></div><span class="pct">{{ $task->progress }}%</span></div></div>
</section>
<section class="card row"><div class="item"><div><h2>Your Steps</h2><span class="muted">Drag the ⋮⋮ handle to reorder</span></div><button type="button" class="btn" data-open="addSub">Add Subtask</button></div>
<ul class="list" id="steps" data-url="{{ route('subtasks.reorder',$task) }}">
@forelse($subs as $s)
<li class="step {{ $s->subtask_is_completed?'done':'' }}" data-id="{{ $s->subtask_id }}" draggable="true">
<span class="handle" aria-hidden="true" title="Drag to reorder">⋮⋮</span>
<form method="POST" action="{{ route('subtasks.toggle',$s) }}">@csrf @method('PATCH')<input type="checkbox" onchange="this.form.submit()" @checked($s->subtask_is_completed) aria-label="Mark completed"></form>
<div><b>{{ $s->subtask_title }}</b><span class="muted">{{ $s->subtask_is_completed?'Completed':($s->subtask_id===$cur && $done>0?'In Progress':'Not Started') }}</span></div>
<div class="more"><button type="button" aria-label="More actions">...</button><div class="menu" hidden><button type="button" data-open="es{{ $s->subtask_id }}">Edit</button>
<form method="POST" action="{{ route('subtasks.destroy',$s) }}" data-confirm="Delete this subtask?">@csrf @method('DELETE')<button>Delete</button></form></div></div></li>
@empty<li class="muted">No subtasks yet. Add the first step.</li>@endforelse
</ul></section>

<dialog class="dlg" id="editTask"><h2>Edit Task</h2><form method="POST" action="{{ route('tasks.update',$task) }}" class="fg" novalidate>@csrf @method('PUT')@include('tasks._fields',['t'=>$task])<div class="acts"><button type="button" class="btn ghost" data-close>Cancel</button><button class="btn">Save changes</button></div></form></dialog>
<dialog class="dlg" id="addSub"><h2>Add Subtask</h2><form method="POST" action="{{ route('subtasks.store',$task) }}" class="fg">@csrf<div><label for="ns">Subtask title</label><input class="in" id="ns" name="subtask_title" maxlength="150" required placeholder="What is the next step?"></div><div class="acts"><button type="button" class="btn ghost" data-close>Cancel</button><button class="btn">Add Subtask</button></div></form></dialog>
@foreach($subs as $s)<dialog class="dlg" id="es{{ $s->subtask_id }}"><h2>Edit Subtask</h2><form method="POST" action="{{ route('subtasks.update',$s) }}" class="fg">@csrf @method('PATCH')<div><label>Subtask title</label><input class="in" name="subtask_title" maxlength="150" required value="{{ $s->subtask_title }}"></div><div class="acts"><button type="button" class="btn ghost" data-close>Cancel</button><button class="btn">Save changes</button></div></form></dialog>@endforeach
@endsection
@push('scripts')<script>
@if(request('edit') || $errors->has('task_title') || $errors->has('task_deadline'))document.getElementById('editTask').showModal();@endif
const ul=document.getElementById('steps');let drag=null;
ul.addEventListener('dragstart',e=>{drag=e.target.closest('.step');drag?.classList.add('drag')});
ul.addEventListener('dragover',e=>{if(!drag)return;e.preventDefault();const after=[...ul.querySelectorAll('.step:not(.drag)')].find(x=>e.clientY<x.getBoundingClientRect().top+x.offsetHeight/2);ul.insertBefore(drag,after||null)});
ul.addEventListener('dragend',()=>{if(!drag)return;drag.classList.remove('drag');drag=null;
 fetch(ul.dataset.url,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify({order:[...ul.querySelectorAll('.step')].map(x=>x.dataset.id)})})});
</script>@endpush
