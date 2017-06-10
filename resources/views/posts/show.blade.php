@extends('app')

@section('content')
  {{ link_to_route('posts', 'published') }}

  <article class="">
    <h2><b>{{ $post->title }}</b> ({{ link_to_route('post.edit', 'Edit post', $post->id) }})</h2>
    <p>Published: {{ $post->published_at }}</p>
    <p>{{ $post->content }}</p>
  </article>
  <ul>
    @foreach ($post->answers as $answer)
      <li>{{ $answer->content }}</li>
    @endforeach
  </ul>
@stop
