@if ($colors_count > 0)

    <td> 
        <a class="btn btn-default btn-sm btn-icon" href="{{ route('product.show', $id) }}" title="ver producto">
            <i class="fas fa-eye"></i>
        </a>
    </td>

    <td> 
        <a class="btn btn-info btn-sm btn-icon" href="{{ route('product.edit', $id) }}" title="editar">
            <i class="fas fa-pen"></i>
        </a>
    </td>

    <td> 
        <a href="{{ route('product.colors', $id) }}" class="btn btn-success btn-sm btn-icon" title="ver todos los colores">
            <i class="bi bi-palette"></i>
        </a>
    </td>

@endif