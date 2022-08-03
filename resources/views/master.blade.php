<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web Call Center - {{$agent->organization->name}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript">var a = {{auth()->user()->authenticatable_id}};</script>
    @stack('pre-scripts')
    @stack('styles')
</head>
<body class="bg-grey-9">
@yield('content')
@stack('scripts')
</body>
</html>
