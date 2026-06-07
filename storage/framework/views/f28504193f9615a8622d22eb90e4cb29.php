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
                        animation: {
                            'gradient': 'gradient 8s linear infinite',
                            'float': 'float 6s ease-in-out infinite',
                            'slide-in': 'slideIn 0.6s ease-out',
                            'fade-in': 'fadeIn 0.8s ease-out',
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
                            slideIn: {
                                '0%': { transform: 'translateX(-100px)', opacity: '0' },
                                '100%': { transform: 'translateX(0)', opacity: '1' },
                            },
                            fadeIn: {
                                '0%': { opacity: '0' },
                                '100%': { opacity: '1' },
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
            
            .particle {
                position: absolute;
                background: rgba(99, 102, 241, 0.1);
                border-radius: 50%;
                animation: float 6s ease-in-out infinite;
            }
            
            .glass {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 relative overflow-x-hidden">
        <!-- Animated Background Particles -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="particle" style="width: 80px; height: 80px; left: 10%; top: 20%; animation-delay: 0s;"></div>
            <div class="particle" style="width: 60px; height: 60px; left: 80%; top: 30%; animation-delay: 2s;"></div>
            <div class="particle" style="width: 100px; height: 100px; left: 50%; top: 60%; animation-delay: 4s;"></div>
            <div class="particle" style="width: 70px; height: 70px; left: 20%; top: 80%; animation-delay: 1s;"></div>
            <div class="particle" style="width: 90px; height: 90px; left: 70%; top: 70%; animation-delay: 3s;"></div>
        </div>

        <div class="min-h-screen flex items-center justify-center relative z-10 py-6 px-4">
            <div class="w-full max-w-md">
                <!-- Enhanced Animated Logo -->
                <div class="text-center mb-8 animate-fade-in">
                    <a href="/" class="group inline-block">
                        <div class="relative inline-block">
                            <!-- Rainbow glow effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-3xl blur-2xl opacity-50 group-hover:opacity-70 transition-opacity duration-300"></div>
                            
                            <!-- Logo without background -->
                            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="InternGrowth" class="h-20 w-auto relative z-10 transform group-hover:scale-110 transition-all duration-300 drop-shadow-2xl">
                        </div>
                        
                        <!-- Brand name below logo -->
                        <h1 class="mt-4 text-3xl font-black font-poppins bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent group-hover:scale-105 transition-transform duration-300">
                            InternGrowth
                        </h1>
                    </a>
                </div>

                <!-- Auth Card with Glass Effect -->
                <div class="animate-slide-in">
                    <div class="glass px-6 py-8 shadow-2xl rounded-3xl border-2 border-white/50">
                        <?php echo e($slot); ?>

                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-6 text-center animate-fade-in">
                    <div class="flex justify-center space-x-6">
                        <a href="#" class="text-gray-600 hover:text-indigo-600 transition text-xs font-medium transform hover:scale-110 duration-300">Privacy</a>
                        <a href="#" class="text-gray-600 hover:text-indigo-600 transition text-xs font-medium transform hover:scale-110 duration-300">Terms</a>
                        <a href="#" class="text-gray-600 hover:text-indigo-600 transition text-xs font-medium transform hover:scale-110 duration-300">Contact</a>
                    </div>
                    <p class="text-gray-500 text-xs mt-3">&copy; 2026 InternGrowth. All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\Users\Raval Ruchit\Desktop\InternGrowth\InternGrowth\resources\views/layouts/guest.blade.php ENDPATH**/ ?>