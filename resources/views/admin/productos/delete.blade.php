{{-- @if ($producto->activo == 'Si')
    <form action="{{ route('product.destroy', $producto->cop) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger text-white"  title="desactivar producto">
            <i class="fas fa-trash"></i>
        </button>
    </form>
@else

<form action="{{ route('product.activate', $producto->cop) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-success text-white"  title="activar producto">
        <i class="fas fa-check"></i>
    </button>
</form>

@endif --}}

@if ($producto->activo == 'Si')
    <form action="{{ route('product.destroy', $producto->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger text-white"  title="desactivar producto">
            <i class="fas fa-trash"></i>
        </button>
    </form>
@else

<form action="{{ route('product.activate', $producto->id) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-success text-white"  title="activar producto">
        <i class="fas fa-check"></i>
    </button>
</form>

@endif