<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">{{ $task->title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto space-y-6">

            <div class="bg-white p-6 rounded shadow">
                <p><strong>Project:</strong> {{ $task->project->name }}</p>
                <p><strong>Assigned To:</strong> {{ $task->assignedUser->name }}</p>
                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $task->status)) }}</p>

                @auth
                    @if (auth()->id() === $task->assigned_to)
                        <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="mt-3">
                            @csrf
                            @method('PUT')

                            <label for="status" class="block text-sm font-medium text-gray-700">Update Status</label>
                            <select name="status" id="status" class="mt-1 block w-full border rounded p-2">
                                <option value="to_do" @selected($task->status === 'to_do')>To Do</option>
                                <option value="in_progress" @selected($task->status === 'in_progress')>In Progress</option>
                                <option value="done" @selected($task->status === 'done')>Done</option>
                            </select>

                            <button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Update Status
                            </button>
                        </form>
                    @endif
                @endauth

                <p><strong>Due Date:</strong> {{ $task->due_date ?? 'N/A' }}</p>
                <p class="mt-4">{{ $task->description }}</p>
            </div>

            <div class="bg-white p-6 rounded shadow space-y-4">
                <h3 class="text-lg font-medium">Comments</h3>

                @forelse ($task->comments as $comment)
                    <div class="border-b pb-2">
                        <p class="font-semibold">{{ $comment->user->name }}
                            <span class="text-xs text-gray-500">
                                ({{ $comment->created_at->diffForHumans() }})
                            </span>
                        </p>
                        <p>{{ $comment->content }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">No comments yet.</p>
                @endforelse
            </div>

            @if (auth()->id() === $task->assigned_to || in_array(auth()->user()->role, ['admin', 'manager']))
                <div class="bg-white p-6 rounded shadow">
                    <h3 class="text-lg font-medium mb-2">Add Comment</h3>

                    <form method="POST" action="{{ route('tasks.comments.store', $task) }}">
                        @csrf
                        <textarea name="content" rows="3" class="w-full border p-2 rounded" required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Submit
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
