@extends('layout')

@section('content')

<div class="row hidden-phone">
    <div class="span3" style="margin: 0;"></div>
    <div class="span9" style="margin: 0;">
        <div class='top-cross'></div>
    </div>
</div>

<div id='posts_infinite' value='{{ sizeof($post_views) }}'>
    @foreach($post_views as $post_view)
    {{ $post_view }}
    @endforeach
</div>
<div id="bottom"></div>
<div class="row ajax-loader" style='display: none;'>
    <div class="span9 offset1">
        <div class="row">
            <div class="span9 left-bordered" style="float: right;">
                <img src='/img/ajax-loader.gif' />
            </div>
        </div>
    </div>
</div>
<div class="row hidden-phone">
    <div class="span3" style="margin: 0;"></div>
    <div class="span9" style="margin: 0;">
        <div class='bottom-fleche'></div>
    </div>
</div>


@stop
