@if($entity->attachments->count())
    @foreach($entity->attachments as $attach)
        @php
            $params = [$attach->id => $attach->file];
        @endphp
        <p>
            <img src="{{ asset($attach->getUrl()) }}" width="100px">
            <input type="hidden" name="files[{{ $attach->id }}]" value="{{ $attach->path }}">
            {{ $attach->name }}
        </p>
    @endforeach
@endif
