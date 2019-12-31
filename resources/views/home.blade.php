<!DOCTYPE html>
<html lang="en">

@include('admin.included.head')


<body class="hold-transition sidebar-mini">
  <div class="wrapper" id="vue-user">

    <!-- Top Navbar -->
    @include('admin.included.top-navbar')

    @auth

    <!-- Sidebar Menu -->
    @include('admin.included.sidebar')
    <div>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">

        <!-- Main content -->
        <div class="content">
          <div class="container-fluid pt-5">


            <router-view></router-view>
            <vue-progress-bar></vue-progress-bar>



          </div><!-- /.container-fluid -->
        </div> <!-- /.content -->
      </div> <!-- /.content-wrapper -->
    </div>
    @else
    @include('auth.login')
    @endauth

    <!-- Main Footer -->
    @include('admin.included.footer')

    <!-- The Modal -->
    @include('admin.included.logout-modal')


  </div> <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <script src="{{ asset('/js/app.js') }}"></script>

</body>

</html>