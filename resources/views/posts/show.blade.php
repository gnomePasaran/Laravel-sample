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
        <li>
          {{ $answer->content }}
          @if($answer->is_best)
            <strong>Best Answer!!!</strong>
          @endif
          @if(Auth::check())
            {{ link_to_route('answer.toggle_best', 'Toggle best', $answer->id) }}
          @endif
          @can('edit', $answer)
            @include('answers._form', [
              'answer' => $answer,
              'route' => ['post.answer.update', $post->id, $answer->id],
              'method' => 'PUT'
            ])

            {{ Form::open(['method' => 'DELETE', 'route' => ['post.answer.destroy', $post->id, $answer->id]]) }}
              {{ Form::submit('Delete') }}
            {{ Form::close() }}
          @endcan
        </li>
      @endforeach
    </ul>
    <div>
      @if(Auth::check())
        <h2>Create answer</h2>
        @include('answers._form', [
          'answer' => Answer::class,
          'route' => ['post.answer.store', $post->id],
          'method' => 'POST'
        ])
      @endif
    </div>
  </div>
@stop
