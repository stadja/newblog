<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
App::singleton(
    'resources', function()
    {
        return array(
                'js' => array()
                , 'js_fx' => array()
                , 'css_before' => array()
                , 'css_after' => array()
                , 'css_fx' => array()
            );
    }
);

View::composer(
    'layout', function($view) {
        $view->with('resources', App::make('resources'));
    }
);

Route::filter(
    'auth_post', function() {
        if (!Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password')))) {
            return 'false';
        }
    }
);

Route::group(
    array('prefix' => 'posts'), function()
    {
        Route::get('list_all', 'PostController@listAll');
        Route::get('list_all/{offset}', 'PostController@listAll');
        Route::get('create', array('before' => 'auth', 'uses' => 'PostController@create'));
        Route::get('offset/{offset}', 'PostController@offset');
        Route::any('by_network', array('before' => 'auth_post', 'uses' => 'PostController@by_network'));
    }
);
Route::resource('posts', 'PostController');

Route::get(
    'last', function() {
        $post = Post::orderBy('posted_at', 'desc')->where('published', 1)->first();
        return Redirect::route('posts.show', $post->id);
    }
);


Route::get('flush', 'PostController@flush');
Route::get('rss.xml', 'PostController@rss');
Route::get('/', 'PostController@index');



Route::group(
    array('prefix' => 'auth'), function()
    {
    Route::post(
        'logout', function() {
            return Auth::logout();
        }
    );

    Route::post(
        'login', function()
        {

            if (!Input::get('assertion')) {
                if (!Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password')))) {
                    return 'false';
                }
                return 'true';
            }

            $postFields = array('assertion' => Input::get('assertion'),
                'audience' => URL::to('').':80');

            $options=array(
                // Url cible (l'url de la page que vous voulez télécharger)
                CURLOPT_URL            => "https://verifier.login.persona.org/verify",
                // Retourner le contenu téléchargé dans une chaine (au lieu de l'afficher directement)
                CURLOPT_RETURNTRANSFER => true,
                // Ne pas inclure l'entête de réponse du serveur dans la chaine retournée
                CURLOPT_HEADER         => false,
                 // Gestion des codes d'erreur HTTP supérieurs ou égaux à 400
                CURLOPT_FAILONERROR    => true,
                CURLOPT_POST           => true,       // Effectuer une requête de type POST
                // Le tableau associatif contenant les variables envoyées par POST au serveur
                CURLOPT_POSTFIELDS     => http_build_query($postFields)
                );

            $curl=curl_init();
            // Erreur suffisante pour justifier un die()
            if (empty($curl)) {
                die("ERREUR curl_init : Il semble que cURL ne soit pas disponible.");
            }
            // Configuration des options de téléchargement
            curl_setopt_array($curl, $options);
            $content=curl_exec($curl);
            curl_close($curl);

            if ($content == 'false') {
                var_dump(curl_error($curl));
                die("ERREUR curl.");
            }

            $content = json_decode($content);
            if (isset($content->status) && ($content->status == "okay")) {
                $user = User::where('email', $content->email)->first();
                Auth::login($user);
            }

            return $content->status;
        }
    );
    }
);


Route::bind(
    'user', function($value, $route)
    {
        return User::where('name', $value)->first();
    }
);

Route::group(
    array('prefix' => '{user}'), function()
    {
        Route::get(
            'password', function($user)
            {
                $credentials = array('email' => $user->email);
                Password::remind($credentials);
                return Redirect::to('');
            }
        );

        Route::get(
            'password/reset/{token}', function($user, $token)
            {
                return View::make('reset-password')->with('token', $token)->with('user', $user);
            }
        );

        Route::post(
            'password/reset/{token}', function($user, $token)
            {
                $credentials = array('email' => $user->email);

                return Password::reset(
                    $credentials, function($user, $password)
                    {
                        $user->password = Hash::make($password);
                        $user->save();
                        return Redirect::to('');
                    }
                );
            }
        );
    }
);


Route::any(
    'test', function() {
        var_dump('test');
        die();
    }
);


// Route::model('post', 'Posts');
// Route::model('user', 'User');

// Route::group(array('prefix' => 'admin'), function()
// {

//  Route::get('user', function()
//  {
//      return 'admin user';
//  });

//  Route::get('test', function()
//  {
//      return 'admin test';
//  });

// });



// Route::get('profile/{user}', function(User $user)
// {
//  var_dump($user); die();
// });

// Route::get('users', function()
// {
//  $users = User::all();
//  return View::make('users')->with('users', $users);
// });

// Route::get('test', function()
// {
//  return URL::to('test');
// });


// Route::get('log/{pseudo}/{password}', function($pseudo, $password)
// {
//  if (Auth::attempt(array('pseudo' => $pseudo, 'password' => $password)))
//  {
//      header('Location: http://'.$pseudo.'.newblog.stadja.net');
//      exit();
//  } else {
//      return 'FAUX';
//  }

// });

// Route::group(array('domain' => '{user}.newblog.stadja.net'), function()
// {
//  Route::get('', function($user)
//  {
//      if (Auth::check() && (Auth::user() == $user))
//      {
//          var_dump(Auth::user());
//          Auth::logout();
//      }

//      return 'Profil de '.$user->pseudo;

//  });
