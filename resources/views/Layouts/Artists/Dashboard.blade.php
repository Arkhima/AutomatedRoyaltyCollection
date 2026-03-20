<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Artist Dashboard | ARC</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #1c2541 0%, #3a506b 50%, #5bc0be 100%);
      --accent-color: #f2c94c;
      --sidebar-width: 280px;
      --sidebar-collapsed-width: 80px;
    }

    body {
      margin: 0;
      font-family: 'Montserrat', sans-serif;
      background: #f8f9fa;
      color: #333;
      overflow-x: hidden;
    }
    
    h1, h2, h3, .brand-font {
      font-family: 'Playfair Display', serif;
    }

    .card {
      padding: 1.5rem;
      border-radius: 12px;
      background: white;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      border: none;
      overflow: hidden;
      position: relative;
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: var(--accent-color);
      transition: width 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .card:hover::before {
      width: 8px;
    }

    .layout-container {
      display: grid;
      grid-template-columns: var(--sidebar-width) 1fr;
      transition: grid-template-columns 0.3s ease;
      min-height: 100vh;
    }

    .layout-container.collapsed {
      grid-template-columns: var(--sidebar-collapsed-width) 1fr;
    }

    /* Sidebar styles */
    .sidebar {
      background: var(--primary-gradient);
      color: white;
      padding: 2rem 1rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      width: var(--sidebar-width);
      transition: all 0.3s ease;
      overflow: hidden;
      position: relative;
      z-index: 10;
    }

    .sidebar::after {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 1px;
      height: 100%;
      background: rgba(255,255,255,0.1);
    }

    .layout-container.collapsed .sidebar {
      width: var(--sidebar-collapsed-width);
      padding: 2rem 0.5rem;
    }

    .sidebar-brand {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      white-space: nowrap;
      padding: 0 0.5rem;
    }

    .sidebar-brand i {
      color: var(--accent-color);
      font-size: 2rem;
    }

    .layout-container.collapsed .sidebar-brand span {
      display: none;
    }

    .sidebar-nav ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .sidebar-nav li {
      margin-bottom: 0.8rem;
    }

    .sidebar-nav a {
      color: rgba(255,255,255,0.9);
      text-decoration: none;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 1rem;
      transition: all 0.3s ease;
      padding: 0.75rem 1rem;
      border-radius: 6px;
      white-space: nowrap;
      position: relative;
    }

    .sidebar-nav a:hover {
      background: rgba(255,255,255,0.1);
      color: white;
      transform: translateX(5px);
    }

    .sidebar-nav a.active {
      background: rgba(255,255,255,0.15);
      color: white;
      font-weight: 600;
    }

    .sidebar-nav a.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 3px;
      background: var(--accent-color);
    }

    .sidebar-nav i {
      min-width: 24px;
      text-align: center;
      font-size: 1.1rem;
    }

    .layout-container.collapsed .sidebar-nav span {
      display: none;
    }

    /* Header styles */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: white;
      padding: 1rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      position: relative;
      z-index: 5;
      gap: 0.5rem;
    }

    .header-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: #1c2541;
      white-space: nowrap;
    }

    .toggle-btn {
      cursor: pointer;
      font-size: 1.4rem;
      color: #1c2541;
      border: none;
      background: none;
      flex-shrink: 0;
      transition: all 0.3s ease;
    }

    .toggle-btn:hover {
      color: var(--accent-color);
      transform: rotate(90deg);
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      flex-shrink: 0;
    }

    .user-profile img {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
      cursor: pointer;
      border: 2px solid var(--accent-color);
      transition: all 0.3s ease;
    }

    .user-profile img:hover {
      transform: scale(1.05);
      box-shadow: 0 0 0 3px rgba(242, 201, 76, 0.3);
    }

    .user-profile a {
      color: #1c2541;
      font-weight: 500;
      transition: all 0.3s ease;
      text-decoration: none;
      font-size: 0.9rem;
    }

    .user-profile a:hover {
      color: var(--accent-color);
    }

    /* Animated marquee banner */
    .animated-banner {
      flex: 1;
      margin: 0 0.5rem;
      overflow: hidden;
      white-space: nowrap;
      position: relative;
      color: #1c2541;
      font-weight: 500;
      font-size: 0.8rem;
      align-self: center;
      user-select: none;
      font-style: italic;
      text-overflow: ellipsis;
      max-width: 180px;
    }

    @media (min-width: 768px) {
      .header {
        padding: 1rem 2rem;
        gap: 1rem;
      }
      .header-title {
        font-size: 1.5rem;
      }
      .user-profile {
        gap: 1rem;
      }
      .user-profile img {
        width: 42px;
        height: 42px;
      }
      .user-profile a {
        font-size: 1rem;
      }
      .animated-banner {
        font-size: 1rem;
        max-width: none;
        margin: 0 2rem;
      }
    }

    .animated-banner span {
      display: inline-block;
      padding-left: 100%;
      animation: scroll-text 20s linear infinite;
    }

    .animated-banner:hover span {
      animation-play-state: paused;
    }

    @keyframes scroll-text {
      0% {
        transform: translateX(0%);
      }
      100% {
        transform: translateX(-100%);
      }
    }

    /* Main content */
    .main-content {
      padding: 1rem;
      transition: padding 0.3s ease;
      background: #f8fafc;
    }

    @media (min-width: 768px) {
      .main-content {
        padding: 2rem;
      }
    }

    /* Footer */
    .footer {
      background: white;
      padding: 1rem;
      text-align: center;
      font-size: 0.8rem;
      color: #1c2541;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
      margin-top: auto;
    }

    @media (min-width: 768px) {
      .footer {
        padding: 1.25rem 2rem;
        font-size: 0.9rem;
      }
    }

    .footer a {
      color: var(--accent-color);
      text-decoration: none;
      font-weight: 500;
    }

    /* Creative elements */
    .music-note {
      position: absolute;
      opacity: 0.1;
      z-index: -1;
    }

    .note-1 {
      top: 10%;
      left: 5%;
      font-size: 3rem;
      transform: rotate(-15deg);
    }

    .note-2 {
      bottom: 15%;
      right: 5%;
      font-size: 4rem;
      transform: rotate(25deg);
    }

    /* Mobile Styles */
    @media (max-width: 767px) {
      .layout-container {
        grid-template-columns: 1fr;
      }

      .sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        height: 100%;
        width: 85%;
        z-index: 1000;
      }

      .layout-container.sidebar-open .sidebar {
        transform: translateX(100%);
      }

      .header-title span:not(.sm-hidden) {
        display: none;
      }

      .animated-banner span {
        animation: none;
        padding-left: 0;
      }

      .user-profile a span {
        display: none;
      }

      #page-loader {
  opacity: 0.5;
  pointer-events: auto;
}

