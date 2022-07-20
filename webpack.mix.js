const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

   mix.styles([
      'public/asset/plugins/OwlCarousel2-2.2.1/owl.carousel.css',
      'public/asset/plugins/OwlCarousel2-2.2.1/owl.theme.default.css',
      'public/asset/plugins/OwlCarousel2-2.2.1/animate.css',
      'public/asset/plugins/flexslider/flexslider.css',
      'public/asset/styles/comun.css'
  ], 'public/css/all.css');  

   mix.styles([
      // 'public/adminlte/plugins/fontawesome-free/css/all.min.css',
      'public/adminlte/dist/css/adminlte.min.css',
      'public/adminlte/plugins/select2/css/select2.min.css',
      //'public/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'
      'public/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css',
      'public/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css',
      'public/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css'
   ], 'public/css/styles.css');  

   mix.scripts([
      'public/asset/js/jquery-3.2.1.min.js',
      'public/asset/plugins/greensock/TweenMax.min.js',
      'public/asset/plugins/greensock/TimelineMax.min.js',
      'public/asset/plugins/scrollmagic/ScrollMagic.min.js',
      'public/asset/plugins/greensock/animation.gsap.min.js',
      'public/asset/plugins/greensock/ScrollToPlugin.min.js',
      'public/asset/plugins/easing/easing.js',
      'public/asset/plugins/OwlCarousel2-2.2.1/owl.carousel.js',
      'public/asset/plugins/flexslider/jquery.flexslider-min.js',
      'public/asset/plugins/progressbar/progressbar.min.js',
      'public/asset/plugins/parallax-js-master/parallax.min.js',
      'public/asset/plugins/sweetalert/sweetalert.min.js',
      'public/asset/js/custom.js'
   ], 'public/js/all.js');  

   mix.scripts([
      'public/adminlte/plugins/jquery/jquery.min.js',
      // 'public/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js',
      'public/adminlte/dist/js/adminlte.min.js',
      'public/adminlte/dist/js/demo.js',
      'public/adminlte/plugins/select2/js/select2.full.min.js',
      'public/adminlte/plugins/datatables/jquery.dataTables.min.js',
      'public/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
      'public/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js',
      'public/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js',
      'public/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js',
      'public/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js',
      'public/adminlte/plugins/jszip/jszip.min.js',
      'public/adminlte/plugins/pdfmake/pdfmake.min.js',
      'public/adminlte/plugins/pdfmake/vfs_fonts.js',
      'public/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js',
      'public/adminlte/plugins/datatables-buttons/js/buttons.print.min.js',
      'public/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js',
   ], 'public/js/plugins.js');  

   mix.js('resources/js/app_admin.js', 'public/js');

  