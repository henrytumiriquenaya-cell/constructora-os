@extends('layouts.guest')

@section('title', 'Iniciar sesión')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 50%,#0f172a 100%);padding:20px;">
    <div style="display:flex;max-width:920px;width:100%;border-radius:20px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.08);">

        {{-- Panel izquierdo --}}
        <div class="d-none d-lg-flex flex-column justify-content-between p-5" style="width:50%;background:linear-gradient(135deg,#020617 0%,#0f172a 60%,#1e1b4b 100%);position:relative;">
            <div style="position:absolute;inset:0;background:radial-gradient(circle at 20% 20%,rgba(99,102,241,0.15),transparent 50%);"></div>
            <div style="position:relative;z-index:1;">
                <div class="d-flex align-items-center gap-2 mb-5" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:10px 16px;width:fit-content;">
                    <div style="width:28px;height:28px;background:rgba(99,102,241,0.3);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-building" style="color:#a5b4fc;font-size:0.85rem;"></i>
                    </div>
                    <span style="color:#e2e8f0;font-weight:600;font-size:0.95rem;">Constructora OS</span>
                </div>
                <h1 style="color:white;font-size:2.2rem;font-weight:800;line-height:1.2;margin-bottom:16px;">
                    Gestión Integral<br>de Construcción
                </h1>
                <p style="color:#94a3b8;font-size:0.95rem;line-height:1.6;">
                    Plataforma empresarial para coordinar proyectos, costos y operaciones con trazabilidad completa.
                </p>
            </div>
            <p style="position:relative;z-index:1;color:#475569;font-size:0.78rem;">© {{ now()->year }} Constructora. Todos los derechos reservados.</p>
        </div>

        {{-- Panel derecho (formulario) --}}
        <div class="d-flex align-items-center justify-content-center p-4 p-md-5" style="width:100%;background:#0f172a;">
            <div style="width:100%;max-width:380px;">
                <div class="mb-4">
                    <h2 style="color:white;font-size:1.6rem;font-weight:700;margin-bottom:6px;">Bienvenido de nuevo</h2>
                    <p style="color:#64748b;font-size:0.85rem;margin:0;">Ingrese sus credenciales para acceder al panel.</p>
                </div>

                @if ($errors->any())
                    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);border-radius:12px;padding:12px 16px;margin-bottom:20px;">
                        @foreach ($errors->all() as $error)
                            <div style="color:#fca5a5;font-size:0.85rem;">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label style="color:#cbd5e1;font-size:0.82rem;font-weight:500;margin-bottom:6px;display:block;">Usuario o correo</label>
                        <div style="display:flex;align-items:center;background:rgba(15,23,42,0.8);border:1px solid #334155;border-radius:10px;padding:0 14px;transition:border-color 0.2s;" onfocus="this.style.borderColor='#6366f1'" class="login-input-group">
                            <i class="fas fa-user" style="color:#475569;font-size:0.85rem;"></i>
                            <input type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username"
                                style="width:100%;background:transparent;border:none;outline:none;padding:12px 10px;color:#e2e8f0;font-size:0.88rem;"
                                placeholder="usuario o correo"
                                onfocus="this.parentElement.style.borderColor='#6366f1';this.parentElement.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                                onblur="this.parentElement.style.borderColor='#334155';this.parentElement.style.boxShadow='none'">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label style="color:#cbd5e1;font-size:0.82rem;font-weight:500;margin-bottom:6px;display:block;">Contraseña</label>
                        <div style="display:flex;align-items:center;background:rgba(15,23,42,0.8);border:1px solid #334155;border-radius:10px;padding:0 14px;" class="login-input-group">
                            <i class="fas fa-lock" style="color:#475569;font-size:0.85rem;"></i>
                            <input type="password" name="password" required autocomplete="current-password"
                                style="width:100%;background:transparent;border:none;outline:none;padding:12px 10px;color:#e2e8f0;font-size:0.88rem;"
                                placeholder="••••••••"
                                onfocus="this.parentElement.style.borderColor='#6366f1';this.parentElement.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                                onblur="this.parentElement.style.borderColor='#334155';this.parentElement.style.boxShadow='none'">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;color:#64748b;font-size:0.82rem;">
                            <input type="checkbox" name="remember" value="1" style="accent-color:#6366f1;width:16px;height:16px;">
                            Mantener sesión iniciada
                        </label>
                    </div>

                    <button type="submit" style="width:100%;background:#6366f1;color:white;border:none;border-radius:10px;padding:13px 20px;font-size:0.9rem;font-weight:600;cursor:pointer;transition:all 0.2s;box-shadow:0 4px 14px rgba(99,102,241,0.35);display:flex;align-items:center;justify-content:center;gap:8px;"
                        onmouseover="this.style.background='#4f46e5';this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(99,102,241,0.45)'"
                        onmouseout="this.style.background='#6366f1';this.style.transform='none';this.style.boxShadow='0 4px 14px rgba(99,102,241,0.35)'">
                        Ingresar al sistema
                        <i class="fas fa-arrow-right" style="font-size:0.8rem;"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
