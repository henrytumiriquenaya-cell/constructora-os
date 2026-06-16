<div class="table-wrapper">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-head-premium">
                <tr>
                    <th>#</th>
                    <th>Código</th>
                    <th>Nombre del Material</th>
                    <th>Categoría</th>
                    <th class="text-center">U. Medida</th>
                    <th class="text-end">Precio Ref.</th>
                    <th class="text-center">Stock Mín.</th>
                    <th>Descripción</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materiales as $m)
                <tr>
                    <td class="text-muted small">{{ $m->id_material }}</td>
                    <td><span class="badge bg-light text-dark fw-mono">{{ $m->codigo_interno }}</span></td>
                    <td class="fw-semibold">{{ $m->nombre }}</td>
                    <td><span class="badge bg-secondary">{{ $m->categoria }}</span></td>
                    <td class="text-center"><span class="badge bg-info text-dark">{{ $m->unidad_medida }}</span></td>
                    <td class="text-end">${{ number_format($m->precio_unitario_ref, 2) }}</td>
                    <td class="text-center">{{ number_format($m->stock_minimo, 2) }}</td>
                    <td class="text-muted small">{{ Str::limit($m->descripcion ?? '—', 40) }}</td>
                    <td class="text-center">
                        <a href="{{ route('operativa.materiales.edit', $m->id_material) }}" class="btn btn-sm btn-warning" title="Editar">
                            <i class="ti ti-edit"></i>
                        </a>
                        <form action="{{ route('operativa.materiales.destroy', $m->id_material) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar {{ addslashes($m->nombre) }}?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Eliminar">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4">No hay materiales registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center py-3">
        {{ $materiales->links() }}
    </div>
</div>
