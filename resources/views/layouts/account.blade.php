<!DOCTYPE html>
<html lang="en">
  
@include('partials.account.head')

<body class="hold-transition layout-top-nav">
<div class="wrapper" id="app">

  @include('partials.account.navbar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            @yield('title')
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('users.show', auth()->user()->slug)}}">Inicio</a></li>
              @yield('breadcumb')
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    @if(session('message'))
    <div class="col-md-8 mx-auto">
        <div class="alert alert-{{ session('message')[0] }} alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p>{{ session('message')[1] }}</p>
        </div>
    </div>
    @endif

      @yield('content')

      {{-- <div id="app" class=""> --}}
        <div class="row">
          <chat-store :ruta="ruta"></chat-store>
        </div>
      {{-- </div> --}}

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- ./wrapper -->


<!-- REQUIRED SCRIPTS -->



<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
{{-- <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('asset/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/app_admin.js') }}" defer></script>
{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}
@yield('scripts')

</body>
</html>

