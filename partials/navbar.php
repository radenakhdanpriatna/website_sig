<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm">
  <!-- Sidebar toggle button-->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button" title="Toggle Sidebar">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="admin_dashboard.php" class="nav-link font-weight-bold text-primary">Home</a>
    </li>
  </ul>

  <!-- Brand center -->
  <a href="dashboard.php" class="navbar-brand mx-auto d-flex align-items-center">
    <img src="images/peta.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: 0.9; width: 36px; height: 36px;">
    <span class="ml-2 font-weight-bold text-primary" style="font-size: 1.4rem;">SIG Pemetaan</span>
  </a>

  <!-- Right navbar -->
  <ul class="navbar-nav ml-auto">
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle text-primary" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="far fa-user-circle mr-1"></i> Admin
    </a>
    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 rounded-lg animate__animated animate__fadeIn" aria-labelledby="userDropdown">
      <a href="profile.php" class="dropdown-item d-flex align-items-center text-primary">
        <i class="fas fa-user mr-2"></i> Profile
      </a>
      <div class="dropdown-divider"></div>
      <a href="logout.php" class="dropdown-item d-flex align-items-center text-danger">
        <i class="fas fa-sign-out-alt mr-2"></i> Logout
      </a>
    </div>
  </li>
</ul>
</nav>

<!-- Optional styling -->
<style>
  .main-header.navbar {
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  .main-header .nav-link {
    color: #007bff !important;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .main-header .nav-link:hover,
  .main-header .nav-link:focus {
    background-color: rgba(0, 123, 255, 0.1);
    border-radius: 0.375rem;
    color: #0056b3 !important;
  }

  .dropdown-menu {
    background-color: #f8f9fa;
    animation-duration: 0.3s;
  }

  .dropdown-menu .dropdown-item:hover {
    background-color: #007bff;
    color: #ffffff !important;
  }

  .navbar-brand {
    padding-left: 0;
    padding-right: 0;
  }
</style>
<script>
  $(document).ready(function () {
    console.log("jQuery OK");
    $('#userDropdown').on('click', function () {
      console.log("Dropdown clicked");
    });
  });
</script>
<!-- jQuery, Popper.js, Bootstrap JS (urutan penting) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
