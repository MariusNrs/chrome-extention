<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 9/15/2017
 * Time: 12:44 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CEAssignedProjects extends Model
{
    protected $table = 'projects_assignments';

    protected $fillable = ['user_id', 'project_id', 'assigner_id'];

    public function projects()
    {
        return $this->belongsTo('App\Models\CEProjects', 'project_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\CEUser', 'user_id');
    }

    public function assigner()
    {
        return $this->belongsTo('App\Models\CEUser', 'assigner_id');
    }

    public function getAssignedProjects($userId)
    {
        $projects = self::where('user_id', $userId)->pluck('project_id')->toArray();

        return $projects;
    }
}