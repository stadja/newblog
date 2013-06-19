<?php
use dflydev\markdown\MarkdownExtraParser;
$markdownParser = new MarkdownExtraParser();
?>
    <div class='post' style='display: {{ isset($display) ? $display : 'block'}};'>
        <div class="row">
            <div class="span9 offset1">
                <div class="row">
                    <div class="span9 left-bordered text-post float-right" style="float: right;">
                        <div class="row-fluid">
                            <div class="span12 body_post">{{ $markdownParser->transformMarkdown($post->body) }}</div>
                             @if ($post->posted_at != $post->updated_at)
                            <small style='float: right;'>(modifié le {{ date("d/m/Y à H:i", strtotime($post->updated_at)) }})</small>
                            @endif<br/> 
                        </div>
                    </div>

                    <div class="span3 float-right" style="margin: 0;">
                        <p>
                            <strong>
                                <a id="post-{{ $post->id }}" href="{{ URL::route('posts.show', $post->id) }}">
                                    {{ $post->title }}
                                </a>
                            </strong><br/>
                            Le {{ date("d/m/Y à H:i", strtotime($post->posted_at)) }}
                            par <b>{{ $post->user->pseudo }}</b>
                        </p>
                    </div>
                </div>
            </div>

            <div class="span2 float-left">
                <div class="row-fluid">
                    <div class="span11">
                        <a href='#'>!</a>
                    </div>
                    <div class="span1">
                        @if (Auth::check() && (Auth::user()->id == $post->user->id))
                        <a href='{{ URL::route('posts.edit', $post->id) }}'>
                            E
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>