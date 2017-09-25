<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 9/15/2017
 * Time: 12:43 PM
 */

namespace App\Models;

use Illuminate\Support\Facades\Mail;
use App\Mail\ActivateAccount;

class CEActivationService
{
    protected $mailer;

    protected $activationRepo;

    protected $resendAfter = 24;

    public function __construct(CEActivationRepository $activationRepo)
    {
        $this->activationRepo = $activationRepo;
    }

    public function sendActivationMail($user)
    {
        if ($user->activated || !$this->shouldSend($user)) {
            return;
        }

        $token = $this->activationRepo->createActivation($user);

        $link = route('user.activate', $token);

        Mail::to($user->email)->queue(new ActivateAccount($link));
    }

    public function activateUser($token)
    {
        $activation = $this->activationRepo->getActivationByToken($token);

        if ($activation === null) {
            return null;
        }

        $user = User::find($activation->user_id);

        if ($user) {
            $user->activated = true;
            $user->assignRole('client');

            $user->save();

            $this->activationRepo->deleteActivation($token);

            return $user;
        }

        abort(403);
    }

    private function shouldSend($user)
    {
        $activation = $this->activationRepo->getActivation($user);
        return $activation === null || strtotime($activation->created_at) + 60 * 60 * $this->resendAfter < time();
    }
}