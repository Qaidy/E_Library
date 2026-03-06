<x-app-layout>
    <div class="space-y-6 animate-slide-up">
        {{-- Profile Header --}}
        <div class="card p-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-2xl font-bold text-primary-600">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">{{ Auth::user()->name }}</h1>
                    <p class="text-sm text-slate-500">{{ Auth::user()->email }}</p>
                    <span class="badge badge-info mt-2">{{ ucfirst(Auth::user()->role ?? 'User') }}</span>
                </div>
            </div>
        </div>

        {{-- Update Profile Info --}}
        <div class="card p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update Password --}}
        <div class="card p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Delete Account --}}
        <div class="card p-6 border-red-200">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
