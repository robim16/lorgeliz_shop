<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-2">Informe de clientes que más compran</h3>

        <div class="card-tools">
            <form>
                <div class="input-group input-group-sm">

                    <input type="text" name="busqueda" class="form-control float-right" placeholder="Buscar"
                        wire:model="busqueda">

                    <div class="input-group-append">
                        {{-- <button type="submit" class="btn btn-success" wire:click="">
                            <i class="fas fa-search"></i>
                        </button> --}}
                        <a href="" class="btn btn-warning mx-1" @click.prevent="pdfInformeClientes"><i
                                class="fas fa-print"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive p-0">
        <table class="table table-head-fixed">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Imagen</th>
                    <th scope="col">Telefono</th>
                    <th scope="col">Email</th>
                    <th scope="col">Compras</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($this->ventas as $venta)
                    <tr>

                        <td>{{ $venta->cliente->user->id }}</td>
                        <td>
                            <a href="{{ route('cliente.show', $venta->cliente->id) }}" class="text-primary">
                                {{ $venta->cliente->user->nombres }} {{ $venta->cliente->user->apellidos }}</a>
                        </td>
                        <td>
                            <img src="{{ url('storage/' . $venta->cliente->user->imagene->url) }}" alt=""
                                style="height: 50px; width: 50px;" class="rounded-circle">
                        </td>
                        <td>{{ $venta->cliente->user->telefono }}</td>
                        <td>{{ $venta->cliente->user->email }}</td>
                        <td>{{ $venta->cantidad }}</td>
                        <td>
                            <a href="{{ route('cliente.show', $venta->cliente->id) }}"
                                class="btn btn-primary btn-sm btn-icon" title="compras del cliente">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        {{ $this->ventas->appends($_GET)->links() }}
    </div>
    <!-- /.card-body -->
</div>
