<form action="{{ route('subcategory.destroy',  $subcategoria->id) }}" method="POST">
	{{-- route('subcategory.destroy',  $subcategoria) --}}
	@csrf
	@method('DELETE')
	<button type="submit" class="btn btn-danger text-white btn-sm btn-icon"  title="eliminar categoría">
		<i class="fas fa-trash"></i>
	</button>
</form>