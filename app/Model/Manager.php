<?php

namespace App\Model;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;
    use SoftDeletes;

    protected $table = 'manager';
    protected $primaryKey = 'mg_id';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'mg_role_ids', 'mg_sex', 'mg_phone', 'mg_email', 'mg_remark'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
//    protected $hidden = [
//        'password', 'remember_token',
//    ];
}
