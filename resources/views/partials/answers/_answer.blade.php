<li>
    <div class="row">
        <div class="col-md-2">
            @if($answer->is_best)
                <i class="btn btn-xs btn-primary">Best Answer!!!</i>
            @endif

            @can('edit', $post)
                {{ link_to_route('answer.toggle_best', 'Toggle best', $answer->id) }}
            @endcan

            {{ $answer->getScore() }}

            @can('notAthor', $answer)
                @include('partials.votes._votes', ['entity' => $answer, 'route' => 'answer'])
            @endcan
        </div>

        <div class="col-md-10">
            <small>
                Answered:
                @include('partials.photo._photo', ['user' => $post->user])
                {{ $answer->user->name }} | {{ $answer->user->email }}
            </small>
            <small>Published: {{ $post->published_at }}</small>
            <p>{{ $answer->content }}</p>

            @include('partials.attachments._attachments', ['entity' => $answer])

            <div>
                @can('edit', $answer)
                    @include('partials.answers._form', [
                        'answer' => $answer,
                        'route' => ['post.answer.update', $post->id, $answer->id],
                        'method' => 'PUT'
                    ])

                    {{ Form::open(['method' => 'DELETE', 'route' => ['post.answer.destroy', $post->id, $answer->id]]) }}
                        {{ Form::submit('Delete') }}
                    {{ Form::close() }}
                @endcan
            </div>
            <hr>
        </div>
    </div>


</li>
