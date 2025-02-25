<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
	protected $table = 'stats';
	protected $fillable = [
		'users_id', 
		'links_id', 
		'ip', 
		'meta',
		'user_agent',
		'referer',
		'device',
		'device_name',
		'browser',
		'browser_version',
		'platform',
		'platform_version',
	];

	// public function link()
	// {
	// 	return $this->belongsTo('App\Link', 'links_id');
	// }
}
