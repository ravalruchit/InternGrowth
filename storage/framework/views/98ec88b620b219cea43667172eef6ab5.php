<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InternGrowth - Student-Startup Internship Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'gradient': 'gradient 8s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'fade-in': 'fadeIn 1s ease-out',
                        'scale-in': 'scaleIn 0.6s ease-out',
                        'shimmer': 'shimmer 2s linear infinite',
                    },
                    keyframes: {
                        gradient: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-30px)' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(100px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.8)', opacity: '0' },
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap');
        
        body { 
            font-family: 'Inter', sans-serif;
        }
        
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
        
        .bg-animated-gradient {
            background: linear-gradient(-45deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #667eea 100%);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hover-glow {
            transition: all 0.3s ease;
        }
        
        .hover-glow:hover {
            box-shadow: 0 0 40px rgba(99, 102, 241, 0.6);
            transform: translateY(-5px);
        }
        
        .particle {
            position: absolute;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 relative overflow-x-hidden">
    <!-- Animated Background Particles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="particle" style="width: 100px; height: 100px; left: 10%; top: 20%; animation-delay: 0s;"></div>
        <div class="particle" style="width: 80px; height: 80px; left: 80%; top: 30%; animation-delay: 2s;"></div>
        <div class="particle" style="width: 120px; height: 120px; left: 50%; top: 60%; animation-delay: 4s;"></div>
        <div class="particle" style="width: 90px; height: 90px; left: 20%; top: 80%; animation-delay: 1s;"></div>
        <div class="particle" style="width: 110px; height: 110px; left: 70%; top: 70%; animation-delay: 3s;"></div>
    </div>

    <div class="min-h-screen flex flex-col relative z-10">
        <!-- Modern Animated Navigation -->
        <nav class="bg-white/80 backdrop-blur-lg shadow-xl border-b border-purple-100 sticky top-0 z-50 animate-fade-in">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center group">
                        <div class="relative">
                            <!-- Animated rainbow glow -->
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl blur-2xl opacity-50 group-hover:opacity-70 transition-opacity duration-300"></div>
                            
                            <!-- Logo without background -->
                            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="InternGrowth" class="h-16 w-auto relative z-10 transform group-hover:scale-110 transition-all duration-300 drop-shadow-2xl">
                        </div>
                        
                        <!-- Brand name -->
                        <span class="ml-4 text-3xl font-black font-poppins bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                            InternGrowth
                        </span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('dashboard')); ?>" class="group relative px-6 py-2.5 rounded-xl text-white font-semibold overflow-hidden transform hover:scale-105 transition-all duration-300 shadow-lg">
                                <span class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600"></span>
                                <span class="relative">Dashboard</span>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="text-gray-700 hover:text-indigo-600 px-4 py-2 rounded-lg font-medium transition-all duration-300">Login</a>
                            <a href="<?php echo e(route('register')); ?>" class="group relative px-6 py-2.5 rounded-xl text-white font-semibold overflow-hidden transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-2xl">
                                <span class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 animate-gradient bg-[length:200%_200%]"></span>
                                <span class="relative">Get Started</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow">
            <!-- Hero Section with Animations -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="text-center mb-20 animate-slide-up">
                    <h1 class="text-7xl font-black font-poppins mb-6 animate-scale-in">
                        <span class="text-gradient">Connect.</span>
                        <span class="text-gradient">Learn.</span>
                        <span class="text-gradient">Grow.</span>
                    </h1>
                    <p class="text-2xl text-gray-700 mb-10 font-medium animate-fade-in" style="animation-delay: 0.2s;">
                        The premier marketplace for student-startup internship microtasks 🚀
                    </p>
                    <div class="flex justify-center gap-6 animate-fade-in" style="animation-delay: 0.4s;">
                        <a href="<?php echo e(route('register')); ?>" class="group relative px-10 py-4 rounded-2xl text-white text-lg font-bold overflow-hidden transform hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-indigo-500/50">
                            <span class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 animate-gradient bg-[length:200%_200%]"></span>
                            <span class="relative flex items-center space-x-2">
                                <span>Get Started Free</span>
                                <svg class="w-6 h-6 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </a>
                        <a href="<?php echo e(route('tasks.index')); ?>" class="group px-10 py-4 rounded-2xl text-indigo-600 text-lg font-bold border-4 border-indigo-600 hover:bg-indigo-600 hover:text-white transition-all duration-300 transform hover:scale-105 shadow-xl">
                            <span class="flex items-center space-x-2">
                                <span>Browse Tasks</span>
                                <svg class="w-6 h-6 transform group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Feature Cards with Staggered Animation -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                    <div class="group bg-white/80 backdrop-blur-lg p-10 rounded-3xl shadow-2xl hover-glow text-center border border-purple-100 animate-slide-up" style="animation-delay: 0.1s;">
                        <div class="text-7xl mb-6 transform group-hover:scale-125 group-hover:rotate-12 transition-all duration-500">🎓</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 font-poppins">For Students</h3>
                        <p class="text-gray-600 text-lg leading-relaxed">Build your portfolio, earn points, and gain real-world experience with top startups</p>
                        <div class="mt-6 flex justify-center space-x-2">
                            <span class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-sm font-semibold">Portfolio</span>
                            <span class="px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">Rewards</span>
                        </div>
                    </div>
                    
                    <div class="group bg-white/80 backdrop-blur-lg p-10 rounded-3xl shadow-2xl hover-glow text-center border border-purple-100 animate-slide-up" style="animation-delay: 0.2s;">
                        <div class="text-7xl mb-6 transform group-hover:scale-125 group-hover:rotate-12 transition-all duration-500">🚀</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 font-poppins">For Startups</h3>
                        <p class="text-gray-600 text-lg leading-relaxed">Access talented students for microtasks and internship projects at scale</p>
                        <div class="mt-6 flex justify-center space-x-2">
                            <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">Talent</span>
                            <span class="px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-semibold">Fast</span>
                        </div>
                    </div>
                    
                    <div class="group bg-white/80 backdrop-blur-lg p-10 rounded-3xl shadow-2xl hover-glow text-center border border-purple-100 animate-slide-up" style="animation-delay: 0.3s;">
                        <div class="text-7xl mb-6 transform group-hover:scale-125 group-hover:rotate-12 transition-all duration-500">🏆</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 font-poppins">Earn Rewards</h3>
                        <p class="text-gray-600 text-lg leading-relaxed">Get verified certificates and climb the leaderboard to showcase your skills</p>
                        <div class="mt-6 flex justify-center space-x-2">
                            <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold">Certificates</span>
                            <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">Rankings</span>
                        </div>
                    </div>
                </div>

                <!-- CTA Section with Gradient Background -->
                <div class="relative bg-animated-gradient rounded-3xl p-16 text-center overflow-hidden shadow-2xl animate-scale-in" style="animation-delay: 0.5s;">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10">
                        <h3 class="text-5xl font-black text-white mb-6 font-poppins animate-pulse-slow">Ready to Start Your Journey?</h3>
                        <p class="text-2xl text-white/90 mb-10 font-medium">Join thousands of students and startups already on InternGrowth ✨</p>
                        <a href="<?php echo e(route('register')); ?>" class="group inline-block bg-white text-indigo-600 px-12 py-5 rounded-2xl text-xl font-bold hover:bg-gray-100 transform hover:scale-110 transition-all duration-300 shadow-2xl">
                            <span class="flex items-center space-x-3">
                                <span>Sign Up Now</span>
                                <svg class="w-7 h-7 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </main>

        <!-- Modern Footer -->
        <footer class="bg-white/80 backdrop-blur-lg border-t border-purple-100 py-10 mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-gray-600 flex items-center justify-center space-x-2">
                    <span>&copy; 2026 InternGrowth. All rights reserved.</span>
                    <span class="text-red-500 animate-pulse">❤️</span>
                    <span>Made with passion</span>
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Raval Ruchit\Desktop\InternGrowth\InternGrowth\resources\views/welcome.blade.php ENDPATH**/ ?>