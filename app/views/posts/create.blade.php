@extends('posts/form')

@section('form_header')
    {{ Form::model(array(), array('route' => array('posts.store'), 'method' => 'post'))}}
@stop
