<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}!</h2>
        <p class="text-gray-600">You are logged in as a <strong>{{ ucfirst(Auth::user()->role) }}</strong>.</p>
    </div>
</x-app-layout>
