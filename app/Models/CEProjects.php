<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 9/15/2017
 * Time: 12:45 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CEProjects extends Model
{
    use SoftDeletes;

    protected $fillable =  ['title', 'user_id', 'status', 'start_date', 'end_date'];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\CEUser');
    }

    public function client()
    {
        return $this->user->client();
    }

    public function candidates()
    {
        return $this->belongsToMany('App\Models\CECandidate', 'project_candidate', 'project_id', 'candidate_id')->withPivot('assigner_id')->withTimestamps();
    }

    public function assignedClients()
    {
        return $this->belongsToMany('App\Models\CEUser', 'project_client', 'project_id', 'user_id')->withPivot('assigner_id')->withTimestamps();
    }

    public function assignedWorkers()
    {
        return $this->belongsToMany('App\Models\CEUser', 'project_worker', 'project_id', 'user_id')->withPivot('assigner_id')->withTimestamps();
    }
}