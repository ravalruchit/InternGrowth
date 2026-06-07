<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">Terms of Service</h1>
        
        <div class="prose prose-indigo max-w-none">
            <p class="text-gray-600 mb-6">Last updated: {{ now()->format('F d, Y') }}</p>
            
            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">1. Acceptance of Terms</h2>
            <p class="text-gray-700 mb-4">By accessing and using InternGrowth, you accept and agree to be bound by these Terms of Service.</p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">2. User Accounts</h2>
            <p class="text-gray-700 mb-4">You are responsible for:</p>
            <ul class="list-disc pl-6 text-gray-700 mb-4">
                <li>Maintaining the confidentiality of your account</li>
                <li>All activities that occur under your account</li>
                <li>Providing accurate and complete information</li>
            </ul>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">3. User Conduct</h2>
            <p class="text-gray-700 mb-4">You agree not to:</p>
            <ul class="list-disc pl-6 text-gray-700 mb-4">
                <li>Submit false or misleading information</li>
                <li>Violate any applicable laws or regulations</li>
                <li>Infringe on intellectual property rights</li>
                <li>Harass or harm other users</li>
            </ul>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">4. Payments and Refunds</h2>
            <p class="text-gray-700 mb-4">All payments are processed securely. Refund policies are determined on a case-by-case basis.</p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">5. Intellectual Property</h2>
            <p class="text-gray-700 mb-4">All content on InternGrowth is protected by copyright and other intellectual property laws.</p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">6. Termination</h2>
            <p class="text-gray-700 mb-4">We reserve the right to terminate or suspend your account for violations of these terms.</p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">7. Contact</h2>
            <p class="text-gray-700 mb-4">For questions about these Terms, contact: <a href="mailto:legal@interngrowth.com" class="text-indigo-600 hover:text-indigo-800">legal@interngrowth.com</a></p>
        </div>
    </div>
</x-app-layout>
