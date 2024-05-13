<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GitHubController extends Controller
{
    public function gitRedirect()
    {
        return Socialite::driver('github')
            ->scopes(['read:user', 'user:email'])
            ->redirect();
    }

    public function gitCallback()
    {
        try {
            $user = Socialite::driver('github')->user();
            $searchUser = User::where('github_id', $user->id)->first();


            if ($searchUser) {
                Auth::login($searchUser);

                $searchUser->avatar_link = $user->getAvatar();
                $searchUser->save();
            } else {
                $name = $this->findName($user);
                $gitUser = User::create([
                    'name' => $name,
                    'email' => $user->email,
                    'github_id' => $user->id,
                    'auth_type' => 'github',
                    'password' => encrypt(Str::random(32)),
                    'avatar_link' => $user->getAvatar(),
                ]);

                Auth::login($gitUser);
            }
            return redirect(route('projects.index'));

        } catch (Exception $e) {
            Log::error($e);
            abort(500);
        }
    }

    private function findName($user): string {
        if($user->name != null) {
            return $user->name;
        } else if($user->nickname != null) {
            return $user->nickname;
        } else {
            return $user->email;
        }
    }
}
