@extends('layouts.store')

@section('estilos')
{{--<link rel="stylesheet" type="text/css" href="{{ asset('asset/plugins/flexslider/flexslider.css') }}">--}}

<link rel="stylesheet" type="text/css" href="{{ asset('asset/styles/product.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('asset/styles/product_responsive.css') }}">
@endsection

@section('content')

<div class="super_container_inner">
{{--<div id="product_cart">--}}
    <div id="">
    {{--<div class="super_container_inner">--}}
        <div class="super_overlay"></div>
    
        <!-- Home -->
    
        <div class="home">
            <div class="home_container d-flex flex-column align-items-center justify-content-end">
                <div class="home_content text-center">
                    <div class="home_title">Características del Producto </div>
                    <div class="breadcrumbs d-flex flex-column align-items-center justify-content-center">
                        <ul class="d-flex flex-row align-items-start justify-content-start text-center">
                        {{-- <li><a href="{{ route('home')}}">Inicio</a></li>
                            <li><a href="" @click.prevent="setSubcategoria({{strtolower($producto->tipo->subcategoria->categoria->nombre)}})">{{ $producto->tipo->subcategoria->categoria->nombre }}</a></li>
                            <li><a href="" @click.prevent="setSubcategoria({{$producto->tipo->id}})">{{ Str::title($producto->tipo->nombre) }}</a></li>
                            @if ($producto->estado == 1)
                            <li><a href="" @click.prevent="setCategoria('nuevos')">Nuevos Productos</a></li>
                            @endif 
                            @if ($producto->estado == 2)
                            <li><a href="" @click.prevent="setCategoria('ofertas')">Ofertas</a></li>
                            @endif 
                        </ul> --}}
                        <li><a href="{{ route('home')}}">Inicio</a></li>
                            <li><a href="" @click.prevent="setSubcategoria({{strtolower($producto->producto->tipo->subcategoria->categoria->nombre)}})">{{ $producto->producto->tipo->subcategoria->categoria->nombre }}</a></li>
                            <li><a href="" @click.prevent="setSubcategoria({{$producto->producto->tipo->id}})">{{ Str::title($producto->producto->tipo->nombre) }}</a></li>
                            @if ($producto->producto->estado == 1)
                                <li><a href="" @click.prevent="setCategoria('nuevos')">Nuevos Productos</a></li>
                            @endif 
                            @if ($producto->producto->estado == 2)
                                <li><a href="" @click.prevent="setCategoria('ofertas')">Ofertas</a></li>
                            @endif 
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Product -->
    
        <div class="product">
            <div class="container">
                <div class="row">
    
                    <!-- Product Image -->
                    <div class="col-lg-6">
                        <div class="product_image_slider_container">
                            {{-- @if ($producto->estado==1)<span class="badge-new"><b>Nuevo</b></span>@endif 
                            @if ($producto->porcentaje_descuento>0)<span class="badge-offer">
                            <b> - {{$producto->porcentaje_descuento}}%</b></span>@endif 
                            <div id="slider" class="flexslider">
                                <ul class="slides">
                                    @foreach(\App\Imagene::where('imageable_type', 'App\ColorProducto')
                                    ->where('imageable_id', $producto->cop)->pluck('url', 'id') as $id => $imagen) 
                                    <li>
                                        <img src="{{ url('storage/' . $imagen) }}" alt="">
                                    </li>   
                                    @endforeach
                                </ul>
                            </div> --}}

                            @if ($producto->producto->estado==1)<span class="badge-new"><b>Nuevo</b></span>@endif 
                            @if ($producto->producto->porcentaje_descuento>0)<span class="badge-offer">
                            <b> - {{$producto->producto->porcentaje_descuento}}%</b></span>@endif 
                            <div id="slider" class="flexslider">
                                <ul class="slides">
                                    @foreach ($producto->imagenes as $item)
                                    <li>
                                        <img src="{{ url('storage/' . $item->url) }}" alt="">
                                        {{--<img src="{{ $imagen }}" alt="">--}}
                                    </li> 
                                    @endforeach  
                                </ul>
                            </div>
                            <div class="carousel_container">
                                <div id="carousel" class="flexslider">
                                    <ul class="slides">
                                        @foreach ($producto->imagenes as $item)
                                        <li>
                                            <div>
                                                <img src="{{ url('storage/' . $item->url) }}" alt="">
                                                {{--<img src="{{ $imagen }}" alt="">--}}
                                            </div>     
                                        </li>
                                        @endforeach   
                                    </ul>
                                </div>
                                <div class="fs_prev fs_nav disabled"><i class="fa fa-chevron-up" aria-hidden="true"></i>
                                </div>
                                <div class="fs_next fs_nav"><i class="fa fa-chevron-down" aria-hidden="true"></i></div>
                            </div>
                        </div>
                        
                    </div>
    
                    <!-- Product Info -->
                    {{-- <div class="col-lg-6 product_col">
                    <div class="product_info info" id="{{ $producto->id}}">
                            <div class="product_name">{{ $producto->nombre }} - {{ $producto->colores }}</div>
                            <div class="product_category">En <a href="" @click.prevent="setSubcategoria({{$producto->tipo->id}})">{{$producto->tipo->nombre}}</a></div>
    
                            <div class="product_price">${{ floatval($producto->precio_actual)}}<del class="price-old"> ${{ floatval($producto->precio_anterior)}}</del></div>
    
                            <div class="product_text">
                                <p>{!! $producto->descripcion_corta !!}</p>
                            </div> --}}

                    <div class="col-lg-6 product_col">
                        <div class="product_info info" id="{{ $producto->producto->id}}">
                            <div class="product_name">{{ $producto->producto->nombre }} - {{ $producto->color->nombre }}</div>
                            <div class="product_category">En <a href="" @click.prevent="setSubcategoria({{$producto->producto->tipo->id}})">{{$producto->producto->tipo->nombre}}</a></div>
    
                            <div class="product_price">${{ floatval($producto->producto->precio_actual)}}<del class="price-old"> ${{ floatval($producto->producto->precio_anterior)}}</del></div>
    
                            <div class="product_text">
                                <p>{!! $producto->producto->descripcion_corta !!}</p>
                            </div>
                
                            {{-- <product producto="{{$producto->cop}}"></product> --}}
                            <product :user_id="{{  auth()->user() ? json_encode(auth()->id()) : '0' }}" :producto="{{$producto->id}}" :ruta="ruta"></product>
                            {{--<div class="product_text">
                                <span style="color: black; font-weight: bold;">Talla</span>
                                <select name="talla" id="talla" class="form-control" v-model="talla" @change="setStock()" @click="change()">
                                    <option value="0" selected>Seleccione una talla</option>
                                    <option v-for="talla in arrayTallas" :key="talla.id" :value="talla.id" v-text="select ? talla.nombre : talla.nombre  + ' unidades disponibles: ' + talla.stock"></option>
                                </select>
                            </div>

                            <div class="product_text">
                                <span style="color: black; font-weight: bold;" value="{{ $producto->cop}}" class="producto">Cantidad</span>
                                <input class="form-number form-control" type="number" id="cantidad" name="cantidad"
                                    value="1" step="1" min="0" v-model="cantidad">
                                    <span style="color: rgb(243, 61, 61); font-weight: bold;" v-text="stock >= cantidad ? '' : 'Puedes agregar máximo ' + stock + ' unidades a tu carrito!'" 
                                    ></span>
                            </div>
                           
                            <div class="product_buttons">
                                <div class="text-right d-flex flex-row align-items-start justify-content-start">
                                    <div
                                        class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center" id="cart" v-on:click.prevent="getCarrito()">
                                        <div>
                                            <div><img src="{{ asset('asset/images/cart.svg') }}" class="svg" alt="">
                                                <div>+</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>--}}

                            
                            <h4 style="margin-top: 18px">Otros colores disponibles:</h4>
                            <div class="btn-group">
                                @foreach ($related_products as $related)
                                    <div class="mx-1">
                                        <a href="{{route('productos.show', $related->slug)}}">
                                            <img src="{{ url('storage/' . $related->imagenes[0]->url) }}" alt="" 
                                                style="height: 80px; width: 80px;" class="">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                           
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <!--tab info -->
        <div class="tabinfo">
            <div class="container">
    
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#descripcion" class="nav-link active" data-toggle="tab">Descripción</a>
                    </li>
                    <li class="nav-item">
                        <a href="#especificaciones" class="nav-link" data-toggle="tab">Especificaciones</a>
                    </li>
                    <li class="nav-item">
                        <a href="#datosinteres" class="nav-link" data-toggle="tab">Datos de interés</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="descripcion">
    
                        <h3>Descripción</h3>
                        <p>
                        </p>
                        <div class="field field--name-body field--type-text-with-summary field--label-hidden field--item">
                            <p>varios colores</p>
    
                            <p>En stock.<br>
                                {{-- <p>{!!$producto->descripcion_larga!!}</p> --}}
                                <p>{!!$producto->producto->descripcion_larga!!}</p>
                            </p>
                        </div>
    
                    </div>
                    <div class="tab-pane fade" id="especificaciones">
                        <h3>Especificaciones</h3>
                        <ol>
                            {{-- {!!$producto->especificaciones!!} --}}
                            {!!$producto->producto->especificaciones!!}
                        </ol>
                    </div>
                    <div class="tab-pane fade" id="datosinteres">
                        <p>Otros datos de interes</p>
                    </div>
                </div>
    
            </div>
    
        </div>
    
        <!-- Boxes -->
    
        <div class="boxes">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="box d-flex flex-row align-items-center justify-content-start">
                            <div class="mt-auto">
                                <div class="box_image"><img src="{{ asset('asset/images/boxes_1.png') }}" alt=""></div>
                            </div>
                            <div class="box_content">
                                <div class="box_title">Guía de Tamaños</div>
                                <div class="box_text">Conoce las tallas de nuestros productos.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 box_col">
                        <div class="box d-flex flex-row align-items-center justify-content-start">
                            <div class="mt-auto">
                                <div class="box_image"><img src="{{ asset('asset/images/boxes_2.png') }}" alt=""></div>
                            </div>
                            <div class="box_content">
                                <div class="box_title">Envío</div>
                                {{-- <div class="box_text">Envío gratis por pedidos desde $.</div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
</div>

@endsection

@section('scripts')
    {{-- <script>
    window.data = {
        datos: {
            "producto": "{{$producto->cop}}",
        }
    }
    </script> --}}
    {{--<script src="{{ asset('asset/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
    <script src="{{ asset('asset/plugins/flexslider/jquery.flexslider-min.js') }}"></script>--}}
    {{-- <script src="{{ asset('asset/plugins/bootstrap-4.1.2/popper.js') }}"></script> --}}
    {{-- <script src="{{ asset('asset/plugins/bootstrap-4.1.2/bootstrap.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('asset/js/bootbox.min.js') }}"></script> --}}
    <script src="{{ asset('asset/js/product.js') }}"></script>
@endsection




   
    
    