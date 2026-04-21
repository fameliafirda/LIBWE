<!-- Main Sidebar Container - Modern Futuristik -->
<aside class="main-sidebar elevation-4" style="background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); font-family: 'Poppins', sans-serif; position: fixed; height: 100vh; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #ec4899 #1a1a2e;">
  
  <!-- Custom Scrollbar Style -->
  <style>
    .main-sidebar::-webkit-scrollbar {
      width: 6px;
    }
    .main-sidebar::-webkit-scrollbar-track {
      background: #1a1a2e;
    }
    .main-sidebar::-webkit-scrollbar-thumb {
      background: linear-gradient(180deg, #ec4899, #8b5cf6);
      border-radius: 10px;
    }
    .main-sidebar::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(180deg, #f472b6, #a78bfa);
    }

    .brand-link {
      background: rgba(255, 255, 255, 0.05) !important;
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    .nav-link {
      position: relative;
      overflow: hidden;
    }

    .nav-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
      transition: left 0.5s ease;
      z-index: 0;
    }

    .nav-link:hover::before {
      left: 100%;
    }

    .nav-link.active {
      background: linear-gradient(90deg, rgba(236, 72, 153, 0.3), rgba(139, 92, 246, 0.3)) !important;
      border-left: 4px solid #ec4899 !important;
      box-shadow: 0 0 20px rgba(236, 72, 153, 0.3);
    }

    .nav-treeview .nav-link {
      margin-left: 15px;
      font-size: 14px;
    }

    .user-panel {
      border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    .glow-text {
      text-shadow: 0 0 10px rgba(236, 72, 153, 0.5);
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
      body {
        margin-left: 0;
      }

      .main-sidebar {
        width: 100%;
        height: auto;
        position: relative;
        padding-top: 20px;
      }

      /* Adjust brand image and text */
      .brand-link {
        display: flex;
        justify-content: center;
        padding: 10px 0;
      }

      .brand-image {
        width: 35px;
        margin-right: 10px;
      }

      .brand-text {
        font-size: 1.2rem;
        text-align: center;
      }

      /* Collapsing Sidebar Links */
      .sidebar .nav-link {
        font-size: 14px;
        padding: 10px;
        margin: 5px 0;
        text-align: center;
      }

      .sidebar .nav-link:hover {
        background: linear-gradient(90deg, #ffd1f7, #d0f0ff);
      }

      /* Adjust User Panel */
      .user-panel {
        padding: 10px;
        text-align: center;
      }

      /* Nav Treeview (nested menu) */
      .nav-treeview {
        padding-left: 0;
        display: none;
      }

      /* Show menu when clicked */
      .menu-open > .nav-treeview {
        display: block;
      }

      .nav-item.menu-open > .nav-link {
        border-radius: 5px;
        background-color: rgba(0, 0, 0, 0.1);
      }
    }

    /* For very small devices (mobile phone) */
    @media (max-width: 576px) {
      .nav-link {
        font-size: 12px;
      }

      .sidebar .nav-link {
        font-size: 13px;
      }

      /* Menu Treeview Adjustment */
      .nav-treeview .nav-link {
        font-size: 12px;
      }
    }
  </style>

  <!-- Brand Logo -->
  <a href="{{ url('/') }}" class="brand-link text-center py-4" style="display: flex; align-items: center; justify-content: center;">
    <img src="{{ asset('dist/img/libwe.png') }}" alt="LIBWE Logo" class="brand-image img-circle elevation-3" style="opacity: 1; width: 40px; margin-right: 8px; filter: drop-shadow(0 0 10px rgba(236, 72, 153, 0.5));">
    <span class="brand-text font-weight-bold" style="font-size: 1.5rem; background: linear-gradient(45deg, #ec4899, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-shadow: 0 0 20px rgba(236, 72, 153, 0.3);">LIBWE</span>
  </a>

  <div class="sidebar">
    <!-- User Panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center px-3">
      <div class="image">
        <img src="{{ asset('dist/img/admroblox.jpeg') }}" class="img-circle elevation-2" alt="User Image" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #ec4899; box-shadow: 0 0 15px rgba(236, 72, 153, 0.5);">
      </div>
      <div class="info ml-3">
        <a href="#" class="d-block" style="color: #fff; font-weight: 500; text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);">Pustakawan</a>
        <small style="color: rgba(255,255,255,0.6); font-size: 11px;">Online</small>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="color: #fff; margin: 5px 10px; padding: 12px 15px; border-radius: 12px;">
            <i class="fas fa-home" style="margin-right: 10px; color: #ec4899;"></i>
            <p style="font-weight: 500;">Dashboard</p>
          </a>
        </li>

        <!-- Buku -->
        <li class="nav-item {{ request()->is('books*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->is('books*') ? 'active' : '' }}" style="color: #fff; margin: 5px 10px; padding: 12px 15px; border-radius: 12px;">
            <i class="fas fa-book" style="margin-right: 10px; color: #8b5cf6;"></i>
            <p style="font-weight: 500;">Buku <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview pl-3">
            <li class="nav-item">
              <a href="{{ route('books.index') }}" class="nav-link {{ request()->routeIs('books.index') ? 'active' : '' }}" style="color: rgba(255,255,255,0.8); margin: 3px 10px; padding: 8px 15px; border-radius: 10px;">
                <i class="fas fa-list" style="margin-right: 8px; font-size: 12px; color: #a78bfa;"></i>
                <p style="font-size: 13px;">Semua Buku</p>
              </a>
            </li>
            @foreach($sidebarCategories as $kategori)
              <li class="nav-item">
                <a href="{{ route('books.byCategory', $kategori->id) }}" class="nav-link {{ request()->is("books/category/$kategori->id") ? 'active' : '' }}" style="color: rgba(255,255,255,0.8); margin: 3px 10px; padding: 8px 15px; border-radius: 10px;">
                  <i class="fas fa-folder" style="margin-right: 8px; font-size: 12px; color: #f472b6;"></i>
                  <p style="font-size: 13px;">{{ $kategori->nama }}</p>
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        <!-- Kategori -->
        <li class="nav-item">
          <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}" style="color: #fff; margin: 5px 10px; padding: 12px 15px; border-radius: 12px;">
            <i class="fas fa-tags" style="margin-right: 10px; color: #f472b6;"></i>
            <p style="font-weight: 500;">Kategori</p>
          </a>
        </li>

        <!-- Data Anggota -->
        <li class="nav-item">
          <a href="{{ route('anggotas.index') }}" class="nav-link {{ request()->routeIs('anggotas.index') ? 'active' : '' }}" style="color: #fff; margin: 5px 10px; padding: 12px 15px; border-radius: 12px;">
            <i class="fas fa-users" style="margin-right: 10px; color: #48dbfb;"></i>
            <p style="font-weight: 500;">Data Anggota</p>
          </a>
        </li>

        <!-- Peminjaman -->
        <li class="nav-item">
          <a href="{{ route('pinjamans.index') }}" class="nav-link {{ request()->routeIs('pinjamans.index') ? 'active' : '' }}" style="color: #fff; margin: 5px 10px; padding: 12px 15px; border-radius: 12px;">
            <i class="fas fa-hand-holding-heart" style="margin-right: 10px; color: #ff9ff3;"></i>
            <p style="font-weight: 500;">Peminjaman</p>
          </a>
        </li>

        <!-- Pengembalian -->
        <li class="nav-item">
          <a href="{{ route('pengembalians.index') }}" class="nav-link {{ request()->routeIs('pengembalians.index') ? 'active' : '' }}" style="color: #fff; margin: 5px 10px; padding: 12px 15px; border-radius: 12px;">
            <i class="fas fa-undo-alt" style="margin-right: 10px; color: #1dd1a1;"></i>
            <p style="font-weight: 500;">Pengembalian</p>
          </a>
        </li>

        <!-- Laporan -->
        <li class="nav-item {{ request()->is('laporan*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}" style="color: #fff; margin: 5px 10px; padding: 12px 15px; border-radius: 12px;">
            <i class="fas fa-chart-bar" style="margin-right: 10px; color: #feca57;"></i>
            <p style="font-weight: 500;">Laporan <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview pl-3">
            <li class="nav-item">
              <a href="{{ route('laporan.peminjaman') }}" class="nav-link {{ request()->routeIs('laporan.peminjaman') ? 'active' : '' }}" style="color: rgba(255,255,255,0.8); margin: 3px 10px; padding: 8px 15px; border-radius: 10px;">
                <i class="fas fa-arrow-up" style="margin-right: 8px; font-size: 12px; color: #feca57;"></i>
                <p style="font-size: 13px;">Peminjaman</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('laporan.buku') }}" class="nav-link {{ request()->routeIs('laporan.buku') ? 'active' : '' }}" style="color: rgba(255,255,255,0.8); margin: 3px 10px; padding: 8px 15px; border-radius: 10px;">
                <i class="fas fa-book" style="margin-right: 8px; font-size: 12px; color: #8b5cf6;"></i>
                <p style="font-size: 13px;">Buku</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('laporan.pengembalian') }}" class="nav-link {{ request()->routeIs('laporan.pengembalian') ? 'active' : '' }}" style="color: rgba(255,255,255,0.8); margin: 3px 10px; padding: 8px 15px; border-radius: 10px;">
                <i class="fas fa-arrow-down" style="margin-right: 8px; font-size: 12px; color: #1dd1a1;"></i>
                <p style="font-size: 13px;">Pengembalian</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Divider -->
        <li class="nav-item" style="margin: 10px 10px;">
          <hr style="border-color: rgba(255,255,255,0.1); margin: 0;">
        </li>

        <!-- Logout -->
        <li class="nav-item">
          <a href="{{ route('logout') }}" class="nav-link text-danger" style="margin: 5px 10px; padding: 12px 15px; border-radius: 12px; color: #ff6b6b !important;"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt" style="margin-right: 10px;"></i>
            <p style="font-weight: 500;">Logout</p>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </li>
      </ul>
    </nav>
  </div>
</aside>

<!-- CSS untuk mengatur konten utama agar tidak ketimpa sidebar fixed -->
<style>
 
  .main-sidebar {
    width: 250px;
    left: 0;
    top: 0;
    z-index: 1000;
  }
  .content-wrapper {
    min-height: 100vh;
  }
  @media (max-width: 768px) {
    body {
      margin-left: 0;
    }
    .main-sidebar {
      width: 100%;
      height: auto;
      position: relative;
    }
  }
</style>