@php
    $flashData = [
        'success' => session('success'),
        'error' => session('error'),
        'errors' => $errors->any() ? $errors->all() : null
    ];
@endphp

@if(session('success') || session('error') || $errors->any())
    <script>
        window.flashData = @json($flashData);
    </script>
@endif