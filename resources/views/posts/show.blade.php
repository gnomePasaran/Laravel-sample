@extends('layouts.app')

@section('content')
  <div class="panel-heading">Posts
    {{ link_to_route('posts', 'published') }}
  </div>
  <div class="panel-body">

    <article class="">
      <h2><b>{{ $post->title }}</b>
        @can('edit', $post)
          ({{ link_to_route('post.edit', 'Edit post', $post->id) }})
        @endcan
      </h2>
      <p>Published: {{ $post->published_at }}</p>
      <p>{{ $post->content }}</p>
    </article>
    <ul>
      @foreach ($post->answers as $answer)
        <li>{{ $answer->content }}</li>
      @endforeach
    </ul>
    <div>
      @if(Auth::check())
        <h2>Create answer</h2>
        {{ Form::model('Answer', array('route' => ['post.answer.store', $post->id])) }}
          <div>
            {{ Form::textArea('content') }}
          </div>
          {{ Form::submit() }}
        {{ Form::close()}}
      @endif
    </div>
  </div>
@stop
