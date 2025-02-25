<?php

namespace App;

use Eloquent as Model;

/**
 * Class Link
 * @package App
 * @version November 14, 2018, 11:24 am UTC
 *
 * @property integer user_id
 * @property string slug
 * @property string phone_number
 * @property string content
 * @property string url
 * @property integer hit
 * @property string type
 */
class Link extends Model
{

    public $table = 'links';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'user_id',
        'slug',
        'phone_number',
        'phone_code',
        'content',
        'url',
        'hit',
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'slug' => 'string',
        'phone_number' => 'string',
        'phone_code' => 'string',
        'content' => 'string',
        'url' => 'string',
        'hit' => 'integer',
        'type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // public function stat()
    // {
    //     return $this->hasMany('App\Stat', 'links_id');
    // }
    
}
