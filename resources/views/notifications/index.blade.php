<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Notifications</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto space-y-4">
            @forelse($notifications as $notification)
                <div class="bg-white p-4 rounded shadow {{ $notification->read_at ? 'opacity-70' : '' }}">
                    <p> ({{ $notification->created_at->diffForHumans() }})</p>
                    <p>Project: {{ $notification->data['project'] ?? 'N/A' }}</p>
                    <p>Assigned By: {{ $notification->data['assigned_by'] ?? 'System' }}</p>
                    <p>Message: {{ $notification->data['message'] ?? 'N/A' }}</p>
                    <form action="{{ route('notifications.markRead', $notification->id) }}" method="POST" class="mt-2">
                        @csrf
                        <button class="text-sm text-blue-600 hover:underline">Mark as read</button>
                    </form>
                </div>
            @empty
                <p>No notifications yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
