<div class="btn-group">
    {{ $comment->content }}
    @can('destroy', $comment)
        {{ Form::open(['method' => 'DELETE', 'route' => ['comment.destroy', $comment]]) }}
            {{ Form::submit('Delete', ['class' => 'btn btn-xs btn-danger ']) }}
        {{ Form::close() }}
    @endcan
    @if(Auth::check())
        <!-- <h3>Update comment</h3> -->
        @include('partials.comments._form', [
            'comment' => $comment,
            'route' => ['comment.update', $comment],
            'method' => 'PUT'
        ])
    @endif
</div>