#page-loader.fade-out {
  opacity: 0;
  pointer-events: none;
}


    }

    @media (max-width: 480px) {
      .user-profile a {
        display: none;
      }
      
      .animated-banner {
        display: none;
      }
    }

    /* Animation for creative touch */
    @keyframes float {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }
    
    .floating {
      animation: float 3s ease-in-out infinite;
    }

    /* Creative Marquee Styling */
.marquee-container {
  overflow: hidden;
  white-space: nowrap;
  position: relative;
  font-style: italic;
  font-weight: 600;
  color: var(--accent-color);
  user-select: none;
  font-family: 'Playfair Display', serif;
}

.marquee-content {
  display: inline-block;
  padding-left: 100%;
  animation: creative-marquee 4s linear infinite;
}

.marquee-container:hover .marquee-content {
  animation-play-state: paused;
}

@keyframes creative-marquee {
  0% {
    transform: translateX(0%);
  }
  100% {
    transform: translateX(-100%);
  }
}

  </style>
</head>

<body>
  <!-- Vinyl Record Spinner with Creative Text -->
<div id="page-loader" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center transition-opacity duration-500"
     style="background: rgba(28, 37, 65, 0.98);">
  <div class="w-16 h-16 border-[6px] border-yellow-400 border-t-white rounded-full animate-spin relative">
    <div class="absolute top-1/2 left-1/2 w-4 h-4 bg-black rounded-full transform -translate-x-1/2 -translate-y-1/2"></div>
  </div>
  <p class="mt-4 text-yellow-400 font-semibold tracking-wide text-lg select-none" style="font-family: 'Playfair Display', serif;">
    Loading Your Groove data…
    <div class="marquee-container text-sm sm:text-base">
            <div class="marquee-content">
                  🎧 Please be patient 🎶
              </div>
    </div>
  </p>
