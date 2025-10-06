<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DailyDrive - Your Goals & Tasks Dashboard')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    @vite(['resources/css/custom.css', 'resources/css/Gemini.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <!-- Include Header -->
    @include('partials.header')

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Floating Chatbot Widget (Stage 8 - Gemini AI) -->
    @include('partials.chat-widget')
    
    <!-- Footer -->
<footer class="bg-light text-center py-3 mt-5">
    <div class="container">
        <p class="text-muted mb-0">&copy; {{ date('Y') }} DailyDrive. Stay productive, stay driven.</p>
    </div>
</footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    @stack('scripts')


</body>

</html>