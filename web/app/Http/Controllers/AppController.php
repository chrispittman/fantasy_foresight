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
        $posts = Post::where('user_id', auth()->user()->id)
            ->orderBy('category')->orderBy('id');
        if (session()->has('category')) {
            $posts = $posts->where('category', session()->get('category'));
        }
        $posts = $posts->get();
        $categories = Post::pluck('category')->unique();
        return view('dashboard', [
            'discord_channels' => $channels,
            'posts' => $posts,
            'categories' => $categories,
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

        $post->sent = true;
        $post->save();

        return redirect('/dashboard');

    }

    public function postChooseCategory() {
        dd(request()->get('category'), session()->all());
        if (request()->get('category') === 'all') {
            session()->forget('category');
        }
        session('category', request()->get('category'));
        return redirect('/dashboard');
    }
}
