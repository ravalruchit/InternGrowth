<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'InternGrowth')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        secondary: '#8b5cf6',
                    },
                    animation: {
                        'gradient': 'gradient 8s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                        'slide-down': 'slideDown 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'scale-in': 'scaleIn 0.4s ease-out',
                        'shimmer': 'shimmer 2s linear infinite',
                    },
                    keyframes: {
                        gradient: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        slideDown: {
                            '0%': { transform: 'translateY(-100%)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(100%)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.9)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-1000px 0' },
                            '100%': { backgroundPosition: '1000px 0' },
                        },
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&display=swap');
        
        body { 
            font-family: 'Inter', sans-serif;
        }
        
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Animated gradient background */
        .bg-animated-gradient {
            background: linear-gradient(-45deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #667eea 100%);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        /* Glass morphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Hover glow effect */
        .hover-glow {
            transition: all 0.3s ease;
        }
        
        .hover-glow:hover {
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.5);
            transform: translateY(-2px);
        }
        
        /* Shimmer effect */
        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        
        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        /* Particle background */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        
        .particle {
            position: absolute;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #764ba2 0%, #667eea 100%);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen relative overflow-x-hidden">
    
    <!-- Animated Background Particles -->
    <div class="particles">
        <div class="particle" style="width: 80px; height: 80px; left: 10%; top: 20%; animation-delay: 0s;"></div>
        <div class="particle" style="width: 60px; height: 60px; left: 80%; top: 30%; animation-delay: 2s;"></div>
        <div class="particle" style="width: 100px; height: 100px; left: 50%; top: 60%; animation-delay: 4s;"></div>
        <div class="particle" style="width: 70px; height: 70px; left: 20%; top: 80%; animation-delay: 1s;"></div>
        <div class="particle" style="width: 90px; height: 90px; left: 70%; top: 70%; animation-delay: 3s;"></div>
    </div>

    <div class="min-h-screen relative z-10">
        <!-- Modern Animated Navigation -->
        <nav class="bg-white/80 backdrop-blur-lg shadow-xl border-b border-purple-100 sticky top-0 z-50 animate-slide-down">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <!-- Logo & Links -->
                    <div class="flex items-center space-x-8">
                        <!-- Animated Logo -->
                        <div class="flex items-center group">
                            <div class="relative">
                                <!-- Animated glow effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl blur-xl opacity-40 group-hover:opacity-70 transition-opacity duration-300"></div>
                                
                                <!-- Logo without background -->
                                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="InternGrowth" class="h-14 w-auto relative z-10 transform group-hover:scale-110 transition-all duration-300 drop-shadow-2xl">
                            </div>
                            
                            <!-- Brand name next to logo -->
                            <span class="ml-3 text-2xl font-black font-poppins bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent group-hover:scale-105 transition-transform duration-300">
                                InternGrowth
                            </span>
                        </div>
                        
                        <!-- Navigation Links with Hover Effects -->
                        <div class="hidden md:flex space-x-2">
                            <a href="<?php echo e(route('dashboard')); ?>" class="group relative px-4 py-2 rounded-xl text-gray-700 hover:text-white font-medium transition-all duration-300 overflow-hidden">
                                <span class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left rounded-xl"></span>
                                <span class="relative flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    <span>Home</span>
                                </span>
                            </a>
                            
                            <a href="<?php echo e(route('tasks.index')); ?>" class="group relative px-4 py-2 rounded-xl text-gray-700 hover:text-white font-medium transition-all duration-300 overflow-hidden">
                                <span class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left rounded-xl"></span>
                                <span class="relative flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span>Tasks</span>
                                </span>
                            </a>
                            
                            <a href="<?php echo e(route('leaderboard')); ?>" class="group relative px-4 py-2 rounded-xl text-gray-700 hover:text-white font-medium transition-all duration-300 overflow-hidden">
                                <span class="absolute inset-0 bg-gradient-to-r from-pink-600 to-orange-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left rounded-xl"></span>
                                <span class="relative flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    <span>Leaderboard</span>
                                </span>
                            </a>
                            
                            <?php if(auth()->user()->isStudent()): ?>
                                <a href="<?php echo e(route('student.analytics')); ?>" class="group relative px-4 py-2 rounded-xl text-gray-700 hover:text-white font-medium transition-all duration-300 overflow-hidden">
                                    <span class="absolute inset-0 bg-gradient-to-r from-orange-600 to-red-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left rounded-xl"></span>
                                    <span class="relative flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        <span>Analytics</span>
                                    </span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- User Info & Logout -->
                    <div class="flex items-center space-x-4">
                    <!-- Notification Bell -->
                    <?php if(auth()->guard()->check()): ?>
                    <div class="relative" id="notif-wrapper">
                        <button id="notif-btn" onclick="toggleNotifDropdown()"
                            class="relative p-2 rounded-full bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-all duration-200 focus:outline-none">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <!-- Badge -->
                            <span id="notif-badge"
                                  class="absolute -top-1 -right-1 hidden w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center shadow">
                                0
                            </span>
                        </button>

                        <!-- Dropdown -->
                        <div id="notif-dropdown"
                             class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-purple-100 z-50 overflow-hidden">
                            <!-- Header -->
                            <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-100">
                                <span class="font-semibold text-gray-800 text-sm">Notifications</span>
                                <button onclick="markAllRead()" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Mark all read</button>
                            </div>

                            <!-- List -->
                            <div id="notif-list" class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                                <div class="px-4 py-6 text-center text-gray-400 text-sm" id="notif-empty">
                                    No notifications
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50 text-center">
                                <a href="<?php echo e(route('notifications.index')); ?>"
                                   class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    View all notifications →
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- User Dropdown Menu -->
                        <div class="relative group">
                            <button class="hidden sm:flex items-center space-x-3 bg-gradient-to-r from-indigo-50 to-purple-50 px-4 py-2 rounded-full border border-purple-200 hover:shadow-lg transition-all duration-300 cursor-pointer">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-sm text-gray-700 font-semibold"><?php echo e(auth()->user()->name); ?></span>
                                <svg class="w-4 h-4 text-gray-600 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-purple-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right scale-95 group-hover:scale-100 z-50">
                                <div class="py-2">
                                    <?php if(auth()->user()->role === 'student'): ?>
                                        <a href="<?php echo e(route('student.profile')); ?>" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-all duration-200">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="font-medium">Edit Profile</span>
                                        </a>
                                    <?php elseif(auth()->user()->role === 'startup'): ?>
                                        <a href="<?php echo e(route('startup.profile')); ?>" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-all duration-200">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            <span class="font-medium">Edit Profile</span>
                                        </a>
                                    <?php endif; ?>
                                    <div class="border-t border-gray-100 my-2"></div>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-red-600 hover:bg-red-50 transition-all duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            <span class="font-medium">Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Animated Flash Messages -->
        <?php if(session('success')): ?>
            <div id="success-notification" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 animate-slide-down">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-2xl shadow-xl flex items-center justify-between transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center animate-bounce">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="font-semibold text-lg"><?php echo e(session('success')); ?></p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800 transform hover:rotate-90 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('message_sent')): ?>
            <div id="message-sent-notification" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 animate-slide-down">
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500 text-blue-800 px-6 py-4 rounded-2xl shadow-xl flex items-center justify-between transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center animate-pulse">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="font-semibold text-lg"><?php echo e(session('message_sent')); ?></p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-blue-600 hover:text-blue-800 transform hover:rotate-90 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('task_created')): ?>
            <div id="task-created-notification" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 animate-slide-down">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-l-4 border-purple-500 text-purple-800 px-6 py-4 rounded-2xl shadow-xl flex items-center justify-between transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center animate-bounce">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="font-semibold text-lg"><?php echo e(session('task_created')); ?></p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-purple-600 hover:text-purple-800 transform hover:rotate-90 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div id="error-notification" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 animate-slide-down">
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-2xl shadow-xl flex items-center justify-between transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center animate-pulse">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="font-semibold text-lg"><?php echo e(session('error')); ?></p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800 transform hover:rotate-90 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Content with Animation -->
        <main class="py-12 animate-fade-in">
            <?php echo e($slot); ?>

        </main>

        <!-- Modern Animated Footer -->
        <footer class="bg-white/80 backdrop-blur-lg border-t border-purple-100 mt-20 relative overflow-hidden">
            <!-- Animated gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/5 via-purple-500/5 to-pink-500/5 animate-gradient bg-[length:200%_200%]"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <!-- About Section with Animation -->
                    <div class="space-y-4 animate-slide-up" style="animation-delay: 0.1s;">
                        <div class="flex items-center space-x-3 group">
                            <div class="relative">
                                <!-- Glow effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-xl blur-lg opacity-30 group-hover:opacity-50 transition-opacity duration-300"></div>
                                
                                <!-- Logo without background -->
                                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="InternGrowth" class="h-12 w-auto relative z-10 transform group-hover:scale-110 transition-all duration-300 drop-shadow-lg">
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">InternGrowth</span>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Connecting talented students with innovative startups for meaningful internship experiences. Build your future today! 
                        </p>
                        <!-- Social Icons -->
                        <div class="flex space-x-3 pt-2">
                        
                            <a href="#" class="w-10 h-10 bg-gradient-to-r from-pink-500 to-rose-500 rounded-full flex items-center justify-center text-white hover:shadow-lg transform hover:scale-110 transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links with Hover Animation -->
                    <div class="animate-slide-up" style="animation-delay: 0.2s;">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center space-x-2">
                            <span class="w-1 h-6 bg-gradient-to-b from-indigo-600 to-purple-600 rounded-full"></span>
                            <span>Quick Links</span>
                        </h3>
                        <ul class="space-y-3">
                            <li><a href="<?php echo e(route('tasks.index')); ?>" class="text-gray-600 hover:text-indigo-600 transition text-sm flex items-center space-x-2 group">
                                <span class="w-0 group-hover:w-2 h-0.5 bg-indigo-600 transition-all duration-300"></span>
                                <span>Browse Tasks</span>
                            </a></li>
                            <li><a href="<?php echo e(route('leaderboard')); ?>" class="text-gray-600 hover:text-indigo-600 transition text-sm flex items-center space-x-2 group">
                                <span class="w-0 group-hover:w-2 h-0.5 bg-indigo-600 transition-all duration-300"></span>
                                <span>Leaderboard</span>
                            </a></li>
                            <li><a href="<?php echo e(route('dashboard')); ?>" class="text-gray-600 hover:text-indigo-600 transition text-sm flex items-center space-x-2 group">
                                <span class="w-0 group-hover:w-2 h-0.5 bg-indigo-600 transition-all duration-300"></span>
                                <span>Dashboard</span>
                            </a></li>
                            <?php if(auth()->guard()->check()): ?>
                            <li><a href="<?php echo e(route('report.show')); ?>" class="text-red-600 hover:text-red-800 transition text-sm flex items-center space-x-2 group font-semibold">
                                <span class="w-0 group-hover:w-2 h-0.5 bg-red-600 transition-all duration-300"></span>
                                <span>⚠️ Report Issue</span>
                            </a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Legal with Hover Animation -->
                    <div class="animate-slide-up" style="animation-delay: 0.3s;">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center space-x-2">
                            <span class="w-1 h-6 bg-gradient-to-b from-purple-600 to-pink-600 rounded-full"></span>
                            <span>Legal</span>
                        </h3>
                        <ul class="space-y-3">
                            <li><a href="<?php echo e(route('privacy')); ?>" class="text-gray-600 hover:text-purple-600 transition text-sm flex items-center space-x-2 group">
                                <span class="w-0 group-hover:w-2 h-0.5 bg-purple-600 transition-all duration-300"></span>
                                <span>Privacy Policy</span>
                            </a></li>
                            <li><a href="<?php echo e(route('terms')); ?>" class="text-gray-600 hover:text-purple-600 transition text-sm flex items-center space-x-2 group">
                                <span class="w-0 group-hover:w-2 h-0.5 bg-purple-600 transition-all duration-300"></span>
                                <span>Terms of Service</span>
                            </a></li>
                            <li><a href="<?php echo e(route('privacy')); ?>" class="text-gray-600 hover:text-purple-600 transition text-sm flex items-center space-x-2 group">
                                <span class="w-0 group-hover:w-2 h-0.5 bg-purple-600 transition-all duration-300"></span>
                                <span>Cookie Policy</span>
                            </a></li>
                            <li><a href="<?php echo e(route('contact')); ?>" class="text-gray-600 hover:text-purple-600 transition text-sm flex items-center space-x-2 group">
                                <span class="w-0 group-hover:w-2 h-0.5 bg-purple-600 transition-all duration-300"></span>
                                <span>Contact Us</span>
                            </a></li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom Bar with Animation -->
                <div class="border-t border-gray-200 mt-12 pt-8 text-center animate-fade-in" style="animation-delay: 0.4s;">
                    <p class="text-gray-600 text-sm flex items-center justify-center space-x-2">
                        <span>&copy; 2026 InternGrowth. All rights reserved.</span>
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Auto-dismiss flash messages -->
    <script>
        // Auto-dismiss all notifications after 3 seconds
        function autoDismissNotification(elementId) {
            const notification = document.getElementById(elementId);
            if (notification) {
                setTimeout(() => {
                    notification.style.transition = 'opacity 0.5s ease-out';
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 500);
                }, 3000);
            }
        }

        // Apply to all notification types
        autoDismissNotification('success-notification');
        autoDismissNotification('message-sent-notification');
        autoDismissNotification('task-created-notification');
        autoDismissNotification('error-notification');
    </script>

    <?php if(auth()->guard()->check()): ?>
    <script>
        // ── Notification Bell ──────────────────────────────────────────────
        const NOTIF_URL   = '<?php echo e(route("notifications.unread-count")); ?>';
        const NOTIF_READ_ALL = '<?php echo e(route("notifications.read-all")); ?>';
        const CSRF        = document.querySelector('meta[name="csrf-token"]').content;

        let dropdownOpen = false;
        let cachedNotifs = [];

        function toggleNotifDropdown() {
            dropdownOpen = !dropdownOpen;
            document.getElementById('notif-dropdown').classList.toggle('hidden', !dropdownOpen);
            if (dropdownOpen) loadNotifDropdown();
        }

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notif-wrapper');
            if (wrapper && !wrapper.contains(e.target) && dropdownOpen) {
                dropdownOpen = false;
                document.getElementById('notif-dropdown').classList.add('hidden');
            }
        });

        async function loadNotifDropdown() {
            try {
                const res  = await fetch('<?php echo e(url("/notifications")); ?>?dropdown=1', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                // We'll just fetch the unread count and recent items via a simple approach
                await refreshBadge();
            } catch(e) {}
        }

        async function refreshBadge() {
            try {
                const res  = await fetch(NOTIF_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                const badge = document.getElementById('notif-badge');
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }
            } catch(e) {}
        }

        async function markAllRead() {
            await fetch(NOTIF_READ_ALL, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
            });
            refreshBadge();
            // Reload dropdown content
            await fetchDropdownNotifs();
        }

        async function fetchDropdownNotifs() {
            try {
                const res  = await fetch('<?php echo e(url("/notifications/dropdown")); ?>', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;
                const data = await res.json();
                renderDropdown(data.notifications);
            } catch(e) {}
        }

        function renderDropdown(items) {
            const list  = document.getElementById('notif-list');
            const empty = document.getElementById('notif-empty');
            if (!items || items.length === 0) {
                list.innerHTML = '<div class="px-4 py-6 text-center text-gray-400 text-sm">No notifications</div>';
                return;
            }
            const typeIcon = {
                success: '✅', warning: '⚠️', error: '❌', info: 'ℹ️'
            };
            list.innerHTML = items.map(n => `
                <div class="px-4 py-3 hover:bg-indigo-50 transition cursor-pointer ${n.is_read ? 'opacity-70' : 'bg-indigo-50/40'}"
                     onclick="markOneRead(${n.id}, this)">
                    <div class="flex items-start gap-2">
                        <span class="text-base flex-shrink-0 mt-0.5">${typeIcon[n.type] || 'ℹ️'}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">${n.title}${!n.is_read ? ' <span class="inline-block w-2 h-2 bg-indigo-500 rounded-full align-middle"></span>' : ''}</p>
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">${n.message}</p>
                            <p class="text-xs text-gray-400 mt-1">${n.time_ago}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        async function markOneRead(id, el) {
            await fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
            });
            el.classList.add('opacity-70');
            el.classList.remove('bg-indigo-50/40');
            refreshBadge();
        }

        // Poll badge every 30 seconds
        refreshBadge();
        setInterval(refreshBadge, 30000);

        // Load dropdown items when opened
        document.getElementById('notif-btn').addEventListener('click', function() {
            if (dropdownOpen) fetchDropdownNotifs();
        });
    </script>
    <?php endif; ?>
</body>
</html><?php /**PATH C:\Users\Raval Ruchit\Desktop\InternGrowth\InternGrowth\resources\views/layouts/app.blade.php ENDPATH**/ ?>