@if(null !== ($headline ?? null))
{{ $headline }}
------------------------------
@endif
@foreach($buttons as $button)
{!! $button['slot'] !!}: {{ $button['url'] }}
@endforeach