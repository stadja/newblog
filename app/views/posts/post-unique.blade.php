<?php
use dflydev\markdown\MarkdownExtraParser;
$markdownParser = new MarkdownExtraParser();
?>
    <div itemprop="blogPosts" itemscope itemtype="http://schema.org/BlogPosting" class='post' style='display: {{ isset($display) ? $display : 'block'}};'>
        <div class="row">
            <div class="span9 offset1">
                <div class="row">
                    <div class="span9 left-bordered text-post float-right" style="float: right;">
                        <div class="barre"></div>
                        <div class="row-fluid">
                            <div class="span12 body_post" itemprop="articleBody">{{ $markdownParser->transformMarkdown($post->body) }}</div>
                             @if ($post->posted_at != $post->updated_at)
                            <small style='float: right;'>(modifié le <time itemprop="dateModified" dateTime="{{ date("c", strtotime($post->updated_at)) }}">{{ date("d/m/Y à H:i", strtotime($post->updated_at)) }}</time>)</small>
                            @endif<br/>
                        </div>
                    </div>

                    <div class="span3 float-right" style="margin: 0;">
                        <p class='post_date'>
                            <strong>
                                <a itemprop="url" id="post-{{ $post->id }}" href="{{ URL::route('posts.show', $post->id) }}">
                                    <span itemprop="name">{{ $post->title }}</span>
                                </a>
                            </strong><br/>
                            Le <time itemprop="datePublished" dateTime="{{ date("c", strtotime($post->posted_at)) }}">{{ date("d/m/Y à H:i", strtotime($post->posted_at)) }}</time>
                            <br/>par <b><span itemprop="author" itemscope="http://schema.org/Person"><span itemprop="name">{{ $post->user->pseudo }}</span></span></b>
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