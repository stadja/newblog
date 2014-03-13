@extends('layout')

@if (isset($title) && ($title != ''))
    @section('title')
            {{$title}}
    @stop
@endif

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
        <div class="row">
        <div class="span" style='text-align: right'>
            @if (isset($next_offset) || isset($prev_offset) )
                @if ($offset !== '0')
                 <a href="/posts/list_all/{{ $prev_offset }}">&lt;-</a>
                @endif
                ||
                @if (isset($next_offset))
                    <a href="/posts/list_all/{{ $next_offset }}">-&gt;</a>
                @endif
            @else
            <a href="/posts/list_all">Voir tous les articles</a>
            @endif
        </div>
</div>


@stop
