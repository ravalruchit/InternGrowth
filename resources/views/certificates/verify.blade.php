<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Certificate Verified</h1>
                <p class="text-gray-600">This certificate is authentic and valid</p>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Certificate Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $certificate->certificate_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Student Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $certificate->student->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Task Completed</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $certificate->task->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Issued Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $certificate->issued_at->format('F d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
