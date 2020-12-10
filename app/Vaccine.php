<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    /**
     * @var string
     */
    protected $table = 'vaccines';

    /**
     * @var array
     */
    protected $guarded = [];
}
