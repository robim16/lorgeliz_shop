@extends('layouts.store')

@section('estilos')
{{--<link rel="stylesheet" type="text/css" href="{{ asset('asset/plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('asset/plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('asset/plugins/OwlCarousel2-2.2.1/animate.css') }}">--}}


<link rel="stylesheet" type="text/css" href="{{ asset('asset/styles/main_styles.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('asset/styles/responsive.css') }}">
@endsection

@section('content')

{{--<div class="super_container_inner" id="inicio">--}}
    <div class="super_container_inner" id="">
    <div class="super_overlay"></div>


    <!-- Home -->
    
    {{--<div class="background_image" style="background-image:url({{ $producto->imagen }})">--}}
    {{--<img src="{{ $producto->imagen }}" alt="">--}}

    <div class="home">
        <slider :productos="{{$productoSlider}}"></slider>
    </div>

    {{-- <div class="home">
        <div class="home_slider_container">
            <div class="owl-carousel owl-theme home_slider">
                
               @foreach ($productoSlider as $producto)
                <div class="owl-item">
                    <div class="background_image" style="background-image:url({{ url('storage/' . $producto->imagenes[0]->url) }})">
                       
                   </div>
                    <div class="container fill_height">
                        <div class="row fill_height">
                            <div class="col fill_height">
                                <div class="home_container d-flex flex-column align-items-center justify-content-start">
                                    <div class="home_content">
                                        <div class="home_title">Nuevos Artículos</div>
                                        <div class="home_subtitle">Summer Wear</div>
                                        <div class="home_items">
                                            <div class="row">
                                                <div class="col-sm-3 offset-lg-1">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-8 offset-sm-2 offset-md-0">
                                                    <div class="product home_item_large">
                                                        <div
                                                            class="product_tag d-flex flex-column align-items-center justify-content-center">
                                                            <div>
                                                                <div>desde</div>
                                                                <div style="font-size: 25px">${{ floatval($producto->producto->precio_actual)}}<span></span>
                                                                </div>
                                                                <del class="price-oldslider">@if ($producto->producto->precio_anterior > $producto->producto->precio_actual)
                                                                ${{ floatval($producto->producto->precio_anterior)}} 
                                                                @endif<span></span></del>
                                                            </div>
                                                        </div>
                                                        <div class="product_image">
                                                            <a href="{{route('productos.show', $producto->slug)}}">
                                                                <img src="{{ url('storage/' . $producto->imagenes[0]->url) }}" alt="">
                                                            </a>
                                                        </div>
                                                        <div class="product_content">
                                                            <div class="product_buttons">
                                                                <div
                                                                    class="text-right d-flex flex-row align-items-start justify-content-start">
                                                                    <div
                                                                        class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
                                                                        <div>
                                                                            <div><img
                                                                                    src="{{ asset('asset/images/cart_2.svg') }}"
                                                                                    alt="">
                                                                                <div>+</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="home_slider_nav home_slider_nav_prev"><i class="fa fa-chevron-left" aria-hidden="true"></i>
            </div>
            <div class="home_slider_nav home_slider_nav_next"><i class="fa fa-chevron-right" aria-hidden="true"></i>
            </div>

            <div class="home_slider_dots_container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="home_slider_dots">
                                <ul id="home_slider_custom_dots"
                                    class="home_slider_custom_dots d-flex flex-row align-items-center justify-content-center">
                                    <li class="home_slider_custom_dot active">01</li>
                                    <li class="home_slider_custom_dot">02</li>
                                    <li class="home_slider_custom_dot">03</li>
                                    <li class="home_slider_custom_dot">04</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div> --}}
    
    <!-- Products -->

    {{--<div class="products">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section_title text-center">Nuevos Productos en Lorgeliz Shop</div>
                </div>
            </div>
            <div class="row page_nav_row">
                <div class="col">
                    <div class="page_nav">
                        <ul class="d-flex flex-row align-items-start justify-content-center">
                            <li class="active"><a href="categorias?categoria=mujeres">Mujeres</a></li>
                            <li><a href="categorias?categoria=hombres">Hombres</a></li>
                            <li><a href="categorias?categoria=niños">Niños</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row products_row">
                @foreach ($nuevosproductos as $nuevo)
                <div class="col-xl-4 col-md-6">
                    <div class="product">
                        <div class="product_image">
                            <a href="{{route('productos.show', $nuevo->slug)}}">
                            <img src="{{ url('storage/' . $nuevo->imagen) }}" alt="">
                            
                            </a>
                        </div>
                        <div class="product_content">
                            <div class="product_info d-flex flex-row align-items-start justify-content-start">
                                <div>
                                    <div>
                                    <div class="product_name"><a href="{{ route('productos.show', $nuevo->slug)}}">{{    $nuevo->nombre}}-{{$nuevo->color}}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-auto text-right">
                                    <div class="product_category">En <a href="categorias?categoria=&subcategoria={{$nuevo->tipo_id}}">{{$nuevo->tipo}}</a></div>
                                    <div class="product_price text-right">${{ floatval($nuevo->precio_actual)}}<span></span></div>
                                </div>
                            </div>
                            <div class="product_buttons">
                                <div class="text-right d-flex flex-row align-items-start justify-content-start">
                                    <div
                                        class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
                                        <div>
                                            <div><a href="{{route('producto.show', $nuevo->slug)}}"><img
                                                    src="{{ asset('asset/images/cart.svg') }}" class="svg" alt="">
                                                </a>
                                                <div>+</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
                @endforeach
                
            </div>
            <div class="row load_more_row">
                <div class="col">
                    <div class="button load_more ml-auto mr-auto"><a href="#">cargar más</a></div>
                </div>
            </div>
        </div>
    </div>--}}

    {{--<div class="products">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section_title text-center">Nuevos Productos en Lorgeliz Shop</div>
                </div>
            </div>
            <div class="row page_nav_row">
                <div class="col">
                    <div class="page_nav">
                        <ul class="d-flex flex-row align-items-start justify-content-center">
                            <li class="active"><a href="categorias?categoria=mujeres">Mujeres</a></li>
                            <li><a href="categorias?categoria=hombres">Hombres</a></li>
                            <li><a href="categorias?categoria=niños">Niños</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row products_row">
               
                <div v-for="nuevo in productoNuevo" :key="nuevo.cop" class="col-xl-4 col-md-6">
                    <div class="product">
                        <div class="product_image">
                        <a :href="'product/' + nuevo.slug">
							<img :src="'storage/' + nuevo.imagen" alt="">
                            {{--<img :src="nuevo.imagen" alt="">--}}
						{{--</a>
                        </div>
                        <div class="product_content">
                            <div class="product_info d-flex flex-row align-items-start justify-content-start">
                                <div>
                                    <div>
                                        <div class="product_name"><a :href="'product/' + nuevo.slug">@{{ nuevo.nombre}}
                                                @{{ nuevo.color}}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-auto text-right">
                                    <div class="product_category">En <a href="">@{{ nuevo.tipo}}</a></div>
                                    <div class="product_price text-right">@{{ nuevo.precio_actual}}<span></span></div>
                                </div>
                            </div>
                            <div class="product_buttons">
                                <div class="text-right d-flex flex-row align-items-start justify-content-start">
                                    <div
                                        class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
                                        <div>
                                            <div><a :href="'product/' + nuevo.slug"><img src="asset/images/cart.svg" class="svg" alt="">
                                                </a>
                                                <div>+</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
                
            </div>
            <div class="row load_more_row">
                <div class="col">
                    <div class="button load_more ml-auto mr-auto"><a href="" v-on:click.prevent="getProductos()">cargar más</a></div>
                </div>
            </div>
        </div>
    </div>--}}
    
    <product-new></product-new>

    <!-- Lo mas visto -->

    {{-- <div class="lomasvendidocontenedor">
        <div class="section_title text-center">Lo más Visto</div>
        <br>
        <div class="lomasvendido owl-carousel owl-theme">

            @foreach ($producto_mas_visto as $producto)
            <div class="owl-item">
                <div class="product">
                    <div class="product_image">
                        <a href="{{route('productos.show', $producto->slug)}}">
                        <img src="{{ url('storage/' . $producto->imagenes[0]->url) }}" alt="">
                        
                        </a>
                    </div>
                    <div class="product_content">
                        <div class="product_info d-flex flex-row align-items-start justify-content-start">
                            <div>
                                <div>
                                    <div class="product_name"><a href="{{ route('productos.show', $producto->slug)}}">{{ $producto->producto->nombre}}-{{$producto->color->nombre}}</a></div>

                                </div>
                            </div>
                            <div class="ml-auto text-right">
                                <div class="product_category">En <a href="" @click.prevent="setSubcategoria({{$producto->producto->tipo->id}})">{{$producto->producto->tipo->nombre}}</a></div>
                                <div class="product_price text-right">${{ floatval($producto->producto->precio_actual)}}<span></span></div>
                            </div>
                        </div>
                        <div class="product_buttons">
                            <div class="text-right d-flex flex-row align-items-start justify-content-start">
                                <div
                                    class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
                                    <div>
                                        <div><a href="{{ route('producto.show', $producto->slug)}}">
                                                <img src="{{ asset('asset/images/cart.svg') }}" class="svg" alt=""></a>
                                            <div>+</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
    </div>  --}}
    <div class="lomasvendidocontenedor">
        <div class="section_title text-center">Lo más Visto</div>
        <br>
        <popular :productos="{{$producto_mas_visto}}"></popular>
    </div>
</div>

<br>

<br>

<br>

    <!-- Lo mas vendido -->

    {{-- <div class="lomasvendidocontenedor">
        <div class="section_title text-center">Lo más Vendido</div>
        <br>
        <div class="lomasvendido owl-carousel owl-theme">

            @foreach ($productos_vendidos as $producto)
                <div class="owl-item">
                    <div class="product">
                        <div class="product_image">
                            <a href="{{route('productos.show', $producto->slug)}}">
                            
                            <img src="{{ url('storage/' . $producto->imagenes[0]->url) }}" alt="">
                        
                            </a>
                        </div>
                        <div class="product_content">
                            <div class="product_info d-flex flex-row align-items-start justify-content-start">
                                <div>
                                    <div>
                                        <div class="product_name"><a href="{{ route('productos.show', $producto->slug)}}">{{ $producto->producto->nombre}}-{{$producto->color->nombre}}</a></div>
                                    </div>
                                </div>
                                <div class="ml-auto text-right">
                                    <div class="product_category">En <a href="" @click.prevent="setSubcategoria({{$producto->producto->tipo->id}})">{{$producto->producto->tipo->nombre}}</a></div>
                                    <div class="product_price text-right">${{ floatval($producto->producto->precio_actual)}}<span></span></div>
                                </div>
                            </div>
                            <div class="product_buttons">
                                <div class="text-right d-flex flex-row align-items-start justify-content-start">
                                    <div
                                        class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
                                        <div>
                                            <div><a href="{{ route('producto.show', $producto->slug)}}"><img src="{{ asset('asset/images/cart.svg') }}" class="svg" alt=""></a>
                                                <div>+</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
            
        </div>
    </div> --}}
    <div class="lomasvendidocontenedor">
        <div class="section_title text-center">Lo más Vendido</div>
        <br>
        <sale :productos="{{$productos_vendidos}}"></sale>
    </div>
</div>


<br>

<br>

<br>

    <!-- En oferta -->

    {{-- <div class="lomasvendidocontenedor">
        <div class="section_title text-center">En oferta</div>
        <br>
        <div class="lomasvendido owl-carousel owl-theme">
        
            @foreach ($productosoferta as $oferta)
                <div class="owl-item">
                <div class="product">
                <span class="badge-new"><b> Nuevo</b></span>
                <span class="badge-offer"><b> - {{ $oferta->producto->porcentaje_descuento}}%</b></span>
                    <div class="product_image">
                        <a href="{{route('productos.show', $oferta->slug)}}">
                        <img src="{{ url('storage/' . $oferta->imagenes[0]->url) }}" alt="">
                        
                        </a>
                    </div>
                    <div class="product_content">
                        <div class="product_info">
                            <div>
                                <div>
                                <div class="product_name product_namesinwidth text-center"><a href="{{ route('productos.show', $oferta->slug)}}">{{ $oferta->producto->nombre}}-{{$oferta->color->nombre}}</a></div>

                                </div>
                            </div>
                            <div class="ml-auto">
                                <div class="product_price text-center">${{ floatval($oferta->producto->precio_actual)}}<span></span>
                                    <del class="price-old">${{ floatval($oferta->producto->precio_anterior)}}</del>
                                </div>
                            </div>
                        </div>
                        <div class="product_buttons">
                            <div class="text-right d-flex flex-row align-items-start justify-content-start">
                                <div
                                    class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
                                    <div>
                                        <div><a href="{{ route('producto.show', $oferta->slug)}}">
                                                <img src="{{ asset('asset/images/cart.svg') }}" class="svg" alt="">
                                            </a>
                                            <div>+</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div> --}}
    <div class="lomasvendidocontenedor">
        <div class="section_title text-center">En oferta</div>
        <br>
        <offers :productos="{{$productosoferta}}"></offers>
    </div>
</div>

<br>

<br>

<br>


<!-- Boxes -->

<div class="boxes">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="boxes_container d-flex flex-row align-items-start justify-content-between flex-wrap">

                    <!-- Box -->
                    <div class="box">
                        <div class="background_image"
                            style="background-image:url({{ asset('asset/images/box_1.jpg') }})"></div>
                        <div class="box_content d-flex flex-row align-items-center justify-content-start">
                            <div class="box_left">
                                <div class="box_image">
                                    <a href="{{ route("categorias")}}">
                                        <div class="background_image"
                                            style="background-image:url({{ asset('asset/images/box_1_img.jpg') }})">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="box_right text-center">
                                <div class="box_title">Trendsetter Collection</div>
                            </div>
                        </div>
                    </div>

                    <!-- Box -->
                    <div class="box">
                        <div class="background_image"
                            style="background-image:url({{ asset('asset/images/box_2.jpg') }})"></div>
                        <div class="box_content d-flex flex-row align-items-center justify-content-start">
                            <div class="box_left">
                                <div class="box_image">
                                    <a href="{{ route("categorias")}}">
                                        <div class="background_image"
                                            style="background-image:url({{ asset('asset/images/box_2_img.jpg') }})">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="box_right text-center">
                                <div class="box_title">Popular Choice</div>
                            </div>
                        </div>
                    </div>

                    <!-- Box -->
                    <div class="box">
                        <div class="background_image"
                            style="background-image:url({{ asset('asset/images/box_3.jpg') }})"></div>
                        <div class="box_content d-flex flex-row align-items-center justify-content-start">
                            <div class="box_left">
                                <div class="box_image">
                                    <a href="{{ route("categorias")}}">
                                        <div class="background_image"
                                            style="background-image:url({{ asset('asset/images/box_3_img.jpg') }})">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="box_right text-center">
                                <div class="box_title">Popular Choice</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features -->

<div class="features">
    <div class="container">
        <div class="row">

            <!-- Feature -->
            <div class="col-lg-4 feature_col">
                <div class="feature d-flex flex-row align-items-start justify-content-start">
                    <div class="feature_left">
                        <div class="feature_icon"><img src="{{ asset('asset/images/icon_1.svg') }}" alt=""></div>
                    </div>
                    <div class="feature_right d-flex flex-column align-items-start justify-content-center">
                        <div class="feature_title">Pagos rápidos y seguros</div>
                    </div>
                </div>
            </div>

            <!-- Feature -->
            <div class="col-lg-4 feature_col">
                <div class="feature d-flex flex-row align-items-start justify-content-start">
                    <div class="feature_left">
                        <div class="feature_icon ml-auto mr-auto"><img src="{{ asset('asset/images/icon_2.svg') }}"
                                alt=""></div>
                    </div>
                    <div class="feature_right d-flex flex-column align-items-start justify-content-center">
                        <div class="feature_title">Productos de calidad</div>
                    </div>
                </div>
            </div>

            <!-- Feature -->
            <div class="col-lg-4 feature_col">
                <div class="feature d-flex flex-row align-items-start justify-content-start">
                    <div class="feature_left">
                        <div class="feature_icon"><img src="{{ asset('asset/images/icon_3.svg') }}" alt=""></div>
                    </div>
                    <div class="feature_right d-flex flex-column align-items-start justify-content-center">
                        <div class="feature_title">Entrega gratis después de $100</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

{{--@section('scripts')
<script src="{{ asset('asset/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
<script src="{{asset('asset/plugins/progressbar/progressbar.min.js') }}"></script>
<script src="{{asset('asset/js/custom.js') }}"></script>
@endsection--}}
