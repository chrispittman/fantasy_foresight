<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="post" action="/post">
                        @csrf
                        Channel:
                        <select name="discord_channel_id">
                            @foreach ($discord_channels as $ch)
                            <option value="{{$ch->id}}">{{$ch->name}}</option>
                            @endforeach
                        </select>
                        <hr>
                        <table border="1">
                            @foreach ($posts as $post)
                                <tr>
                                    <td><input type="radio" name="post_id" value="{{$post->id}}"></td>
                                    <td>{{$post->text}}</td>
                                    <td>{{$post->image_url}}</td>
                                </tr>
                            @endforeach
                        </table>
                        <hr>
                        <button type="submit">Post to Discord</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
