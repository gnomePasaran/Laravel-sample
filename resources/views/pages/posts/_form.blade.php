@extends('layouts.app')

@section('content')
    @php
        $route = ($post->id) ? ['post.update', $post->id] : 'post.store'
    @endphp

    {{ link_to_route('posts', 'published') }}

    {{ Form::model($post, [
        'route' => $route,
        'method' => ($post->id) ? 'PUT' : 'POST',
        'class' => 'form-horizontal',
        'files' => true
    ]) }}
        @if(count($errors) > 0)
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
                {{ Form::checkbox('published', '1', $post->published_at) }}
            </div>
        </div>
        {{ Form::file('file') }}

        {{ Form::submit('Create post') }} &nbsp;&nbsp;&nbsp; {{ link_to_route('posts', 'Cancel') }}
    {{ Form::close() }}
@stop
