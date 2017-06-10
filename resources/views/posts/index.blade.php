@extends('app')

@section('content')
  {{ link_to_route('posts', 'published') }} &nbsp;&nbsp;&nbsp; {{ link_to_route('post.create', 'New post') }}

  @foreach ($posts as $post)
    <article class="">
      <h2><b>{{ link_to_route('post.show', $post->title, $post->id) }}</b> ({{ link_to_route('post.edit', 'Edit post', $post->id) }})</h2>
      <p><b>{{ $post->excerpt }}</b></p>
      <p>Published: {{ $post->published_at }}</p>
    </article>
  @endforeach
@stop
