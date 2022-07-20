<td>
    @if ($colors_count > 0)
        @foreach(\App\Imagene::where('imageable_type', 'App\ColorProducto')
            ->where('imageable_id', $colors[0]["pivot"]["id"])->pluck('url', 'id')->take(1) as $id => $imagen)    
            <img src="{{ url('storage/' . $imagen) }}" alt="" style="height: 50px; width: 50px;" class="rounded-circle">
            
        @endforeach
    @endif
</td>