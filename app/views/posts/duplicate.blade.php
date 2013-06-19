@extends('layout')

@section('content')
    @foreach($posts as $posts)
        <p>{{ $posts->user->pseudo }}: <b>{{ $posts->title }}</b></p>
    @endforeach
@stop
