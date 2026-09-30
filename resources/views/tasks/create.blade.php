@extends('layouts.app')
@section('content')
<h1>Create a New Task</h1>
<form class="two" id="cf" method="POST" action="{{ route('tasks.store') }}" novalidate>@csrf
<div class="card"><div class="fg">@include('tasks._fields',['t'=>null])<div id="drafts" class="fg"></div>
<div class="acts"><button type="button" class="btn ghost" id="addSub">Add Subtask</button></div>
<div class="acts"><a class="btn ghost" href="{{ route('dashboard') }}">Cancel</a><button class="btn">Create Task</button></div></div></div>
<aside class="prev" aria-label="Task preview"><h2>Task Preview</h2><div class="ring" style="--p:0"><span>0%</span></div><b id="pvT"></b><span class="muted" id="pvM"></span><p id="pvD" class="row"></p><span class="muted" id="pvDl"></span><span class="muted" id="pvS"></span></aside>
</form>
@endsection
@push('scripts')<script>
const f=document.getElementById('cf'),g=id=>document.getElementById(id);
const pv=()=>{g('pvT').textContent=g('title').value.trim()||'Untitled task';g('pvM').textContent=g('cat').value+' · '+g('pri').value;g('pvD').textContent=g('desc').value.trim()||'No description yet.';g('pvDl').textContent=g('dl').value||'No deadline';g('pvS').textContent='0 / '+[...document.querySelectorAll('.draft input')].filter(i=>i.value.trim()).length+' subtasks completed'};
f.addEventListener('input',pv);f.addEventListener('change',pv);pv();
g('addSub').onclick=()=>{g('drafts').insertAdjacentHTML('beforeend','<div class="draft item"><input class="in" name="subtasks[]" placeholder="Subtask title..." maxlength="150" aria-label="Subtask title"><button type="button" class="btn ghost sm" aria-label="Remove">✕</button></div>');g('drafts').lastElementChild.querySelector('input').focus()};
g('drafts').onclick=e=>{const b=e.target.closest('button');if(b){b.parentElement.remove();pv()}};
</script>@endpush
