<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin Perpustakaan')</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  @stack('styles')

<style>
  body {
    font-family: 'Poppins', sans-serif;
  }

  .main-sidebar {
    background: linear-gradient(180deg, #fbeaff, #d4ecff);
    color: #333;
  }

  .brand-link {
    background-color: rgba(255, 255, 255, 0.3);
    border-bottom: 1px solid #ccc;
    font-size: 1.2rem;
  }

  .sidebar .nav-link {
    border-radius: 10px;
    margin: 5px 8px;
    padding: 10px 15px;
    font-weight: 500;
    font-size: 15px;
    transition: all 0.3s ease;
    color: #333;
  }

  .sidebar .nav-link:hover {
    background: linear-gradient(90deg, #ffd1f7, #d0f0ff);
    transform: translateX(6px);
    color: #111 !important;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #ff85c1;
  }

  .sidebar .nav-link:hover p {
    animation: bounce 0.3s ease;
  }

  @keyframes bounce {
    0% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
    100% { transform: translateY(0); }
  }

  .nav-link.active {
    background: linear-gradient(90deg, #ffe3fb, #d5f4ff);
    font-weight: bold;
    color: #000 !important;
    border-left: 4px solid #9b5de5;
    box-shadow: inset 0 0 8px rgba(0, 0, 0, 0.05);
  }

  .nav-treeview {
    display: none;
    padding-left: 1.5rem;
  }

  .menu-open > .nav-treeview {
    display: block;
  }

  .user-panel img {
    border: 2px solid #fff;
  }

  .content-header h1 {
    font-size: 22px;
    font-weight: 600;
  }

  /* =======================================================
     PERBAIKAN SIDEBAR UNTUK MOBILE (Off-Canvas Overlay)
     ======================================================= */
  @media (max-width: 991.98px) {
    .main-sidebar {
      position: fixed !important;
      top: 0;
      bottom: 0;
      left: -260px; /* Sembunyikan sidebar ke kiri */
      height: 100vh;
      width: 250px;
      z-index: 1050 !important;
      transition: left 0.3s ease-in-out;
      box-shadow: none;
    }

    /* Saat tombol menu diklik, sidebar masuk ke layar */
    body.sidebar-open .main-sidebar {
      left: 0;
      box-shadow: 10px 0 30px rgba(0,0,0,0.5);
    }

    /* Latar belakang gelap saat sidebar terbuka */
    .sidebar-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(2px);
      z-index: 1040;
    }

    body.sidebar-open .sidebar-overlay {
      display: block;
    }

    .content-wrapper {
      margin-left: 0 !important;
      width: 100% !important;
    }
  }

  /* Responsive Design Adjustments Asli Milikmu */
  @media (max-width: 768px) {
    .content-wrapper {
      padding: 15px;
    }

    .container-fluid {
      padding-left: 15px;
      padding-right: 15px;
    }

    .card-header, .card-body {
      padding: 10px;
    }

    .table-responsive {
      overflow-x: auto;
    }

    .nav-link {
      font-size: 14px;
    }

    .btn {
      font-size: 14px;
      padding: 8px 15px;
    }

    /* Ensure the table takes full width on smaller screens */
    .table {
      width: 100% !important;
      margin-bottom: 15px;
    }

    .container-fluid {
      padding: 0 10px;
    }

    .content-header h1 {
      font-size: 20px;
    }
  }

  @media (max-width: 576px) {
    .nav-link {
      font-size: 12px;
      padding: 8px 10px;
    }

    .sidebar .nav-link {
      font-size: 14px;
    }

    .content-wrapper {
      padding: 10px;
    }

    /* Adjust table layout for mobile */
    .table th, .table td {
      padding: 8px;
    }

    .table thead {
      font-size: 14px;
    }

    .card-header h5 {
      font-size: 18px;
    }

    .btn {
      padding: 8px 12px;
      font-size: 14px;
    }
  }
</style>

</head>
<body class="hold-transition sidebar-mini">
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<div class="wrapper">

  {{-- Navbar --}}
  @include('layouts.navbar')

  {{-- Sidebar --}}
  @include('layouts.sidebar')

  {{-- Content Wrapper --}}
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('title', 'Dashboard')</h1>
          </div>
          <div class="col-sm-6 text-right">
            @yield('breadcrumb')
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>
  </div>

  {{-- Footer --}}
  @include('layouts.footer')

</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Script untuk memunculkan/menyembunyikan sidebar di mobile --}}
<script>
  function toggleSidebar() {
    document.body.classList.toggle('sidebar-open');
  }

  document.addEventListener('DOMContentLoaded', function () {
    // Tangkap tombol hamburger (pastikan di layouts.navbar milikmu tombolnya punya attribute data-widget="pushmenu")
    const menuBtn = document.querySelector('[data-widget="pushmenu"]');
    if (menuBtn) {
      menuBtn.addEventListener('click', function (e) {
        // Cegah AdminLTE bawaan bentrok jika di HP, kita pakai sistem toggle kita sendiri
        if(window.innerWidth <= 991.98) {
           e.preventDefault();
           e.stopPropagation();
           toggleSidebar();
        }
      });
    }
  });
</script>

{{-- Accordion Sidebar Toggle (Bawaan milikmu) --}}
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleParents = document.querySelectorAll('.nav-item.has-treeview > a');
    toggleParents.forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        const parent = this.closest('.nav-item');
        parent.classList.toggle('menu-open');
      });
    });
  });
</script>

@stack('scripts')
</body>
</html>