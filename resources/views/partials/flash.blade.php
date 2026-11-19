@if (session('success'))
    <p class="notice success">{{ session('success') }}</p>
@endif

@if (session('error'))
    <p class="notice error">{{ session('error') }}</p>
@endif

@foreach ($errors->all() as $error)
    <p class="notice error">{{ $error }}</p>
@endforeach