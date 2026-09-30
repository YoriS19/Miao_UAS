@extends('layouts.app')
@section('content')
<h1>Settings</h1>
<section class="card row"><span class="muted">Username</span><b>{{ auth()->user()->username }}</b><span class="muted row">Email</span><b>{{ auth()->user()->email }}</b></section>
@endsection
