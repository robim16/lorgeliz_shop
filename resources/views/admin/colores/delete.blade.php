<form action="{{ route('color.destroy',  $color->id) }}" method="POST"> 
	{{-- route('category.destroy',  $categoria) --}}
	@csrf
	@method('DELETE')
	<button type="submit" class="btn btn-danger text-white"  title="eliminar color">
		<i class="fas fa-trash"></i>
	</button>
</form>