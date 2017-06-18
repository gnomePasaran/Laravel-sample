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
          @can('edit', $answer)
            {{ Form::model($answer, ['route' => ['post.answer.update', $post->id, $answer->id], 'method' => 'PUT', 'class' => 'form-horizontal']) }}
              {{ Form::textArea('content')}}
              {{ Form::submit('Update') }}
            {{ Form::close() }}

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
