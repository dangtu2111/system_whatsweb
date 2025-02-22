<?php

namespace App\Repositories;

use App\Link;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class LinkRepository
 * @package App\Repositories
 * @version November 14, 2018, 11:24 am UTC
 *
 * @method Link findWithoutFail($id, $columns = ['*'])
 * @method Link find($id, $columns = ['*'])
 * @method Link first($columns = ['*'])
*/
class LinkRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'slug',
        'phone_number',
        'content',
        'url',
        'hit',
        'type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Link::class;
    }
}
