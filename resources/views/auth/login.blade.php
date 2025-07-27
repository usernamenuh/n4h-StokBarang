@extends('layouts.auth')

@section('title', 'Login')

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
                    Welcome Back
                </h1>
                <p class="slide-up text-white/60 text-xs" style="animation-delay: 0.3s;">
                    Sign in to continue to StockMaster
                </p>
            </div>
            
            <!-- Login form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4 relative z-10" onsubmit="showLoading(event)">
                @csrf
                
                <div class="space-y-3">
                    <!-- Email input -->
                    <div class="relative input-container group">
                        <div class="relative flex items-center overflow-hidden rounded-lg">
                            <i class="fas fa-envelope absolute left-3 w-4 h-4 input-icon transition-all duration-300"></i>
                            <input 
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

                    <!-- Password input -->
                    <div class="relative input-container group">
                        <div class="relative flex items-center overflow-hidden rounded-lg">
                            <i class="fas fa-lock absolute left-3 w-4 h-4 input-icon transition-all duration-300"></i>
                            <input 
                                id="password"
                                type="password" 
                                name="password"
                                placeholder="Password"
                                required 
                                autocomplete="current-password"
                                class="glass-input w-full h-10 pl-10 pr-10 rounded-lg transition-all duration-300 @error('password') border-red-400/50 @enderror"
                            />
                            <button 
                                type="button" 
                                onclick="togglePassword()"
                                class="absolute right-3 cursor-pointer"
                            >
                                <i id="password-icon" class="fas fa-eye-slash w-4 h-4 text-white/40 hover:text-white transition-colors duration-300"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <!-- Remember me & Forgot password -->
                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center space-x-2">
                        <input
                            id="remember-me"
                            name="remember"
                            type="checkbox"
                            {{ old('remember') ? 'checked' : '' }}
                            class="custom-checkbox"
                        />
                        <label for="remember-me" class="text-xs text-white/60 hover:text-white/80 transition-colors duration-200">
                            Remember me
                        </label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-white/60 hover:text-white transition-colors duration-200">
                            Forgot password?
                        </a>
                    @endif
                </div>
                
                <!-- Sign in button -->
                <button 
                    type="submit" 
                    class="glass-button w-full h-10 rounded-lg transition-all duration-300 flex items-center justify-center mt-5 group relative overflow-hidden"
                    id="login-btn"
                >
                    <div class="button-glow"></div>
                    <div class="button-shimmer"></div>
                    <span id="btn-text" class="flex items-center justify-center gap-1 text-sm font-medium relative z-10">
                        Sign In
                        <i class="fas fa-arrow-right w-3 h-3 group-hover:translate-x-1 transition-transform duration-300"></i>
                    </span>
                    <div id="btn-loading" class="loading-spinner hidden"></div>
                </button>
                
                <!-- Divider -->
                <div class="relative mt-2 mb-5 flex items-center">
                    <div class="flex-grow border-t border-white/5"></div>
                    <span class="mx-3 text-xs text-white/40">or</span>
                    <div class="flex-grow border-t border-white/5"></div>
                </div>
                
                <!-- Google Sign In -->
                <button 
                    type="button"
                    onclick="signInWithGoogle()"
                    class="glass-button-secondary w-full h-10 rounded-lg transition-all duration-300 flex items-center justify-center gap-2 group relative overflow-hidden"
                >
                    <div class="absolute inset-0 bg-white/5 rounded-lg filter blur opacity-0 group-hover:opacity-70 transition-opacity duration-300"></div>
                    <div class="w-4 h-4 flex items-center justify-center text-white/80 group-hover:text-white transition-colors duration-300">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.56.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                    </div>
                    <span class="text-white/80 group-hover:text-white transition-colors text-xs relative z-10">
                        Sign in with Google
                    </span>
                    <!-- Button hover effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/5 to-white/0 transform -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-in-out"></div>
                </button>
                
                <!-- Sign up link -->
                <p class="text-center text-xs text-white/60 mt-4 slide-up" style="animation-delay: 0.5s;">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="relative inline-block group text-white hover:text-white/70 transition-colors duration-300 font-medium">
                        Sign up
                        <span class="absolute bottom-0 left-0 w-0 h-[1px] bg-white group-hover:w-full transition-all duration-300"></span>
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('password-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.className = 'fas fa-eye w-4 h-4 text-white/40 hover:text-white transition-colors duration-300';
        } else {
            passwordInput.type = 'password';
            passwordIcon.className = 'fas fa-eye-slash w-4 h-4 text-white/40 hover:text-white transition-colors duration-300';
        }
    }
    
    function showLoading(event) {
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');
        
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
    }
    
    function signInWithGoogle() {
        alert('Google Sign In akan diimplementasikan');
    }
    
</script>
@endpush
@endsection