</div>


  <div class="layout-container" id="layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div>
        <div class="sidebar-brand brand-font">
          <i class="fas fa-music floating"></i> 
          <span>ARC</span>
        </div>
        <nav class="sidebar-nav">
          <ul>
            <li>
              <a href="{{ route('Artists.Dashboard.index') }}" class="{{ request()->routeIs('Artists.Dashboard.*') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
              </a>
            </li>

          <li>
            <a href="{{ route('Artists.Profile.show') }}" class="{{ request()->routeIs('Artists.Profile.*') ? 'active' : '' }}">
              <i class="fas fa-user"></i>
              <span>Artist</span>
            </a>
          </li>

          @if(Auth::check() && Auth::user()->is_admin)
            <li>
              <a href="{{ route('Admin.Dashboard.index') }}" class="{{ request()->routeIs('Admin.*') ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i>
                <span>Admin</span>
              </a>
            </li>
          @endif

            <li>
              <a href="{{ route('Artists.Tracks.index') }}" class="{{ request()->routeIs('Artists.Tracks.*') ? 'active' : '' }}">
                <i class="fas fa-music"></i>
                <span>Tracks</span>
              </a>
            </li>

            <li>
              <a href="{{ route('Artists.Streams.index') }}" class="{{ request()->routeIs('Artists.Streams.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> 
                <span>Stream Analytics</span>
              </a>
            </li>

            <li>
              <a href="{{ route('Artists.Royalties.index') }}" class="{{ request()->routeIs('Artists.Royalties.*') ? 'active' : '' }}">
                <i class="fas fa-calculator"></i> 
                <span>Royalties</span>
              </a>
            </li>

            <li>
              <a href="{{ route('Artists.Reports.index') }}" class="{{ request()->routeIs('Artists.Reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> 
                <span>Reports</span>
              </a>
            </li>

            <li>
              <a href="{{ route('Artists.Payments.index') }}" class="{{ request()->routeIs('Artists.Payments.*') ? 'active' : '' }}">
                <i class="fas fa-wallet"></i>
                <span>Payments</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
      <div class="text-xs mt-auto opacity-80">
        <i class="fas fa-music music-note note-1"></i>
        <i class="fas fa-music music-note note-2"></i>
        <div class="text-center py-2 border-t border-white/10">
          &copy; 2025 ARC<br>
          <small>Automated Royalty Collection</small>
        </div>
      </div>
    </aside>

    <!-- Main area -->
    <div class="flex flex-col min-h-screen">
      <!-- Header -->
      <header class="header">
        <button class="toggle-btn" onclick="toggleSidebar()">
          <i class="fas fa-bars"></i>
        </button>
        
        <div class="flex items-center flex-1 min-w-0">
          <div class="header-title brand-font truncate">
            <span id="greeting" class="hidden sm:inline"></span>, {{ Auth::user()->name }}
            <span class="sm:hidden">ARC</span>
          </div>
          
          <div class="animated-banner truncate">
            <span>🎵 Your art matters • ✨ Create without limits • 🎶 Let your music speak</span>
          </div>
        </div>

        <div class="user-profile">
          <a href="{{ route('Artists.Profile.show') }}" 
             class="hidden sm:inline hover:text-yellow-500 transition-colors" 
             title="My Artist Profile">
            <i class="fas fa-user-edit mr-1"></i>
            <span class="hidden md:inline">Profile</span>
          </a>
          
          <a href="{{ route('Artists.Profile.show') }}" title="My Artist Profile">
            <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=1c2541&color=fff' }}" 
                 alt="Artist Profile" />
          </a>

          <!-- Logout Form -->
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
          </form>

          <!-- Logout Button with confirmation -->
          <button
            onclick="confirmLogout(event)"
            title="Logout"
            aria-label="Logout"
            class="text-gray-700 hover:text-yellow-500 transition-colors text-lg"
          >
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </div>
      </header>

      <!-- Main Content -->
      <main class="main-content flex-grow">
        <div class="container mx-auto px-2 sm:px-4">
          @yield('content')
        </div>
      </main>

      <!-- Footer -->
      <footer class="footer">
        <div class="container">
          <p>&copy; 2025 <a href="/">ARC</a>. All rights reserved. 
          <span class="hidden sm:inline">|</span> <br class="sm:hidden"> 
          Empowering creators since 2025</p>
        </div>
      </footer>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      const layout = document.getElementById('layout');
      if (window.innerWidth <= 767) {
        layout.classList.toggle('sidebar-open');
      } else {
        layout.classList.toggle('collapsed');
      }
    }

    function confirmLogout(event) {
      event.preventDefault();
      if (confirm('Ready to take a creative break?\nYou can always come back to your artistic hub later.')) {
        document.getElementById('logout-form').submit();
      }
    }

    // Dynamic greeting
    function updateGreeting() {
      const hour = new Date().getHours();
      const greeting = document.getElementById('greeting');
      const isMobile = window.innerWidth < 640;
      
      if (isMobile) {
        greeting.textContent = hour < 12 ? '☀️' : hour < 18 ? '🌤️' : '🌙';
      } else {
        if (hour < 12) {
          greeting.textContent = 'Good Morning';
        } else if (hour < 18) {
          greeting.textContent = 'Good Afternoon';
        } else {
          greeting.textContent = 'Good Evening';
        }
      }
    }
    
    // Run on load and resize
    updateGreeting();
    window.addEventListener('resize', updateGreeting);

    window.addEventListener('load', () => {
  const loader = document.getElementById('page-loader');
  setTimeout(() => {
    loader.classList.add('opacity-0', 'pointer-events-none');
    setTimeout(() => loader.remove(), 500); // fade out duration
  }, 1900); // <-- delay before fading out (2000ms = 2 seconds)
});
    
    // Add floating animation to random elements
    document.addEventListener('DOMContentLoaded', function() {
      const cards = document.querySelectorAll('.card');
      cards.forEach((card, index) => {
        if (index % 2 === 0) {
          card.classList.add('floating');
          card.style.animationDelay = `${index * 0.2}s`;
        }
      });
    });
  </script>
</body>
</html>