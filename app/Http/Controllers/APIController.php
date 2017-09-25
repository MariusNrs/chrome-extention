<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 9/25/2017
 * Time: 11:38 AM
 */

namespace App\Http\Controllers;

use App\Models\CECandidate;
use App\Http\Controllers\Controller;
use App\Models\CEProjects;
use App\Models\CEProjectsCandidates;
use App\Models\CEUser;
use Illuminate\Support\Facades\Auth;

class APIController extends Controller
{
    /**
     *revoke all user access tokens by user_id
     * @param $user_id
     */

    public function revokeAllTokens($user_id)
    {
        $tokens = CEUser::find($user_id)->tokens()->get();
        foreach ($tokens as $token) {
            $token->revoke();
        }
    }

    /**
     *  revoke current access token on user logout
     */

    public function revokeToken()
    {
        Auth::user()->token()->revoke();
    }

    /**
     * if user has role admin sysadmin returns all projects with candidates
     * if user is not admin, returns only user projects with candidates by user id
     *
     * @return $this
     */

    public function getProjects()
    {
        if (Auth::user()->hasRole('sysadmin') || Auth::user()->hasRole('admin'))
            return CEProjects::with('candidates')->get();
        else
            return CEProjects::where('user_id', Auth::user()->id)->with('candidates')->get();
    }

    /**
     *  requests candidates information
     */
    public function getCandidate()
    {
        $phrase = request('q');

        //TODO: When DB will have UNIQUE link, then change get to first.

        return CECandidate::where('link', $phrase)->get();
    }

    /**
     *  validates if all required fields are filled
     */
    private function validateField(&$error, $data, $field_id)
    {
        if (!isset($data[$field_id]))
            $error[] = $field_id . ' missing';
    }

    /**
     * creates new candidate
     * adds new candidate to specified project
     */

    public function createCandidate()
    {
        $data = request()->all();
        $error = [];

        $this->validateField($error, $data, 'project_id');
        $this->validateField($error, $data, 'link');
        $this->validateField($error, $data, 'full_name');

        if (!empty($error))
            return response()->json(array(
                'success' => false,
                'message' => $error
            ), 400);

        if (strpos($data['link'], 'https://www.linkedin.com/in/') !== false) {
            if (!CECandidate::where("link", $data['link'])->first()) {
                $record = CECandidate::create($data);

                $projectsData = [
                    $data['project_id'] => [
                        'assigner_id' => Auth::user()->id
                    ]
                ];

                $record->projects()->sync($projectsData);

                return $record;

            } else {
                return response()->json(array(
                    'success' => false,
                    'message' => 'Candidate already exists',
                ), 400);
            }

        } else {
            return response()->json(array(
                'success' => false,
                'message' => 'required url: https://www.linkedin.com/in/',
            ), 400);
        }
    }

    /**
     * updates candidates information
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateCandidate($id)
    {
        $data = request()->all();

        $error = [];

        $this->validateField($error, $data, 'link');
        $this->validateField($error, $data, 'full_name');

        if (!empty($error))
            return response()->json(array(
                'success' => false,
                'message' => $error
            ), 400);

        if (strpos($data['link'], 'https://www.linkedin.com/in/') !== false) {
            $record = CECandidate::find($id);
            $record->update($data);

            return $record;

        } else {
            return response()->json(array(
                'success' => false,
                'message' => 'required url: https://www.linkedin.com/in/',
            ), 400);
        }
    }

    /**
     *  adds existing candidate to a project
     */
    public function addCandidateToProject($project_id)
    {
        $candidate_id = request('candidate_id');
        $projectData = CEProjects::where('id', $project_id)->first();
        $checkData = $projectData->candidates->contains($candidate_id);

        if(!$checkData)
        {
            $projectData->candidates()->attach($candidate_id, ['assigner_id' => Auth::user()->id]);

            return response()->json(array(
                'success' => true,
                'message' => 'record added',
            ),200);
        } else
        {
            {
                return response()->json(array(
                    'success' => false,
                    'message' => 'record already exists',
                ), 400);
            }
        }
    }

    /**
     * force deletes candidate from project
     * @param $project_id
     * @param $candidate_id
     * @return \Illuminate\Http\JsonResponse
     */

    public function deleteCandidate($project_id, $candidate_id)
    {
        $record = CEProjectsCandidates::where('project_id', $project_id)->where('candidate_id', $candidate_id);
        $result = $record->get()->toArray();

        if($result == true)
        {
            $record->forceDelete();
            return response()->json(array(
                'success' => true,
                'message' => 'record deleted',
            ), 200);
        }
        else{
            return response()->json(array(
                'success' => false,
                'message' => 'record does not exist',
            ), 400);
        }
    }
}