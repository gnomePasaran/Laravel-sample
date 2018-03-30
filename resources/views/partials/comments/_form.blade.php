{{ Form::model($comment, [
    'route' => $route,
    'method' => $method,
    'class' => 'form-horizontal',
    'files' => true,
]) }}

    @if((count($errors) > 0))
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{ Form::textarea('content', null, ['rows' => 1]) }}

    {{ Form::submit($method == 'PUT' ? 'Update' : 'Create') }}
{{ Form::close() }}
