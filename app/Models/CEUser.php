<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 9/15/2017
 * Time: 11:55 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;
use Spatie\Permission\Traits\HasRoles;

class CEUser extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'ce_users';
    protected $fillable = [
        'name', 'email', 'password', 'provider', 'provider_id', 'activated', 'token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    public function client()
    {
        return $this->hasOne('App\Models\CEClient');
    }

    public function projects()
    {
        return $this->HasMany('App\Models\CEProjects');
    }

    public function projectsAssignedAsClient()
    {
        return $this->belongsToMany('App\Models\CEProjects','project_client', 'user_id', 'project_id')->withPivot('assigner_id')->withTimestamps();
    }

    public function projectsAssignedAsWorker()
    {
        return $this->belongsToMany('App\Models\CEProjects','project_worker', 'user_id', 'project_id')->withPivot('assigner_id')->withTimestamps();
    }
    public function tokens()
    {
        return $this->hasMany(Token::class, 'user_id')->orderBy('created_at', 'desc');
    }
}