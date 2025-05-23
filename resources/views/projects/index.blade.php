<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Projects</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:pax-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('projects.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + New Project
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Team</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Deadline</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $project->name }}</td>
                                <td class="px-4 py-2">{{ $project->team->name }}</td>
                                <td class="px-4 py-2 capitalize">{{ str_replace('_', ' ', $project->status) }}</td>
                                <td class="px-4 py-2">{{ $project->deadline ?? 'No deadline' }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('projects.edit', $project) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">No projects available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>