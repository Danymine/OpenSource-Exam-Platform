@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'mt-2 alert alert-danger']) }} role="alert">
        @foreach ((array) $messages as $message)
            {{ $message }}
        @endforeach
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif