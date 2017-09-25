<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 9/15/2017
 * Time: 12:44 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CEClient extends Model
{
    use SoftDeletes;

    protected $fillable =  ['user_id', 'first_name', 'last_name', 'company_name', 'trading_name', 'company_no', 'vat_no', 'paid_for', 'status' ];

    protected $dates = ['deleted_at'];

    public function projects()
    {
        return $this->user->projects();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\CEUser');
    }
}