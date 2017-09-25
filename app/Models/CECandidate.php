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

class CECandidate extends Model
{
    use SoftDeletes;

    protected $fillable =  ['full_name', 'link', 'status', 'company_name', 'position', 'contact_details', 'client_comment', 'tr_client_comment', 'our_comment', 'added_by', 'source'];

    protected $dates = ['deleted_at'];

    public function projects()
    {
        return $this->belongsToMany('App\Models\Projects', 'project_candidate', 'candidate_id', 'project_id')->withPivot('assigner_id')->withTimestamps();
    }

    public function scopeInterested( $query ) {
        return $query->where('candidates.status', '=', 3);
    }
}