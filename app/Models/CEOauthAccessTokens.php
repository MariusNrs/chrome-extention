<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 9/15/2017
 * Time: 12:45 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CEOauthAccessTokens extends Model
{
    protected $table = 'oauth_access_tokens';

    protected $fillable = [
        'id', 'user_id', 'client_id', 'name', 'scopes', 'revoked', 'expires_at'
    ];
}