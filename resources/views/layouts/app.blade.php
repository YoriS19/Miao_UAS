@php
$nav=[['dashboard','Dashboard','dashboard_icon',['dashboard']],['tasks.index','Tasks','task_icon',['tasks.index','tasks.show']],['tasks.create','Create Task','create_icon',['tasks.create']],['focus','Daily Focus','focus_icon',['focus']],['settings','Settings','setting_icon',['settings']]];
$quick=[['tasks.create','create_icon','Add task'],['tasks.index','task_icon','Tasks'],['focus','focus_icon','Daily focus'],['settings','user_icon','Profile']];
$due=auth()->user()->tasks()->where('task_status','!=','Completed')->whereDate('task_deadline','<=',today()->addDay())->orderBy('task_deadline')->get();
$img=fn($n)=>asset("img/$n.png");
@endphp
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>Miáo</title><link rel="stylesheet" href="{{ asset('css/style.css') }}"></head>
<body>
<aside class="side"><a class="brand" href="{{ route('dashboard') }}"><img src="{{ $img('mascot') }}" alt=""><span>Miáo</span></a>
<nav aria-label="Main">@foreach($nav as $n)<a class="nv {{ request()->routeIs(...$n[3]) ? 'on' : '' }}" href="{{ route($n[0]) }}"><img src="{{ $img($n[2]) }}" alt=""><span>{{ $n[1] }}</span></a>@endforeach</nav>
<form method="POST" action="{{ route('logout') }}" style="margin-top:auto">@csrf<button class="nv out"><b aria-hidden="true">⎋</b><span>Logout</span></button></form></aside>
<div class="shade" id="shade"></div>
<main class="main">
<header class="top"><button class="dots" id="dots" aria-label="Toggle sidebar">•••</button>
<a class="avatar" href="{{ route('settings') }}" aria-label="Profile"><img src="{{ $img('user_icon') }}" alt=""></a>
<form class="search" method="GET" action="{{ route('tasks.index') }}" role="search"><img src="{{ $img('search_icon') }}" alt=""><input name="q" value="{{ request('q') }}" type="search" placeholder="Search your tasks..." aria-label="Search your tasks"></form>
<div class="bellwrap"><button class="bell" id="bell" type="button" aria-label="Notifications"><img src="{{ $img('notification_icon') }}" alt=""></button>
<div class="pop" id="notif" hidden>@forelse($due as $t)<a class="nt" href="{{ route('tasks.show',$t) }}"><b>{{ $t->task_title }}</b><span class="muted">Due {{ strtolower($t->due_label) }}</span></a>@empty<p class="muted">You're all caught up.</p>@endforelse</div></div></header>
@yield('content')
</main>
<nav class="quick" aria-label="Quick actions">@foreach($quick as $q)<a class="{{ request()->routeIs($q[0]) ? 'on' : '' }}" href="{{ route($q[0]) }}" aria-label="{{ $q[2] }}"><img src="{{ $img($q[1]) }}" alt=""></a>@endforeach</nav>
@if(session('ok'))<div class="toast">{{ session('ok') }}</div>@endif
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body></html>
