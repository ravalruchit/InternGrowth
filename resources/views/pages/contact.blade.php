<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">Contact Us</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-6 rounded-xl">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Get in Touch</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-indigo-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900">Email</p>
                            <a href="mailto:info@interngrowth.com" class="text-indigo-600 hover:text-indigo-800">info@interngrowth.com</a>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-indigo-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900">Address</p>
                            <p class="text-gray-600">InternGrowth Platform<br>India</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Support</h3>
                <p class="text-gray-600 mb-4">Need help? Check out our resources:</p>
                <ul class="space-y-2">
                    <li><a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800">Dashboard</a></li>
                    <li><a href="{{ route('tasks.index') }}" class="text-indigo-600 hover:text-indigo-800">Browse Tasks</a></li>
                    <li><a href="{{ route('leaderboard') }}" class="text-indigo-600 hover:text-indigo-800">Leaderboard</a></li>
                </ul>
            </div>
        </div>

        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-8 text-white text-center">
            <h3 class="text-2xl font-bold mb-4">Have Questions?</h3>
            <p class="mb-6">We're here to help! Reach out to us anytime.</p>
            <a href="mailto:info@interngrowth.com" class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Send us an Email
            </a>
        </div>
    </div>
</x-app-layout>
