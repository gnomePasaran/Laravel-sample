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
  <div>
    {{ Form::model('Answer', array('route' => ['post.answer.store', $post->id])) }}
        <h2>Create answer</h2>
        <div>
          {{ Form::textArea('content') }}
        </div>
      </div>
      {{ Form::submit() }}
    {{ Form::close()}}
  </div>
@stop
