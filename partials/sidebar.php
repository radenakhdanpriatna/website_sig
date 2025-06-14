<!-- partials/sidebar.php -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="dashboard.php" class="brand-link">
    <i class="fas fa-map-marked-alt ml-3 mr-2"></i>
    <span class="brand-text font-weight-light">SIG Perumahan</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="images/man.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Administrator</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-item">
          <a href="admin_dashboard.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="manage_rumah.php" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>Data Rumah</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="manage_peta.php" class="nav-link">
            <i class="nav-icon fas fa-map"></i>
            <p>Data Lokasi</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="manage_galeri.php" class="nav-link">
            <i class="nav-icon fas fa-image"></i>
            <p>Galeri Gambar</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="manage_ulasan.php" class="nav-link">
            <i class="nav-icon fas fa-comments"></i>
            <p>Ulasan Pengguna</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="logout.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
            <p>Logout</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
