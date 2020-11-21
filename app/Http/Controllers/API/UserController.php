<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Command\Exception\CommandException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use LaravelRestcord\Discord;
use RestCord\DiscordClient;
use RestCord\Model\User\User;

class UserController extends Controller
{
    public function getByAvatarByUid(Request $r)
    {
        $v = validator($r->all(['uid']), [
            'uid' => 'required|string'
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 403);
        }
        $discord = new DiscordClient([
            'token' => config('discord.secret')
        ]);
        if (Cache::has('discord-user:avatar:' . $r->query('uid'))) {
            return response()->json(Cache::get('discord-user:avatar:' . $r->query('uid')));
        }
        try {
            $user = $discord->user->getUser([
                'user.id' => intval($r->query('uid'))
            ]);
        } catch (CommandException $cex) {
            if ($cex->getResponse()->getStatusCode() == 404) {
                $v->errors()->add('uid', 'File or User does not exist');
                return response()->json([
                    'errors' => $v->errors()
                ], 403);
            }
            $v->errors()->add('server', 'Something went wrong');
            return response()->json([
                'errors' => $v->errors()
            ], 403);
        }
        $avatarUrl = $user->getAvatar();
        $avatarInfo = pathinfo($avatarUrl);
        $mimey = new \Mimey\MimeTypes();
        $data = [
            'url' => $avatarUrl,
            'info' => array_merge($avatarInfo, ['mime' => $mimey->getMimeType($avatarInfo['extension'])])
        ];
        Cache::put('discord-user:avatar:' . $user->id, $data, now()->addMinutes(1));
        return response()->json($data);
    }
}
