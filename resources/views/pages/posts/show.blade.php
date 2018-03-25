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
        <h1>
            <b>{{ $post->title }}</b>
            @can('edit', $post)
                ({{ link_to_route('post.edit', 'Edit post', $post->id) }})
            @endcan
        </h1>
        <small>Athor: {{ $post->user->name }} | {{ $post->user->email }}</small>
        <small>Published: {{ $post->published_at }}</small>

        <div class="row">
            <div class="col-md-1">
                {{ $post->getScore() }}

                @can('notAthor', $post)
                    @include('partials.votes._votes', ['entity' => $post, 'route' => 'post'])
                @endcan
            </div>
            <div class="col-md-11">
                <p>{{ $post->content }}</p>
                @include('partials.attachments._attachments', ['entity' => $post])
            </div>
        </div>
        <ul>
            @foreach ($post->answers as $answer)
                @include('partials.answers._answer', ['answer' => $answer])
            @endforeach
        </ul>
        <div>
            @if(Auth::check())
                <h2>Create answer</h2>
                @include('partials.answers._form', [
                    'answer' => new App\Models\Answer(),
                    'route' => ['post.answer.store', $post->id],
                    'method' => 'POST'
                ])
            @endif
        </div>
    </div>
@stop
