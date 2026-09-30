@extends('layouts.app')
@section('content')
@php $pc=$total?round($done/$total*100):0; $h=now()->hour; @endphp
<section><h1>Good {{ $h<12?'Morning':($h<18?'Afternoon':'Evening') }}, {{ auth()->user()->username }}!</h1><p class="muted">{{ now()->format('l, j F Y') }}</p></section>
<div class="grid2">
<article class="card"><h2>Today's Focus</h2><p class="muted">Your top priorities for today</p>
<ul class="list">@forelse($focus as $t)<li><a href="{{ route('tasks.show',$t) }}"><b>{{ $t->task_title }}</b><span class="muted">{{ $t->task_priority }} · {{ $t->due_label }}</span></a></li>@empty<li class="muted">No focus yet. Pick up to 3 tasks to start.</li>@endforelse</ul>
<a class="link" href="{{ route('focus') }}">View Daily Focus →</a></article>
<article class="card"><h2>Overall Progress</h2><div class="big">{{ $pc }}%</div><div class="prog"><div class="bar"><i data-w="{{ $pc }}"></i></div></div><p class="muted">{{ $done }} of {{ $total }} tasks completed</p></article>
</div>
<article class="card"><h2>Upcoming Deadlines</h2><ul class="cols">@forelse($upcoming as $t)<li><a href="{{ route('tasks.show',$t) }}"><b>{{ $t->task_title }}</b><span class="muted">{{ $t->task_deadline->format('j F Y') }}</span></a></li>@empty<li class="muted">No upcoming deadlines.</li>@endforelse</ul></article>
<article class="card row"><h2>Active Tasks</h2><ul class="list">@forelse($active as $t)<li class="prog"><a href="{{ route('tasks.show',$t) }}">{{ $t->task_title }}</a><div class="bar"><i data-w="{{ $t->progress }}"></i></div><span class="pct">{{ $t->progress }}%</span></li>@empty<li class="muted">No active tasks. <a class="link" href="{{ route('tasks.create') }}">Create your first task</a></li>@endforelse</ul></article>
@endsection
