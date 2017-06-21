@php
  $query = $entity->votes()->where('user_id', '=', Auth::user()->id)->first()
@endphp

@if($query['score'] != 1)
  {{ Form::open(['method' => 'POST', 'route' => ["$route.vote_up", $entity->id]]) }}
    {{ Form::submit('+') }}
  {{ Form::close() }}
@endif

@if($query)
  {{ Form::open(['method' => 'POST', 'route' => ["$route.vote_cancel", $entity->id]]) }}
    {{ Form::submit('Cancel') }}
  {{ Form::close() }}
@endif

@if($query['score'] != -1)
  {{ Form::open(['method' => 'POST', 'route' => ["$route.vote_down", $entity->id]]) }}
    {{ Form::submit('-') }}
  {{ Form::close() }}
@endif
