<x-app-layout>
    <div class="ig-container max-w-4xl py-12 space-y-8 ig-anim-fade-up">
        <!-- Header -->
        <div class="mb-4">
            <p class="ig-eyebrow mb-2">— Legal</p>
            <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                Terms of <span class="ig-serif text-[var(--ig-accent)]">Service.</span>
            </h1>
            <p class="text-xs text-[var(--ig-muted)] font-mono mt-3">Last updated: {{ now()->format('F d, Y') }}</p>
        </div>
        
        <!-- Document Content -->
        <div class="ig-card p-6 sm:p-8 bg-white border border-[var(--ig-line)] text-sm sm:text-base leading-relaxed text-[var(--ig-ink-2)] space-y-6">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">1. Acceptance of Terms</h2>
                <p>By accessing and utilizing the InternGrowth platform, you accept and agree to be bound by these Terms of Service. If you do not agree, please do not use the services.</p>
            </div>

            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">2. User Accounts</h2>
                <p class="mb-3">You are responsible for the following account tasks:</p>
                <ul class="list-disc pl-6 space-y-1 text-[var(--ig-muted)]">
                    <li>Maintaining absolute confidentiality of your login credentials</li>
                    <li>All actions and submissions conducted under your profile</li>
                    <li>Providing accurate, complete, and verified information</li>
                </ul>
            </div>

            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">3. User Conduct</h2>
                <p class="mb-3">You explicitly agree not to engage in the following behaviors:</p>
                <ul class="list-disc pl-6 space-y-1 text-[var(--ig-muted)]">
                    <li>Submitting false evidence, documents, or misleading mock work</li>
                    <li>Violating any state, federal, or platform guidelines</li>
                    <li>Infringing upon patents, trademarks, or copyrights</li>
                    <li>Harassing, spamming, or harming other platform participants</li>
                </ul>
            </div>

            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">4. Payments and Refunds</h2>
                <p>All payments, wallet transfers, and top-up operations are final and secured. Dispute resolutions are evaluated manually on a case-by-case basis by InternGrowth operators.</p>
            </div>

            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">5. Intellectual Property</h2>
                <p>All trademarks, graphics, code, and interface layouts presented on InternGrowth are owned by the platform and protected by international intellectual property laws.</p>
            </div>

            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">6. Termination</h2>
                <p>We reserve the right to suspend or permanently block your access to the marketplace for any violations of conduct or false submission practices.</p>
            </div>

            <div class="border-t border-[var(--ig-line)] pt-6">
                <h2 class="text-lg sm:text-xl font-bold text-[var(--ig-ink)] mb-3">7. Contact</h2>
                <p class="mb-3">For inquiries regarding terms or service policies, contact: 
                    <a href="mailto:legal@interngrowth.com" class="text-[var(--ig-azure)] hover:underline font-semibold">legal@interngrowth.com</a>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
