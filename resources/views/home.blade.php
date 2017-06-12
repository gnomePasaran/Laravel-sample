@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Posts
                    {{ link_to_route('posts', 'published') }} &nbsp;&nbsp;&nbsp; {{ link_to_route('post.create', 'New post', [], ['class' => 'new-post']) }}
                </div>

                <div class="panel-body">

                    @foreach ($posts as $post)
                      <article class="">
                        <h2><b>{{ link_to_route('post.show', $post->title, $post->id) }}</b> ({{ link_to_route('post.edit', 'Edit post', $post->id) }})</h2>
                        <p><b>{{ $post->excerpt }}</b></p>
                        <p>Published: {{ $post->published_at }}</p>
                      </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
