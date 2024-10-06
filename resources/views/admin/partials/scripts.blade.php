  <!-- plugins:js -->
  <script src="{{ asset('adminTemplate/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="{{ asset('adminTemplate/vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('adminTemplate/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('adminTemplate/vendors/progressbar.js/progressbar.min.js') }}"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('adminTemplate/js/off-canvas.js') }}"></script>
  <script src="{{ asset('adminTemplate/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('adminTemplate/js/template.js') }}"></script>
  <script src="{{ asset('adminTemplate/js/settings.js') }}"></script>
  <script src="{{ asset('adminTemplate/js/todolist.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('adminTemplate/js/dashboard.js') }}"></script>
  <script src="{{ asset('adminTemplate/js/Chart.roundedBarCharts.js') }}"></script>

  <!-- Bootstrap -->
  <script src="{{ asset('vendors/bootstrap/bootstrap.bundle.min.js') }}"></script>

  <!-- jquery -->
  <script src="{{ asset('vendors/jquery/jquery-3.7.1.min.js') }}"></script>
  
  <!-- sweetalert2 -->
  <script src="{{ asset('vendors/sweetAlert2/sweetalert2.js') }}"></script>
  <script src="{{ asset('js/alerts.js') }}"></script>
  
  <!-- functions -->
  <script src="{{ asset('adminAssets/js/commonFunctions.js') }}"></script>

  
  @yield('js')
  <!-- End custom js for this page-->