<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app.name') }}
        </h2>
    </x-slot>

    <script>
        function openIncog(url) {
            alert(url);
        }
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="post" action="/choose_category">
                        @csrf
                        Category:
                        <select name="category">
                            <option value="all">All</option>
                            @foreach ($categories as $cat)
                            <option>{{$cat}}</option>
                            @endforeach
                        </select>
                        <button type="submit">Change Category</button>
                    </form>
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
                            @foreach ($posts->groupBy(fn ($item) => $item->category ) as $category=>$cat_posts)
                                <tr><td colspan="4"><h3 class="font-bold">{{$category}}</h3></td></tr>
                                @foreach ($cat_posts as $post)
                                <tr>
                                    <td>@if ($post->sent)
                                            ✅
                                    @endif</td>
                                    @if ($post->link_url)
                                        <td><button type="button" onClick="openIncog('{{$post->link_url}}')">🔗</button></td>
                                        <td>{{$post->text}}</td>
                                        <td></td>
                                    @else
                                    <td><input type="radio" name="post_id" value="{{$post->id}}"></td>
                                    <td>{{$post->text}}</td>
                                    <td>
@if (!$post->sent)
                                       <img src="{{$post->img_url}}" height="150" width="150">
@endif
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            @endforeach
                        </table>
                        <hr>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Post to Discord</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
