@extends('posts/form')

@section('form_header')
    {{ Form::model($post, array('route' => array('posts.update', $post->id), 'method' => 'put'))}}
@stop
