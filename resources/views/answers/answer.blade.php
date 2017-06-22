<li>
  <h1><b>{{ $answer->getScore() }}&nbsp;&nbsp;&nbsp;<b>{{ $answer->content }}</b></h1>

  @if($answer->is_best)
    <strong>Best Answer!!!</strong>
  @endif

  @can('edit', $post)
    {{ link_to_route('answer.toggle_best', 'Toggle best', $answer->id) }}
  @endcan

  @can('notAthor', $answer)
    @include('votes._votes', ['entity' => $answer, 'route' => 'answer'])
  @endcan

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
