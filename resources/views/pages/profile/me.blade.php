@extends('layouts.app')

@section('content')
    <div class="panel-heading">
        <h1>User's Profile</h1>
    </div>

    <div class="panel-body">
        <div class="row">
            {{ Form::model($user, [
              'route' => 'profile.update',
              'method' => 'PUT',
              'class' => 'form-horizontal',
              'files' => true,
              ]) }}

                <div class="col-md-5">
                    @include('partials.photo._photo', ['user' => $user, 'width' => 250])
                    {{ Form::file('photo') }}
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        {{ Form::label('name') }}
                        {{ Form::text('name', $user->name) }}
                    <div class="form-group">
                    </div>
                        {{ Form::label('email') }}
                        {{ Form::email('email', $user->email, ['readonly' => true]) }}
                    </div>
                    {{ Form::submit('Update', ['class' => 'btn btn-default']) }} &nbsp;&nbsp;&nbsp; {{ link_to_route('posts', 'To list') }}
                </div>
            {{ Form::close() }}
        </div>
        <div class="row">
            <ul>
                <h1>User's subscriptions:</h1>
                @foreach($user->subscriptions as $subscription)
                    <li>
                        {{ $subscription->post->title }}
                        {{ link_to_route('post.subscribe', 'Unsubscribe', $subscription->post->id) }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@stop
