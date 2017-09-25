<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 9/15/2017
 * Time: 12:45 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CEProjectsCandidates extends Model
{
    protected $table = 'project_candidate';

    protected $fillable =  ['project_id', 'candidate_id', 'assigner_id'];
}