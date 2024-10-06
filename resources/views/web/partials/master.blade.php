<!DOCTYPE html>
<html lang="en">
@include('web.partials.head')

<body>
    @include('web.layouts.header')

    @yield('content')

    @include('web.layouts.footer')
    
    @include('web.partials.scripts')

<script src="{{ asset('js/header.js') }}"></script>
<script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>