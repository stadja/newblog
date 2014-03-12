<?php echo '<?xml version="1.0"?>'; ?>
<rss version="2.0">
    <channel>
        <title>Sérendipité (et épectase)</title>
        <link><?php echo URL::to(''); ?></link>
        <image>
            <url><?php echo URL::to(''); ?></url>
            <link><?php echo URL::to('/img/small-giraffe.png'); ?></link>
            <title>Une image</title>
        </image>
        <description>C'est un blog.</description>
        <language>fr-fr</language>
        <copyright>Copyright 2013, Ouam</copyright>
        <pubDate>{{ $posts->first()->posted_at }}</pubDate>
        @foreach($posts as $post)
        <item>
            <guid>{{ $post->id }}</guid>
            <pubDate>{{ $post->posted_at }}</pubDate>
            <title>{{ $post->title }}</title>
            <link><?php echo URL::route('posts.show', $post->id); ?></link>
            <description>{{ $post->body }}</description>
        </item>
        @endforeach
    </channel>
</rss>
