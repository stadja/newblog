@extends('layout')

<?php 
/*$res = App::make('resources');
$res['js'][] = 'redactor.min.js';
$res['css_before'][] = 'redactor.css';
$res['js_fx'][] = "$('.redactor').redactor();";
$res['css_fx'][] = ".redactor_editor, .redactor_editor:focus {
    background-color: #090909 !important;
}";
$res = App::instance('resources', $res);*/
?>

@section('content')

<div class="span7 offset2">
	@yield('form_header')

	<div class="row-fluid">
	    {{ Form::label('title', 'Titre') }}
	    {{ Form::text('title') }}
	</div>
	<div class="row-fluid">
	    {{ Form::label('body', 'Text') }}
	    <div class='body_post'>
	        {{ Form::textarea('body', null, array('class' => 'redactor', 'style' => "width:100%; height:650px;")) }}
	    </div>
	</div>
	<div class="row-fluid">

		{{ Form::submit('Enregistrer') }}
	    {{ Form::label('published', 'Publication') }}
	    {{ Form::checkbox('published', '1') }}
	</div>

	{{ Form::close() }}
</div>
<div class='span2'>
	@if(isset($post) && isset($post->id))
		<a href='{{ URL::route('posts.show', $post->id) }}'>Prévisualisation</a>
	@endif
</div>
@stop
