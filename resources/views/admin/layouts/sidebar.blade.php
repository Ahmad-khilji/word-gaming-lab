<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
      <!-- Dashboard Nav -->
      <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('super_admin.index') ? 'active' : '' }}" href="{{ route('super_admin.index') }}">
              <i class="bi bi-grid"></i>
              <span>Dashboard</span>
          </a>
      </li><!-- End Dashboard Nav -->
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('super_admin.theme.index') ? 'active' : '' }}" href="{{ route('super_admin.theme.index') }}">
            <i class="bi bi-menu-button-wide"></i>
            <span>Setting</span>
        </a>
    </li>
      <!-- Game Setting Nav -->
      @php
          $gameRoutes = [
              'super_admin.threeword.index',
              'super_admin.fiveword.index',
              'super_admin.sevenword.index'
          ];
          $isGameActive = request()->routeIs($gameRoutes);
      @endphp

      <li class="nav-item {{ $isGameActive ? 'active' : '' }}">
          <a class="nav-link {{ $isGameActive ? '' : 'collapsed' }}" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-menu-button-wide"></i>
              <span>Game Setting</span>
              <i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse {{ $isGameActive ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('super_admin.threeword.index') ? 'active' : '' }}" href="{{ route('super_admin.threeword.index') }}">
                      <i class="bi bi-menu-button-wide"></i>
                      <span>Three Letter</span>
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('super_admin.fiveword.index') ? 'active' : '' }}" href="{{ route('super_admin.fiveword.index') }}">
                      <i class="bi bi-menu-button-wide"></i>
                      <span>Five Letter</span>
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('super_admin.sevenword.index') ? 'active' : '' }}" href="{{ route('super_admin.sevenword.index') }}">
                      <i class="bi bi-menu-button-wide"></i>
                      <span>Seven Letter</span>
                  </a>
              </li>
          </ul>
      </li><!-- End Game Setting Nav -->
  </ul>
</aside><!-- End Sidebar -->
