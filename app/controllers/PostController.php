<?php

class PostController extends \BaseController
{

    var $limit = 1;

    public function __construct()
    {
    }

    public function rss()
    {
        try {
            return Cache::section('posts')->rememberForever(
                'rss', function()
                {
                    $posts = Post::with('user')->take(30)->orderBy('posted_at', 'desc')->where('published', 1)->get();
                    return View::make('posts/rss')->with('posts', $posts);
                }
            );
        } catch(Exception $err) {
            $posts = Post::with('user')->take(30)->orderBy('posted_at', 'desc')->where('published', 1)->get();
            return View::make('posts/rss')->with('posts', $posts);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $posts = Post::with('user')->orderBy('posted_at', 'desc');
        if (!Auth::check()) {
            $posts = $posts->where('published', 1);
        } else {
            $posts = $posts->where('published', 1)->orWhere('author', Auth::user()->id);
        }
        $posts = $posts->take($this->limit)->get();
        $postViews = array();
        foreach ($posts as $post) {
            $view = (!Auth::check()) ? Cache::section('posts')->rememberForever(
                'view_'.$post->id, function() use($post)
                {
                    return View::make('posts/post-unique')->with('post', $post)
                                                                             ->with('display', '')
                                                                             ->render();
                }
            ) : View::make('posts/post-unique')->with('post', $post)->with('display', '')->render();
            $postViews[] = $view;
        }

        Resources::set_js('posts.js');

// var_dump($postViews);
// die();
        return View::make('posts/posts')->with('post_views', $postViews);
    }

/**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function listposts()
    {

        die();
        $posts = Post::with('user')->orderBy('posted_at', 'desc');
        if (!Auth::check()) {
            $posts = $posts->where('published');
        } else {
            $posts = $posts->where('published')->orWhere('author', Auth::user()->id);
        }
        $posts = $posts->take($this->limit)->get();
        $postViews = array();
        foreach ($posts as $post) {
            $view = (!Auth::check()) ? Cache::section('posts')->rememberForever(
                'view_'.$post->id, function() use($post)
                {
                    return View::make('posts/post-unique')->with('post', $post)
                                                                             ->with('display', '')
                                                                             ->render();
                }
            ) : View::make('posts/post-unique')->with('post', $post)->with('display', '')->render();
            $postViews[] = $view;
        }

        Resources::set_js('posts.js');

// var_dump($postViews);
// die();
        return View::make('posts/posts')->with('post_views', $postViews);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function offset($offset = 0)
    {
        $limit = $this->limit;
        $offsetFunc = '_offset';
        $postViews = (!Auth::check()) ? Cache::section('offsets')
        ->rememberForever(
            $offset, function() use($offset, $offsetFunc, $limit) {
                return $offsetFunc($offset, $limit);
            }
        ) : $offsetFunc($offset, $limit);

        return Response::json(array('count' => sizeof($postViews), 'html' => $postViews));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('posts/create')->with('post', array());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

        $postViews = array();
        $view = '';
        $post = Post::with('user')->find($id);

        if (!Auth::check()) {
            $view = Cache::section('posts')->rememberForever(
                'view_'.$id, function() use($id)
                {
                    if (!$post) {
                        return Redirect::to('posts');
                    }
                    return View::make('posts/post-unique')->with('post', $post)->with('display', '')->render();
                }
            );
        } else {
            if (!$post) {
                return Redirect::to('posts');
            }
            $view = View::make('posts/post-unique')->with('post', $post)->with('display', '')->render();
        }
        $postViews[] = $view;

        return View::make('posts/posts')->with('post_views', $postViews)->with('title', $post->title);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $post = Post::with('user')->find($id);

        if (!$post) {
            return Redirect::to('posts');
        }

        return View::make('posts/edit')->with('post', $post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $this->beforeFilter('csrf');
        $post = App::make('Post');
        $post->title = Input::get('title');
        $post->body = Input::get('body');
        $post->published = 1 && Input::get('published');

        if (Auth::check()) {
            $author = Auth::user();
        } else {
            $author = User::find(1);
        }
        $post->author = $author->id;

        if (Input::get('published')) {
            $post->posted_at = date("Y-m-d H:i:s");
        }

        $post->save();
        $this->_flush();

        return Redirect::route('posts.edit', $post->id);
    }

    /**
     * Store a newly created resource in storage called by a post action
     *
     * @return Response
     */
    public function by_network()
    {
        $path = Input::get('path');
        if ($path == 'None') {
            $path = '';
        }
        $post = Post::where('path', $path)->first();
        if (!$path || !$post) {
            $post = App::make('Post');
            if (Input::get('published')) {
                $post->posted_at = date("Y-m-d H:i:s");
            }
        }
        $post->path = $path;

        $title = Input::get('title');

        if ($title && ($title != 'None')) {
            $post->title = $title;
        }

        $post->body = Input::get('body');
        $post->published = 1 && Input::get('published');

        $post->author = Auth::user()->id;


        $post->save();
        $this->_flush();
        return 'ok';
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $this->beforeFilter('csrf');

        $post = Post::find($id);
        $post->title = Input::get('title');
        $post->body = Input::get('body');

        if (!$post->published && Input::get('published')) {
            $post->posted_at = date("Y-m-d H:i:s");
        }
        $post->published = 1 && Input::get('published');
        $post->save();
        $this->_flush();

        Cache::section('posts')->forget('view_'.$post->id);
        return Redirect::route('posts.edit', $post->id);
    }

    private function _flush()
    {
        Cache::section('posts')->flush();
        Cache::section('offsets')->flush();

        $data = "cache.appcache";
        $file = file($data);
        $i = sizeof($file) - 1; // the [x]th row you'd like to change (1 means the second row)

        $replace = '#'.time();

        $file[$i] = $replace;
        $content = implode("", $file);
        $open = fopen($data, "w");
        fwrite($open, $content);
    }

    public function flush()
    {
        $this->_flush();
        return Redirect::to('');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}



/**
 * Display a listing of the resource.
 *
 * @return Response
 */
function _offset($offset, $limit)
{
    $posts = Post::with('user')->orderBy('posted_at', 'desc');
    if (!Auth::check()) {
        $posts = $posts->where('published', 1);
    } else {
        $posts = $posts->where('published', 1)->orWhere('author', Auth::user()->id);
    }

    $posts = $posts->take($limit)->skip($offset)->get();

    $postViews = array();
    foreach ($posts as $post) {
        $view = (!Auth::check()) ? Cache::section('posts')->rememberForever(
            'view_'.$post->id, function() use($post)
            {
                return View::make('posts/post-unique')->with('post', $post)->with('display', '')->render();
            }
        ) : View::make('posts/post-unique')->with('post', $post)->with('display', '')->render();
        $postViews[] = $view;
    }

    return $postViews;
}
