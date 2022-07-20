<!DOCTYPE html>
<html>
  
@include('partials.admin.head')

<body class="hold-transition sidebar-mini">
  <!-- Site wrapper -->
  <div class="wrapper">

   @include('partials.admin.navbar')

   @include('partials.admin.aside')
   

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>@yield('titulo')</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin')}}">Inicio</a></li>
                @yield('breadcrumb')
              </ol>
              
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        @if(session('message'))

        <div class="alert alert-{{ session('message')[0] }} alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p>{{ session('message')[1] }}</p>
        </div>

        @endif

        @yield('content')

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('partials.admin.footer')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
  {{-- <!-- Bootstrap 4 -->
  <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
  <!-- AdminLTE App -->
  <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{ asset('adminlte/dist/js/demo.js') }}"></script>
  {{-- <script src="{{ asset('js/bootstrap-select.min.js') }}"></script> --}}
  {{-- <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script> --}}
  <!-- Ekko Lightbox -->
  {{-- <script src="{{ asset('adminlte/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script> --}}

  {{-- <script src="{{ asset('js/plugins.js') }}" defer></script> --}}
  <script src="{{ asset('js/app_admin.js') }}" defer></script>

  {{--<script src="{{ asset('js/app.js') }}" defer></script>--}}

  @yield('scripts')

</body>
</html>
