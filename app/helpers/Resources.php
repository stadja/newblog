<?php

class Resources {

    static function set_js_fx($js_fx = FALSE) {
		$res = App::make('resources');
		$res['js_fx'][] = $js_fx;
		$res = App::instance('resources', $res);
		return App::make('resources');
	}

    static function set_js($js) {
		$res = App::make('resources');
		$res['js'][] = $js;
		$res = App::instance('resources', $res);
		return App::make('resources');
	}

}