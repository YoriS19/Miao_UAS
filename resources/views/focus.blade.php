@extends('layouts.app')
@section('content')
<form method="POST" action="{{ route('focus.save') }}">@csrf
<div class="item"><div><h1>Daily Focus</h1><p class="muted">Choose up to 3 things to focus on today.</p></div><button class="btn">Save Focus</button></div>
<div class="two eq">
<section class="card"><h2>My Focus</h2><ul class="list">@forelse($mine as $t)<li><a href="{{ route('tasks.show',$t) }}"><b>{{ $t->task_title }}</b><span class="muted">{{ $t->task_priority }} · {{ $t->due_label }}</span></a></li>@empty<li class="muted">Nothing here yet. Tick up to 3 tasks, then save.</li>@endforelse</ul></section>
<section class="card"><h2>Choose from your Tasks</h2><div class="item"><span class="muted">Select up to 3 tasks</span><b id="count"></b></div><p class="err" id="msg">{{ $errors->first('tasks') }}</p>
<ul class="list">@forelse($open as $t)<li><label class="pick"><input type="checkbox" name="tasks[]" value="{{ $t->task_id }}" @checked(in_array($t->task_id,$sel))><span><b>{{ $t->task_title }}</b><span class="muted">{{ $t->task_priority }} · {{ $t->due_label }}</span></span></label></li>@empty<li class="muted">No tasks yet. <a class="link" href="{{ route('tasks.create') }}">Create one</a></li>@endforelse</ul></section>
</div></form>
@endsection
@push('scripts')<script>
const boxes=[...document.querySelectorAll('input[name="tasks[]"]')],cnt=()=>boxes.filter(b=>b.checked).length,msg=document.getElementById('msg');
const upd=()=>document.getElementById('count').textContent=cnt()+' / 3 selected';upd();
boxes.forEach(b=>b.addEventListener('change',()=>{if(b.checked&&cnt()>3){b.checked=false;msg.textContent='Select up to 3 tasks. Uncheck one to pick another.';msg.classList.remove('show');void msg.offsetWidth;msg.classList.add('show')}else msg.textContent='';upd()}));
</script>@endpush
