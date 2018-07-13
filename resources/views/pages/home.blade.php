@extends('layouts.app')

@section('content')
    <div class="panel-heading">Posts
        {{ link_to_route('posts', 'published') }} &nbsp;&nbsp;&nbsp;
        @if(Auth::check())
            {{ link_to_route('post.create', 'New post', [], ['class' => 'new-post']) }}
        @endif
    </div>

    <div class="panel-body">
        @foreach ($posts as $post)
            <article class="">
                <h2><b>{{ link_to_route('post.show', $post->title, ['post' => $post->slug]) }}</b>
                    @can('edit', $post)
                        ({{ link_to_route('post.edit', 'Edit post', ['post' => $post->slug], 'class="edit-post"') }})
                        <span class="btn-group">
                            {{ Form::open(['method' => 'DELETE', 'route' => ['post.destroy', $post->id]]) }}
                                {{ Form::submit('Delete', ['class' => 'btn btn-xs btn-danger delete-post']) }}
                            {{ Form::close() }}
                        </span>
                    @endcan
                </h2>
                <p><b>{{ $post->excerpt }}</b></p>
                <p>Athor: {{ $post->user->name }} | {{ $post->user->email }}</p>
                <p>Published: {{ $post->published_at }}</p>
            </article>
        @endforeach
        {{ $posts->links() }}
    </div>
@endsection
