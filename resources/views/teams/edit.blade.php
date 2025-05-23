<x-app-layout>
    <div class="max-w-md mx-auto py-8">
        <h2 class="text-2xl font-bold mb-4">Edit Team</h2>

        @if($errors->any())
            <div class="mb-4 text-red-500">
                <ul>
                    @foreach($errors->all() AS $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('teams.update', $team) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium">Team Name</label>
                <input type="text" name="name" value="{{ old('name', $team->name) }}"
                        class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300" required>
            </div>

            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Update</button>
            <a href="{{ route('teams.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </form>
    </div>
</x-app-layout>