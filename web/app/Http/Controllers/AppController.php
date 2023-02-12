<?php

namespace App\Http\Controllers;

use App\Models\DiscordChannel;
use App\Models\Post;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AppController extends Controller
{
    public function getDashboard() {
        $channels = DiscordChannel::where('user_id', auth()->user()->id)->get();
        $posts = Post::where('user_id', auth()->user()->id)->get();
        return view('dashboard', [
            'discord_channels' => $channels,
            'posts' => $posts,
        ]);
    }

    public function post() {
        $channel = DiscordChannel::where('user_id', auth()->user()->id)
            ->findOrFail(request('discord_channel_id'));
        $post = Post::where('user_id', auth()->user()->id)
            ->findOrFail(request('post_id'));

        $data = [
            "content" => $post->text,
            ];
        if ($post->img_url) {
            $data['embeds'] = [
                ['image'=>['url'=>$post->img_url]]
            ];
        }

        $client = new Client();

        $response = $client->post($channel->webhook_url, [
            \GuzzleHttp\RequestOptions::JSON => $data
        ]);

        return redirect('/dashboard');

    }
}
