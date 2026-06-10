@extends('layouts.guest')

@section('title', 'Sign In — Structure OS')

@section('content')
<div style="min-height: 100vh; display: flex; background-color: #16161a;">
    
    {{-- Left Side: Image and Branding --}}
    <div class="d-none d-lg-flex flex-column justify-content-center position-relative" style="width: 50%; background: linear-gradient(160deg, #0a0a14 0%, #0f1628 40%, #131a2e 70%, #0d1020 100%); overflow: hidden; padding: 4rem;">
        {{-- Architectural grid effect (CSS-only, no external image needed) --}}
        <div style="position: absolute; inset: 0; overflow: hidden;">
            {{-- Vertical lines --}}
            <div style="position:absolute;inset:0;background:repeating-linear-gradient(90deg,rgba(99,102,241,0.04) 0px,rgba(99,102,241,0.04) 1px,transparent 1px,transparent 80px);pointer-events:none;"></div>
            {{-- Horizontal lines --}}
            <div style="position:absolute;inset:0;background:repeating-linear-gradient(180deg,rgba(99,102,241,0.03) 0px,rgba(99,102,241,0.03) 1px,transparent 1px,transparent 80px);pointer-events:none;"></div>
            {{-- Building silhouette gradient --}}
            <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:200px;height:70%;background:linear-gradient(180deg,transparent 0%,rgba(10,15,30,0.9) 100%);clip-path:polygon(15% 100%,15% 40%,20% 40%,20% 10%,25% 10%,25% 40%,30% 40%,30% 25%,35% 25%,35% 40%,40% 40%,40% 5%,45% 5%,45% 40%,50% 40%,50% 20%,55% 20%,55% 40%,60% 40%,60% 30%,65% 30%,65% 40%,70% 40%,70% 50%,85% 50%,85% 100%);opacity:0.6;"></div>
            {{-- Glow effect --}}
            <div style="position:absolute;top:20%;left:30%;width:300px;height:300px;background:radial-gradient(circle,rgba(99,102,241,0.12) 0%,transparent 70%);border-radius:50%;"></div>
            <div style="position:absolute;bottom:30%;right:20%;width:200px;height:200px;background:radial-gradient(circle,rgba(59,130,246,0.08) 0%,transparent 70%);border-radius:50%;"></div>
        </div>
        
        <div style="position: relative; z-index: 10;">
            {{-- Logo Header --}}
            <div class="d-flex align-items-center gap-3 mb-5" style="position: absolute; top: -20vh;">
                <div style="width: 32px; height: 32px; background: white; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16161a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l-9 4v12l9 4 9-4V6l-9-4z"/></svg>
                </div>
                <span style="color: white; font-size: 1.25rem; font-weight: 700; letter-spacing: -0.02em;">Structure OS</span>
            </div>

            {{-- Main Text --}}
            <h1 style="color: white; font-size: 3rem; font-weight: 700; line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -0.02em; max-width: 500px;">
                Mastering the Art of Construction Logistics.
            </h1>
            <p style="color: #94a3b8; font-size: 1.05rem; line-height: 1.6; max-width: 480px;">
                The enterprise-grade operating system designed for modern architectural management and large-scale site coordination.
            </p>
        </div>
    </div>

    {{-- Right Side: Login Form --}}
    <div class="d-flex align-items-center justify-content-center" style="width: 100%; max-width: 50%; padding: 2rem;">
        <div style="width: 100%; max-width: 420px;">
            
            <div class="mb-5">
                <h2 style="color: white; font-size: 1.8rem; font-weight: 700; margin-bottom: 0.5rem; letter-spacing: -0.02em;">Welcome Back</h2>
                <p style="color: #94a3b8; font-size: 0.95rem;">Please enter your credentials to access your dashboard.</p>
            </div>

            @if ($errors->any())
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 24px;">
                    @foreach ($errors->all() as $error)
                        <div style="color: #fca5a5; font-size: 0.85rem;">{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb-4">
                    <label style="color: #cbd5e1; font-size: 0.85rem; font-weight: 500; margin-bottom: 8px; display: block;">Username or Email</label>
                    <input type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username"
                        style="width: 100%; background: #1a1a24; border: 1px solid #2d2d3f; border-radius: 8px; padding: 12px 16px; color: #f8fafc; font-size: 0.9rem; transition: all 0.2s; outline: none;"
                        placeholder="e.g. name@structure.os"
                        onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 2px rgba(99,102,241,0.2)'"
                        onblur="this.style.borderColor='#2d2d3f'; this.style.boxShadow='none'">
                </div>

                <div class="mb-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label style="color: #cbd5e1; font-size: 0.85rem; font-weight: 500; margin: 0;">Password</label>
                        <a href="#" style="color: #94a3b8; font-size: 0.8rem; text-decoration: none;">Forgot password?</a>
                    </div>
                    <input type="password" name="password" required autocomplete="current-password"
                        style="width: 100%; background: #1a1a24; border: 1px solid #2d2d3f; border-radius: 8px; padding: 12px 16px; color: #f8fafc; font-size: 0.9rem; transition: all 0.2s; outline: none;"
                        placeholder="••••••••"
                        onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 2px rgba(99,102,241,0.2)'"
                        onblur="this.style.borderColor='#2d2d3f'; this.style.boxShadow='none'">
                    
                    <button type="button" style="position: absolute; right: 12px; bottom: 12px; background: transparent; border: none; color: #64748b; cursor: pointer;">
                        <i class="far fa-eye"></i>
                    </button>
                </div>

                <div class="mb-5">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: #94a3b8; font-size: 0.85rem;">
                        <input type="checkbox" name="remember" value="1" style="width: 16px; height: 16px; accent-color: #6366f1; background: #1a1a24; border: 1px solid #2d2d3f; border-radius: 4px;">
                        Remember this device for 30 days
                    </label>
                </div>

                <button type="submit" style="width: 100%; background: #6366f1; color: white; border: none; border-radius: 8px; padding: 12px 20px; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 40px;"
                    onmouseover="this.style.background='#4f46e5'"
                    onmouseout="this.style.background='#6366f1'">
                    Sign In 
                    <i class="fas fa-sign-in-alt" style="font-size: 0.85rem;"></i>
                </button>

                {{-- Footer Links --}}
                <div style="position: relative; text-align: center; margin-bottom: 30px;">
                    <hr style="border-color: #2d2d3f; margin: 0;">
                    <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #16161a; padding: 0 10px; color: #64748b; font-size: 0.75rem; letter-spacing: 0.05em;">AUTHENTICATION</span>
                </div>

                <div class="d-flex gap-3 mb-5">
                    <a href="#" style="flex: 1; text-align: center; padding: 10px; border: 1px solid #2d2d3f; border-radius: 8px; color: #94a3b8; text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: background 0.2s;"
                        onmouseover="this.style.background='#1a1a24'; this.style.color='#f8fafc'"
                        onmouseout="this.style.background='transparent'; this.style.color='#94a3b8'">
                        <i class="fas fa-headset me-2"></i> Support
                    </a>
                    <a href="#" style="flex: 1; text-align: center; padding: 10px; border: 1px solid #2d2d3f; border-radius: 8px; color: #94a3b8; text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: background 0.2s;"
                        onmouseover="this.style.background='#1a1a24'; this.style.color='#f8fafc'"
                        onmouseout="this.style.background='transparent'; this.style.color='#94a3b8'">
                        <i class="fas fa-user-plus me-2"></i> Request Access
                    </a>
                </div>

                <p style="color: #64748b; font-size: 0.75rem; text-align: center; line-height: 1.5;">
                    By signing in, you agree to our <a href="#" style="color: #94a3b8; text-decoration: none;">Terms of Service</a> and <a href="#" style="color: #94a3b8; text-decoration: none;">Privacy Policy</a>.
                </p>

            </form>
        </div>
    </div>
</div>
@endsection
