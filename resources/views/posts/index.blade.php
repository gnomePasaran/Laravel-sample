@extends('app')


@section('content')
  {{ link_to_route('posts', 'published') }} &nbsp;&nbsp;&nbsp; {{ link_to_route('post.create', 'new_post') }}

  @foreach ($posts as $post)
    <article class="">
      <h2>{!! $post->title !!}</h2>
      <p>{!! $post->excerpt !!}</p>
      <p>Published: {{ $post->published_at }}</p>
    </article>
  @endforeach
@stop
