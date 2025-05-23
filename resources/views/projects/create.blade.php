<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Create Project</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Project Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full mt-1 border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">Description</label>
                        <textarea name="description" rows="3"
                                    class="w-full mt-1 border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="deadline" class="block text-gray-700">Deadline</label>
                        <input type="date" name="deadline" value="{{ old('deadline') }}"
                                class="w-full mt-1 border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-gray-700">Status</label>
                        <select name="status" required
                                class="w-full mt-1 border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('in_progress') == 'in_progress' ? 'selected' : ''}}>In Progress</option>
                            <option value="completed" {{ old('completed') == 'completed' ? 'selected' : ''}}>Completed</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="team_id" class="block text-gray-700">Team</label>
                        <select name="team_id" required
                                class="w-full mt-1 border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring focus-ring-indigo-200">
                            <option value="">-- Select Team --</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('projects.index') }}" class="mr-4  text-gray-600 hover:underline">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>