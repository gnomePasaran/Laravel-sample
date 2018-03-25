@if($entity->attachments->count())
    @foreach($entity->attachments as $attach)
        <p>
            <img src="{{ asset($attach->getUrl()) }}" width="100px">
            {{ $attach->name }}
        </p>
    @endforeach
@endif
