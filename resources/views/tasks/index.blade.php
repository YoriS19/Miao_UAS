@extends('layouts.app')
@section('content')
<div class="item"><h1>Tasks</h1><a class="btn" href="{{ route('tasks.create') }}">Create Task</a></div>
<div class="stack row">
@forelse($tasks as $t)
<article class="card stack"><div class="item"><a href="{{ route('tasks.show',$t) }}"><b>{{ $t->task_title }}</b><span class="muted">{{ $t->task_category }} · {{ $t->task_priority }} · {{ $t->task_deadline->format('j F Y') }} · {{ $t->task_status }}</span></a>
<div class="more"><button type="button" aria-label="More actions">...</button><div class="menu" hidden><a href="{{ route('tasks.show',$t) }}">View</a><a href="{{ route('tasks.show',[$t,'edit'=>1]) }}">Edit</a>
<form method="POST" action="{{ route('tasks.destroy',$t) }}" data-confirm="Delete this task and its subtasks?">@csrf @method('DELETE')<button>Delete</button></form></div></div></div>
<div class="prog"><div class="bar"><i data-w="{{ $t->progress }}"></i></div><span class="pct">{{ $t->progress }}%</span></div></article>
@empty<p class="muted">No tasks found. <a class="link" href="{{ route('tasks.create') }}">Create a task</a></p>@endforelse
</div>
@endsection
