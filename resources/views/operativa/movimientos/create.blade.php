@extends('layouts.app')

@section('title', 'Nuevo Movimiento')
@section('page_title', 'Nuevo Movimiento de Materiales')
@section('page_subtitle', 'Gestión Operativa · Registrar múltiples materiales')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:var(--text-primary);">
            <i class="ti ti-arrows-exchange me-2" style="color:var(--indigo);"></i>
            Nuevo Movimiento
        </h4>
        <small style="color:var(--text-muted);">Agregue uno o varios materiales en un solo registro</small>
    </div>
    <a href="{{ route('operativa.movimientos.index') }}" class="btn btn-secondary btn-sm">
        <i class="ti ti-arrow-left me-1"></i> Volver
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <strong><i class="ti ti-alert-circle me-1"></i>Corrige los siguientes errores:</strong>
        <ul class="mb-0 mt-2 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('operativa.movimientos.store') }}" method="POST" id="form-movimientos">
    @csrf

    {{-- Proyecto global (aplica a todos los materiales) --}}
    <div class="page-card mb-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="ti ti-building" style="color:var(--indigo); font-size:1.1rem;"></i>
            <span class="fw-semibold" style="color:var(--text-primary);">Destino (Proyecto)</span>
            <span style="color:var(--text-muted); font-size:0.82rem;">— aplica a todos los materiales</span>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <select name="id_proyecto_global" id="id_proyecto_global" class="form-select">
                    <option value="">Sin proyecto asignado</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id_proyecto }}">
                            {{ $p->nombre_proyecto }}
                            @if($p->codigo_proyecto) — {{ $p->codigo_proyecto }} @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="tipo_global" id="tipo_global" class="form-select">
                    <option value="entrada">📥 Entrada (Ingreso al stock)</option>
                    <option value="salida" selected>📤 Salida (Consumo / Uso)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="button" id="btn-aplicar-global" class="btn btn-secondary w-100" onclick="aplicarGlobal()">
                    <i class="ti ti-refresh me-1"></i> Aplicar a todas las filas
                </button>
            </div>
        </div>
    </div>

    {{-- Tabla de materiales --}}
    <div class="page-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="ti ti-list" style="color:var(--indigo); font-size:1.1rem;"></i>
                <span class="fw-semibold" style="color:var(--text-primary);">Lista de Materiales</span>
                <span class="badge badge-status badge-en_ejecucion" id="count-badge">1 material</span>
            </div>
            <button type="button" class="btn btn-primary btn-sm" onclick="agregarFila()">
                <i class="ti ti-plus me-1"></i> Agregar material
            </button>
        </div>

        <div class="table-wrapper">
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="tabla-materiales">
                    <thead class="table-head-premium">
                        <tr>
                            <th style="width:30%;">Material *</th>
                            <th style="width:15%;">Cantidad *</th>
                            <th style="width:12%;">Tipo *</th>
                            <th style="width:20%;">Proyecto</th>
                            <th style="width:17%;">Descripción (auto)</th>
                            <th style="width:6%;" class="text-center">Quitar</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-materiales">
                        {{-- Fila inicial --}}
                        <tr class="fila-material" data-index="0">
                            <td>
                                <select name="items[0][id_material]" class="form-select form-select-sm sel-material" onchange="autocomplete(this)">
                                    <option value="">— Seleccionar —</option>
                                    @foreach($materiales as $mat)
                                        <option value="{{ $mat->id_material }}"
                                                data-desc="{{ $mat->descripcion ?? '' }}"
                                                data-stock="{{ $mat->cantidad ?? 0 }}"
                                                data-unidad="{{ $mat->unidad_medida ?? '' }}">
                                            {{ $mat->nombre }}{{ $mat->unidad_medida ? ' ('.$mat->unidad_medida.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="stock-info d-none mt-1" style="font-size:0.72rem;"></small>
                            </td>
                            <td>
                                <input type="number" name="items[0][cantidad]" class="form-control form-control-sm"
                                       step="0.0001" min="0.0001" placeholder="0.00">
                            </td>
                            <td>
                                <select name="items[0][tipo]" class="form-select form-select-sm sel-tipo">
                                    <option value="entrada">📥 Entrada</option>
                                    <option value="salida" selected>📤 Salida</option>
                                </select>
                            </td>
                            <td>
                                <select name="items[0][id_proyecto]" class="form-select form-select-sm sel-proyecto">
                                    <option value="">Sin proyecto</option>
                                    @foreach($proyectos as $p)
                                        <option value="{{ $p->id_proyecto }}">{{ $p->nombre_proyecto }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <textarea name="items[0][descripcion]" class="form-control form-control-sm desc-field"
                                          rows="1" readonly style="resize:none;border-style:dashed;font-size:0.78rem;"
                                          placeholder="Auto..."></textarea>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger" onclick="quitarFila(this)" title="Quitar fila" disabled>
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer / Totales --}}
        <div class="d-flex justify-content-between align-items-center mt-3 pt-3" style="border-top:1px solid var(--card-border);">
            <div style="color:var(--text-muted); font-size:0.85rem;">
                <i class="ti ti-info-circle me-1"></i>
                Total de filas: <strong id="total-filas" style="color:var(--indigo);">1</strong>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('operativa.movimientos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i> Guardar todos los movimientos
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
// ─── Datos de materiales (inyectados por Laravel) ──────────────────────────
const MATERIALES_DATA = {
    @foreach($materiales as $mat)
    "{{ $mat->id_material }}": {
        desc:   @json($mat->descripcion ?? ''),
        stock:  {{ $mat->cantidad ?? 0 }},
        unidad: @json($mat->unidad_medida ?? '')
    },
    @endforeach
};

const PROYECTOS_OPTS = `
    <option value="">Sin proyecto</option>
    @foreach($proyectos as $p)
    <option value="{{ $p->id_proyecto }}">{{ $p->nombre_proyecto }}</option>
    @endforeach
`;

let idx = 0;

// ─── Autocompletar descripción + mostrar stock ─────────────────────────────
function autocomplete(selectEl) {
    const row   = selectEl.closest('tr');
    const matId = selectEl.value;
    const desc  = row.querySelector('.desc-field');
    const info  = row.querySelector('.stock-info');

    if (!matId) {
        desc.value = '';
        info.classList.add('d-none');
        return;
    }

    const data = MATERIALES_DATA[matId];
    desc.value = data.desc || '(Sin descripción)';

    // Mostrar stock
    const stock = parseFloat(data.stock) || 0;
    info.classList.remove('d-none');
    const color = stock <= 0 ? '#f87171' : (stock < 5 ? '#fbbf24' : '#34d399');
    info.innerHTML = `<span style="color:${color};font-weight:600;">Stock: ${stock.toLocaleString('es-BO',{maximumFractionDigits:2})} ${data.unidad}</span>`;
}

// ─── Agregar nueva fila ────────────────────────────────────────────────────
function agregarFila() {
    idx++;
    const tbody = document.getElementById('tbody-materiales');

    const materialesOpts = Object.entries(MATERIALES_DATA).map(([id]) => {
        // We need the name from the existing select
        const existingOpt = document.querySelector(`#tabla-materiales select.sel-material option[value="${id}"]`);
        const text = existingOpt ? existingOpt.textContent.trim() : id;
        return `<option value="${id}">${text}</option>`;
    });

    const tipoSelect = document.getElementById('tipo_global');
    const proyGlobal = document.getElementById('id_proyecto_global');

    // Clonar opciones de materiales del primer select
    const firstSel = document.querySelector('.sel-material');
    const optsHTML = Array.from(firstSel.options).map(o =>
        `<option value="${o.value}" data-desc="${o.dataset.desc||''}" data-stock="${o.dataset.stock||0}" data-unidad="${o.dataset.unidad||''}">${o.textContent.trim()}</option>`
    ).join('');

    const proyOpts = Array.from(document.querySelector('.sel-proyecto').options).map(o =>
        `<option value="${o.value}" ${o.value == proyGlobal.value ? 'selected' : ''}>${o.textContent.trim()}</option>`
    ).join('');

    const selectedTipo = tipoSelect.value;

    const tr = document.createElement('tr');
    tr.className = 'fila-material';
    tr.dataset.index = idx;
    tr.innerHTML = `
        <td>
            <select name="items[${idx}][id_material]" class="form-select form-select-sm sel-material" onchange="autocomplete(this)">
                ${optsHTML}
            </select>
            <small class="stock-info d-none mt-1" style="font-size:0.72rem;"></small>
        </td>
        <td>
            <input type="number" name="items[${idx}][cantidad]" class="form-control form-control-sm"
                   step="0.0001" min="0.0001" placeholder="0.00">
        </td>
        <td>
            <select name="items[${idx}][tipo]" class="form-select form-select-sm sel-tipo">
                <option value="entrada" ${selectedTipo==='entrada'?'selected':''}>📥 Entrada</option>
                <option value="salida"  ${selectedTipo==='salida' ?'selected':''}>📤 Salida</option>
            </select>
        </td>
        <td>
            <select name="items[${idx}][id_proyecto]" class="form-select form-select-sm sel-proyecto">
                ${proyOpts}
            </select>
        </td>
        <td>
            <textarea name="items[${idx}][descripcion]" class="form-control form-control-sm desc-field"
                      rows="1" readonly style="resize:none;border-style:dashed;font-size:0.78rem;"
                      placeholder="Auto..."></textarea>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="quitarFila(this)" title="Quitar fila">
                <i class="ti ti-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    actualizarContador();
}

// ─── Quitar fila ───────────────────────────────────────────────────────────
function quitarFila(btn) {
    const tbody = document.getElementById('tbody-materiales');
    if (tbody.querySelectorAll('tr').length <= 1) return; // mantener mínimo 1
    btn.closest('tr').remove();
    actualizarContador();
}

// ─── Actualizar contador ───────────────────────────────────────────────────
function actualizarContador() {
    const n = document.querySelectorAll('#tbody-materiales tr').length;
    document.getElementById('total-filas').textContent = n;
    document.getElementById('count-badge').textContent = `${n} material${n>1?'es':''}`;

    // Habilitar/deshabilitar botones de quitar
    const btns = document.querySelectorAll('#tbody-materiales .btn-danger');
    btns.forEach(b => b.disabled = n <= 1);
}

// ─── Aplicar proyecto y tipo globales a todas las filas ───────────────────
function aplicarGlobal() {
    const proy = document.getElementById('id_proyecto_global').value;
    const tipo = document.getElementById('tipo_global').value;

    document.querySelectorAll('.sel-proyecto').forEach(s => s.value = proy);
    document.querySelectorAll('.sel-tipo').forEach(s => s.value = tipo);

    // Feedback visual
    const btn = document.getElementById('btn-aplicar-global');
    btn.textContent = '✓ Aplicado';
    btn.classList.add('btn-primary');
    btn.classList.remove('btn-secondary');
    setTimeout(() => {
        btn.innerHTML = '<i class="ti ti-refresh me-1"></i> Aplicar a todas las filas';
        btn.classList.add('btn-secondary');
        btn.classList.remove('btn-primary');
    }, 1500);
}

// ─── Validación antes de enviar ────────────────────────────────────────────
document.getElementById('form-movimientos').addEventListener('submit', function(e) {
    const filas = document.querySelectorAll('#tbody-materiales tr');
    let valido = true;
    filas.forEach(tr => {
        const mat = tr.querySelector('.sel-material').value;
        const cant = tr.querySelector('input[type=number]').value;
        if (!mat || !cant || parseFloat(cant) <= 0) {
            valido = false;
            tr.style.outline = '2px solid #f87171';
            tr.style.borderRadius = '4px';
        } else {
            tr.style.outline = '';
        }
    });
    if (!valido) {
        e.preventDefault();
        alert('Por favor completa el material y la cantidad en todas las filas marcadas en rojo.');
    }
});

// Init
document.addEventListener('DOMContentLoaded', actualizarContador);
</script>
@endpush
