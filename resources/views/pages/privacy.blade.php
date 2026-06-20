<x-app-layout>
    <div class="ig-container max-w-4xl py-12 space-y-8 ig-anim-fade-up">
        <!-- Header -->
        <div class="mb-4">
            <p class="ig-eyebrow mb-2">— Legal</p>
            <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                Privacy <span class="ig-serif text-[var(--ig-accent)]">Policy.</span>
            </h1>
            <p class="text-xs text-[var(--ig-muted)] font-mono mt-3">Last updated: {{ now()->format('F d, Y') }}</p>
        </div>
        
        <!-- Document Content -->
        <div class="ig-card p-6 sm:p-8 bg-white border border-[var(--ig-line)] text-sm sm:text-base leading-relaxed text-[var(--ig-ink-2)] space-y-6">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">1. Information We Collect</h2>
                <p class="mb-3">We collect information you provide directly to us, including:</p>
                <ul class="list-disc pl-6 space-y-1 text-[var(--ig-muted)]">
                    <li>Name, email address, and college information</li>
                    <li>Profile information and skills matrix</li>
                    <li>Task submissions and work samples</li>
                    <li>Communication data across direct hiring pipelines</li>
                </ul>
            </div>

            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">2. How We Use Your Information</h2>
                <p class="mb-3">We use the information we collect to:</p>
                <ul class="list-disc pl-6 space-y-1 text-[var(--ig-muted)]">
                    <li>Provide and improve our platform and user experience</li>
                    <li>Match students with relevant startup task opportunities</li>
                    <li>Process wallet top-ups and issue completed task credentials</li>
                    <li>Communicate with you regarding account updates</li>
                </ul>
            </div>

            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">3. Information Sharing</h2>
                <p class="mb-3">We do not sell your personal information. We may share your information with:</p>
                <ul class="list-disc pl-6 space-y-1 text-[var(--ig-muted)]">
                    <li>Startups you apply to (such as profile details and task proofs)</li>
                    <li>Service providers who assist with our operations</li>
                    <li>Legal authorities when required by law</li>
                </ul>
            </div>

            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">4. Data Security</h2>
                <p class="mb-3">We implement standard security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.</p>
            </div>

            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">5. Your Rights</h2>
                <p class="mb-3">You have the right to:</p>
                <ul class="list-disc pl-6 space-y-1 text-[var(--ig-muted)]">
                    <li>Access your personal data</li>
                    <li>Correct inaccurate data</li>
                    <li>Request deletion of your account records</li>
                    <li>Opt-out of system notifications and emails</li>
                </ul>
            </div>

            <div class="border-t border-[var(--ig-line)] pt-6">
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">6. Contact Us</h2>
                <p class="mb-3">If you have questions about this Privacy Policy, please contact our legal desk at: 
                    <a href="mailto:privacy@interngrowth.com" class="text-[var(--ig-azure)] hover:underline font-semibold">privacy@interngrowth.com</a>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
