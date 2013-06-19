@extends('layout')

@section('content')
	@if (Session::has('error'))
	    {{ trans(Session::get('reason')) }}
	@endif

	<form method='POST'>
		<input type="hidden" name="token" value="{{ $token }}">
		<div>{{ $user->email }}</div>
		<input type="password" name="password">
		<input type="password" name="password_confirmation">
		<input type="submit">
	</form>
@stop
