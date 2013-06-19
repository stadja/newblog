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
App::singleton('resources', function() {
	return array('js' => array(), 'css_before' => array(), 'css_after' => array(), 'js_fx' => array(), 'css_fx' => array());
});

View::composer('layout', function($view) {
	$view->with('resources', App::make('resources'));
});

Route::filter('auth_post', function() {
	if (!Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password')))) {
		return 'false';
	}
});
Route::resource('posts', 'PostController');

Route::group(array('prefix' => 'posts'), function()
{
	Route::get('create', array('before' => 'auth', 'uses' => 'PostController@create'));
	Route::post('/', array('before' => 'auth', 'uses' => 'PostController@store'));
	Route::get('{id}/edit', array('before' => 'auth', 'uses' => 'PostController@edit'));
	Route::put('{id}/edit', array('before' => 'auth', 'uses' => 'PostController@update'));
	Route::any('by_network', array('before' => 'auth_post', 'uses' => 'PostController@by_network'));
	Route::get('offset/{offset}', 'PostController@offset' );
});

Route::get('flush', 'PostController@flush' );
Route::get('rss.xml', 'PostController@rss' );
Route::get('/', 'PostController@index');


Route::group(array('prefix' => 'auth'), function()
{
	Route::post('logout', function() {
		return Auth::logout();
	});

	Route::post('login', function() {

		if (!Input::get('assertion')) {
			if (!Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password')))) {
				return 'false';
			}
			return 'true';
		}

		$postFields = array('assertion' => Input::get('assertion'),
							'audience' => URL::to('').':80');

		$options=array(
			CURLOPT_URL            => "https://verifier.login.persona.org/verify",       // Url cible (l'url de la page que vous voulez télécharger)
			CURLOPT_RETURNTRANSFER => true,       // Retourner le contenu téléchargé dans une chaine (au lieu de l'afficher directement)
			CURLOPT_HEADER         => false,      // Ne pas inclure l'entête de réponse du serveur dans la chaine retournée
			CURLOPT_FAILONERROR    => true,       // Gestion des codes d'erreur HTTP supérieurs ou égaux à 400
			CURLOPT_POST           => true,       // Effectuer une requête de type POST
			CURLOPT_POSTFIELDS     => http_build_query($postFields) // Le tableau associatif contenant les variables envoyées par POST au serveur
		);

		$CURL=curl_init();
		// Erreur suffisante pour justifier un die()
		if(empty($CURL)){
			die("ERREUR curl_init : Il semble que cURL ne soit pas disponible.");
		}
		// Configuration des options de téléchargement
		curl_setopt_array($CURL,$options);
		$content=curl_exec($CURL);  
		curl_close($CURL);

		if($content == 'false'){
			var_dump(curl_error($CURL));
			die("ERREUR curl.");
		}	

		$content = json_decode($content);
		if (isset($content->status) && ($content->status == "okay")) {
			$user = User::where('email', $content->email)->first();
			Auth::login($user);
		}

		return $content->status;
	});

});


Route::bind('user', function($value, $route)
{
	return User::where('name', $value)->first();
});

Route::group(array('prefix' => '{user}'), function()
{
	Route::get('password', function($user)
	{	
		$credentials = array('email' => $user->email);
		Password::remind($credentials);
		return Redirect::to('');
	});

	Route::get('password/reset/{token}', function($user, $token)
	{
		return View::make('reset-password')->with('token', $token)->with('user', $user);
	});

	Route::post('password/reset/{token}', function($user, $token)
	{
		$credentials = array('email' => $user->email);

		return Password::reset($credentials, function($user, $password)
		{
			$user->password = Hash::make($password);
			$user->save();
			return Redirect::to('');
		});
	});
});


Route::any('test', function() {
	var_dump('test');
	die();
});


// Route::model('post', 'Posts');
// Route::model('user', 'User');

// Route::group(array('prefix' => 'admin'), function()
// {

// 	Route::get('user', function()
// 	{
// 		return 'admin user';
// 	});

// 	Route::get('test', function()
// 	{
// 		return 'admin test';
// 	});

// });



// Route::get('profile/{user}', function(User $user)
// {
// 	var_dump($user); die();
// });

// Route::get('users', function()
// {
// 	$users = User::all();
// 	return View::make('users')->with('users', $users);
// });

// Route::get('test', function()
// {
// 	return URL::to('test');
// });


// Route::get('log/{pseudo}/{password}', function($pseudo, $password)
// {
// 	if (Auth::attempt(array('pseudo' => $pseudo, 'password' => $password)))
// 	{
// 		header('Location: http://'.$pseudo.'.newblog.stadja.net');
// 		exit();
// 	} else {
// 		return 'FAUX';
// 	}

// });

// Route::group(array('domain' => '{user}.newblog.stadja.net'), function()
// {
// 	Route::get('', function($user)
// 	{
// 		if (Auth::check() && (Auth::user() == $user))
// 		{
// 			var_dump(Auth::user());
// 			Auth::logout();
// 		}

// 		return 'Profil de '.$user->pseudo;

// 	});
