<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Exam Project - Timedoor</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="antialiased">
    <nav>
        <ul class="container">
            <li>
                <a href="{{ route('home') }}">Home</a>
            </li>
            <li>
                <a href="{{ route('top-rating') }}">Top Rating Author</a>
            </li>
            <li>
                <a href="{{ route('rating.create') }}">Add Rating</a>
            </li>
        </ul>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    @yield('script')
</body>

</html>