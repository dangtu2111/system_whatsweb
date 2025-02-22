<?php

namespace App;

use Eloquent as Model;

/**
 * Class Page
 * @package App
 * @version November 27, 2018, 5:46 am UTC
 *
 * @property integer user_id
 * @property string title
 * @property string slug
 * @property string content
 * @property string type
 * @property integer show_in_menu
 * @property integer sort
 */
class Page extends Model
{

    public $table = 'posts';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'type',
        'show_in_menu',
        'sort'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'content' => 'string',
        'type' => 'string',
        'show_in_menu' => 'integer',
        'sort' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
