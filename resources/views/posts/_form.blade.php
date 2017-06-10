@extends('app')

@section('content')
  @php
    $route = ($post->id) ? array('post.update', $post->id) : 'post.create'
  @endphp

  {{ link_to_route('posts', 'published') }}

  {{ Form::model($post, array('route' => $route, 'method' => ($post->id) ? 'PUT' : 'POST', 'class' => 'form-horizontal')) }}
    @if (count($errors) > 0)
      <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
      </div>
    @endif

    <div>
      <div>
        {{ Form::label('title', 'Title', array('class' => 'awesome')) }}
      </div>
      <div>
        {{ Form::text('title') }}
      </div>
    </div>

    <div>
      <div>
        {{ Form::label('content', 'Content', array('class' => 'awesome')) }}
      </div>
      <div>
        {{ Form::textArea('content') }}
      </div>
    </div>

    <div>
      <div>
        {{ Form::label('published', 'Published', array('class' => 'awesome')) }}
      </div>
      <div>
        {{ Form::checkbox('published', 'Published') }}
      </div>
    </div>

    {{ Form::submit('Create post') }} &nbsp;&nbsp;&nbsp; {{ link_to_route('posts', 'Cancel') }}
  {{ Form::close() }}
@stop
