@props(['user'])

<div class="user-card" data-role="{{ $user->is_superadmin ? 'webmaster' : ($user->isAdmin ? 'leader' : ($user->is_officer ? 'officer' : 'others')) }}">
    <a href="{{ route('users.show', $user->id) }}" 
       class="hover-card block glass-effect rounded-xl p-6">
        <div class="flex items-center space-x-6">
            <div class="profile-picture-container flex-shrink-0 w-16 h-16">
                <div class="relative w-full h-full">
                    <div class="w-full h-full rounded-lg overflow-hidden bg-gray-900/50 ring-1 ring-gray-700/50">
                        <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-profile.png') }}"
                             alt="{{ $user->charactername }}"
                             class="w-full h-full object-cover object-center">
                    </div>
                    
                    @if($user->is_superadmin || $user->isAdmin || $user->is_officer)
                        <div class="absolute -bottom-1 -right-1 role-badge {{ $user->is_superadmin ? 'webmaster' : ($user->isAdmin ? 'leader' : 'officer') }} p-1.5 rounded-md">
                            @if($user->is_superadmin)
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @elseif($user->isAdmin)
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                <p class="text-sm font-medium truncate {{ $user->is_superadmin ? 'bg-gradient-to-r from-purple-400 via-blue-400 to-purple-400 text-transparent bg-clip-text' : 'text-white' }} group-hover:text-blue-400 transition-colors duration-200">
                    {{ $user->charactername }}
                    </p>
                    <div class="flex items-center space-x-3">
                        @if($user->is_superadmin)
                            <span class="px-3 py-1.5 text-sm font-medium rounded-lg bg-purple-500/10 text-purple-400">
                                {{ __('Webmester') }}
                            </span>
                        @elseif($user->isAdmin)
                            <span class="px-3 py-1.5 text-sm font-medium rounded-lg bg-blue-500/10 text-blue-400">
                                {{ __('Leader') }}
                            </span>
                        @elseif($user->is_officer)
                            <span class="px-3 py-1.5 text-sm font-medium rounded-lg bg-emerald-500/10 text-emerald-400">
                                {{ __('Tiszt') }}
                            </span>
                        @endif
                    </div>
                </div>
                
                @if($user->title)
                    <p class="mt-1.5 text-sm text-gray-400 truncate">
                        {{ $user->title }}
                    </p>
                @endif
            </div>
        </div>
    </a>
</div>