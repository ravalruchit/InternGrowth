{{-- ============================================
     INTERNGROWTH - ALL BLADE FILES COMBINED
     This file contains all Blade templates in one place
     DO NOT USE THIS FILE IN PRODUCTION
     This is for reference/backup purposes only
     ============================================ --}}

{{-- ============================================
     FILE 1: layouts/app.blade.php
     ============================================ --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'InternGrowth') }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-wrapper">
        <nav class="navbar">
            <div class="navbar-container">
                <div class="navbar-left">
                    <div class="navbar-logo">
                        <img src="{{ asset('images/logo.png') }}" alt="InternGrowth">
                    </div>
                    <div class="navbar-links">
                        <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
                        <a href="{{ route('tasks.index') }}" class="nav-link">Tasks</a>
                        <a href="{{ route('leaderboard') }}" class="nav-link">Leaderboard</a>
                        <a href="{{ route('messages.index') }}" class="nav-link">Messages</a>
                    </div>
                </div>
                <div class="navbar-right">
                    <span class="navbar-user">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </nav>
        <main class="main-content">
            {{ $slot }}
        </main>
        <footer class="footer">
            <div class="container">
                <p>&copy; 2026 InternGrowth. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>

{{-- ============================================
     END OF FILE 1
     ============================================ --}}
