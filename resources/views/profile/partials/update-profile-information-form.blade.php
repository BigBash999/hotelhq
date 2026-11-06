<section>
    <header>
        <h2 class="text-lg font-medium text-gray-200">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Profile Photo</label>
            <div class="flex items-center gap-4">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" class="h-16 w-16 rounded-full object-cover border-2 border-indigo-500">
                @else
                    <div class="h-16 w-16 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xl font-bold border-2 border-indigo-500">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                <input type="file" name="profile_photo" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-900/50 file:text-indigo-300 hover:file:bg-indigo-900 transition">
            </div>
        </div>

        <div>
            <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
            <input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-900 border-gray-600 text-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" value="{{ old('name', $user->name) }}" required autofocus>
            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full bg-gray-900 border-gray-600 text-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" value="{{ old('email', $user->email) }}" required>
            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded shadow transition">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-green-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>