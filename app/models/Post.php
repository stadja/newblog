<?php

class Post extends Eloquent {

	protected $table = 'posts';

	public function user()
    {
        return $this->belongsTo('User', 'author');
    }
}
