{{ Form::model($answer, ['route' => $route, 'method' => $method, 'class' => 'form-horizontal']) }}
  @if ((count($errors) > 0))
    <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
    </div>
  @endif

  {{ Form::textarea('content', null, ['rows' => 2]) }}
  {{ Form::file('file') }}
  {{ Form::submit($method == 'PUT' ? 'Update' : 'Create') }}
{{ Form::close() }}
