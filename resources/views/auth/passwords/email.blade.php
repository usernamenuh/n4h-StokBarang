@extends('layouts.auth') {{-- Assuming layouts.auth exists and provides the base structure --}}

@section('title', 'Forgot Password')

@section('content')
<div class="purple-bg min-h-screen w-screen relative flex items-center justify-center">
    <!-- Background glow effects -->
    <div class="bg-glow-top"></div>
    <div class="bg-glow-top-animated"></div>
    <div class="bg-glow-bottom"></div>
    <div class="bg-glow-spot-1"></div>
    <div class="bg-glow-spot-2"></div>
    
    <!-- Main card container -->
    <div class="fade-in w-full max-w-sm relative z-10 tilt-container">
        <div class="glass-card relative group rounded-2xl p-6 shadow-2xl overflow-hidden">
            <!-- Card glow effect -->
            <div class="card-glow"></div>
            
            <!-- Thin border effect - only on hover -->
            <div class="card-border-thin"></div>
            
            <!-- Card edge animations -->
            <div class="absolute inset-0 rounded-2xl overflow-hidden pointer-events-none">
                <!-- Existing light beams -->
                <div class="light-beam light-beam-top"></div>
                <div class="light-beam light-beam-right"></div>
                <div class="light-beam light-beam-bottom"></div>
                <div class="light-beam light-beam-left"></div>
                
                <!-- Card edge glows -->
                <div class="card-edge-glow card-edge-top"></div>
                <div class="card-edge-glow card-edge-right"></div>
                <div class="card-edge-glow card-edge-bottom"></div>
                <div class="card-edge-glow card-edge-left"></div>
                
                <!-- Corner glow spots -->
                <div class="corner-glow-1"></div>
                <div class="corner-glow-2"></div>
                <div class="corner-glow-3"></div>
                <div class="corner-glow-4"></div>
            </div>
            
            <!-- Card inner patterns -->
            <div class="card-pattern"></div>
            
            <!-- Logo and header -->
            <div class="text-center space-y-1 mb-5 relative z-10">
                <div class="slide-up mx-auto w-10 h-10 rounded-full border border-white/10 flex items-center justify-center relative overflow-hidden">
                    <span class="text-lg font-bold bg-clip-text text-transparent bg-gradient-to-b from-white to-white/70">S</span>
                    <div class="logo-glow"></div>
                </div>
                <h1 class="slide-up text-xl font-bold bg-clip-text text-transparent bg-gradient-to-b from-white to-white/80" style="animation-delay: 0.2s;">
                    Reset Password
                </h1>
                <p class="slide-up text-white/60 text-xs" style="animation-delay: 0.3s;">
                    Enter your email to receive a password reset link
                </p>
            </div>
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="relative z-10 mb-4 p-3 rounded-lg bg-green-500/20 text-green-200 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Email form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4 relative z-10" onsubmit="showLoading(event)">
                @csrf
                
                <div class="space-y-3">
                    <!-- Email input -->
                    <div class="relative input-container group">
                        <div class="relative flex items-center overflow-hidden rounded-lg">
                            <i class="fas fa-envelope absolute left-3 w-4 h-4 input-icon transition-all duration-300"></i>
                            <input 
                                id="email"
                                type="email" 
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Email address"
                                required 
                                autocomplete="email"
                                autofocus
                                class="glass-input w-full h-10 pl-10 pr-3 rounded-lg transition-all duration-300 @error('email') border-red-400/50 @enderror"
                            />
                        </div>
                        @error('email')
                            <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <!-- Send Reset Link button -->
                <button 
                    type="submit" 
                    class="glass-button w-full h-10 rounded-lg transition-all duration-300 flex items-center justify-center mt-5 group relative overflow-hidden"
                    id="login-btn" {{-- Reusing ID for loading spinner --}}
                >
                    <div class="button-glow"></div>
                    <div class="button-shimmer"></div>
                    <span id="btn-text" class="flex items-center justify-center gap-1 text-sm font-medium relative z-10">
                        Send Password Reset Link
                        <i class="fas fa-paper-plane w-3 h-3 group-hover:translate-x-1 transition-transform duration-300"></i>
                    </span>
                    <div id="btn-loading" class="loading-spinner hidden"></div>
                </button>
                
                <!-- Back to login link -->
                <p class="text-center text-xs text-white/60 mt-4 slide-up" style="animation-delay: 0.5s;">
                    Remember your password? 
                    <a href="{{ route('login') }}" class="relative inline-block group text-white hover:text-white/70 transition-colors duration-300 font-medium">
                        Login
                        <span class="absolute bottom-0 left-0 w-0 h-[1px] bg-white group-hover:w-full transition-all duration-300"></span>
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showLoading(event) {
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');
        
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
    }
</script>
@endpush
@endsection
