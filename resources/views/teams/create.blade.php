<x-app-layout>
    <div class="max-w-md mx-auto py-8">
        <h2 class="text-2xl font-bold mb-4">Create Team</h2>

        @if($errors->any())
            <div class="mb-4 text-red-500">
                <ul>
                    @foreach($errors->all() AS $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('teams.store') }}" class="space-y-4">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium">Team Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300" required>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
            <a href="{{ route('teams.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </form>
    </div>
</x-app-layout>