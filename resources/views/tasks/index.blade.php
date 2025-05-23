<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tasks</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('tasks.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + New Task
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Project</th>
                            <th class="px-4 py-2">Assigned To</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Due Date</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $task->title }}</td>
                                <td class="px-4 py-2">{{ $task->project->name }}</td>
                                <td class="px-4 py-2">{{ $task->assignedUser->name }}</td>
                                <td class="px-4 py-2 capitalize">{{ str_replace('_', ' ', $task->status) }}</td>
                                <td class="px-4 py-2">{{ $task->due_date ?? 'No date'}}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-indigo-600 hover:underline mr-2">View</a>
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-gray-500">No task found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>