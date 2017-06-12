@extends('layouts.app')

@section('content')

                <div class="panel-heading">Posts
                    {{ link_to_route('posts', 'published') }} &nbsp;&nbsp;&nbsp;
                    @can('new', $posts->first())
                        {{ link_to_route('post.create', 'New post', [], ['class' => 'new-post']) }}
                    @endcan
                </div>

                <div class="panel-body">

                    @foreach ($posts as $post)
                      <article class="">
                        <h2><b>{{ link_to_route('post.show', $post->title, $post->id) }}</b>
                          @can('edit', $posts->first())
                              ({{ link_to_route('post.edit', 'Edit post', $post->id) }})
                          @endcan
                        </h2>
                        <p><b>{{ $post->excerpt }}</b></p>
                        <p>Published: {{ $post->published_at }}</p>
                      </article>
                    @endforeach
                </div>

@endsection
