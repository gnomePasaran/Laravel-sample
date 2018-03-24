@extends('layouts.app')

@section('content')
    <div class="panel-heading">
        Posts
        {{ link_to_route('posts', 'published') }}&nbsp;&nbsp;&nbsp;
        @if(Auth::check())
            {{
                link_to_route(
                    'post.subscribe',
                    Auth::user()->isSubscribed($post) ? 'Unsubscribe' : 'Subscribe',
                    $post->id
                )
            }}
        @endif
    </div>

    <div class="panel-body">
        <article class="">
            <h2>{{ $post->getScore() }}&nbsp;&nbsp;&nbsp;<b>{{ $post->title }}</b>

                @can('edit', $post)
                    ({{ link_to_route('post.edit', 'Edit post', $post->id) }})
                @endcan

                @can('notAthor', $post)
                    @include('votes._votes', ['entity' => $post, 'route' => 'post'])
                @endcan

            </h2>
            <p>Athor: {{ $post->user->name }} | {{ $post->user->email }}</p>
            <p>Published: {{ $post->published_at }}</p>
            <p>{{ $post->content }}</p>
            @if($post->attachments()->count())
                @foreach($post->attachments as $attach)
                    <img src="{{ asset($attach->getUrl()) }}">
                @endforeach
            @endif
        </article>
        <ul>
            @foreach ($post->answers as $answer)
                @include('answers.answer', ['answer' => $answer])
            @endforeach
        </ul>
        <div>
            @if(Auth::check())
                <h2>Create answer</h2>
                @include('answers._form', [
                  'answer' => new App\Models\Answer(),
                  'route' => ['post.answer.store', $post->id],
                  'method' => 'POST'
                ])
            @endif
        </div>
    </div>
@stop
