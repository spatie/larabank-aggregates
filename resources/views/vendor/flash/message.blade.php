@foreach (session('flash_notification', collect())->toArray() as $message)
    <div class="bg-red-dark text-white text-sm p-2 rounded mb-2" role="alert">
        {!! $message['message'] !!}
    </div>
@endforeach

{{ session()->forget('flash_notification') }}
