<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NbxConfig extends Model
{
    protected $table = 'nbx_config';

	protected $fillable = ['server', 'port', 'domains', 'upload'];

    public $timestamps = false;
}
