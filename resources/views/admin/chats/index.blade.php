@extends('layouts.admin')

@section('titulo', 'Chats')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('content')

{{-- <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sección de chats</h3>
                <div class="card-tools">
                    <form>
                        <div class="input-group input-group-sm" style="width: 160px;">
                            <div class="input-group-append">
                                <a href="" class="btn btn-success mx-1" v-on:click.prevent="">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>
                            <input type="text" name="keyword" class="form-control float-right" placeholder="Buscar"
                            value="{{ request()->get('keyword') }}">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 300px;">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Imagen</th>
                            <th>Fecha de inicio</th>
                            <th>Ultimo mensaje</th>
                            <th colspan="2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($chats as $chat)

                        <tr>
                            <td> {{$chat->id }} </td>
                            <td> <a href="{{ route('cliente.show', $chat->cliente)}}">{{$chat->nombres }} {{$chat->apellidos }}</a> </td>
                            <td> <img style="height: 40px; width: 40px;"
                                src="{{ url('storage/' . $chat->url) }}"
                                class="rounded-circle">
                            </td>
                            <td> {{ date('d/m/Y h:i:s A', strtotime($chat->fecha)) }} </td>
                            <td> {{$chat->mensaje }} </td>

                            <td> <a class="btn btn-primary" href="" title="ver chat"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                {{ $chats->appends($_GET)->links() }}
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    
<!-- /.row -->
</div> --}}

<div id="app" class="">
    <chat :user="{{  auth()->user()->id }}" :ruta="ruta"></chat>
</div>

@endsection