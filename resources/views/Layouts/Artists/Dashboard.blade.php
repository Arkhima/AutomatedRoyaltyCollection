<!DOCTYPE html>
<html lang="en">
<head>
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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: linear-gradient(135deg, #1c2541, #f2c94c);
      --secondary-color: linear-gradient(to right, rgba(176, 210, 230, 0.63), hsl(184, 87.70%, 32.00%));
      --sidebar-width: 250px;
      --sidebar-collapsed-width: 70px;
    }

    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: #f8f9fa;
      color: #333;
      overflow-x: hidden;
    }
    .card {
      padding: 1.5rem;
      border-radius: 8px;
      background: transparent;
      box-shadow:
        0 2px 5px rgba(0,0,0,0.1),
        0 5px 10px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow:
        0 4px 12px rgba(0,0,0,0.15),
        0 8px 20px rgba(0,0,0,0.1);

      transition: transform 0.5s ease;
    }

    .text {
      text-shadow: 0px 2px 5px rgba(0,0,0,0.1);
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
      background: linear-gradient(135deg, #1c2541, #f2c94c);
      color: whitesmoke;
      padding: 1.5rem 1rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      width: var(--sidebar-width);
      transition: width 0.3s ease, transform 0.3s ease;
      overflow: hidden;
    }

    .layout-container.collapsed .sidebar {
      width: var(--sidebar-collapsed-width);
      padding: 1.5rem 0.5rem;
    }

    .sidebar-brand {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      white-space: nowrap;
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
      margin-bottom: 1.2rem;
    }

    .sidebar-nav a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.8rem;
      transition: background 0.3s, color 0.3s, transform 0.2s;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      white-space: nowrap;
    }

    .sidebar-nav a:hover {
      background: transparent;
      transform: translateX(4px);
      transition: transform 0.5s ease;
    }

    .layout-container.collapsed .sidebar-nav span {
      display: none;
    }

    /* Header styles */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: var(--primary-color);
      padding: 1rem 2rem;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      position: relative;
    }

    .header-title {
      font-size: 1.4rem;
      font-weight: 600;
      color: white;
      white-space: nowrap;
    }

    .toggle-btn {
      cursor: pointer;
      font-size: 1.4rem;
      color: white;
      border: none;
      background: none;
      flex-shrink: 0;
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      flex-shrink: 0;
    }

    .user-profile img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    /* Animated marquee banner */
    .animated-banner {
      flex: 1;
      margin: 0 1rem;
      overflow: hidden;
      white-space: nowrap;
      position: relative;
      color: #fff;
      font-weight: 600;
      font-size: 1rem;
      align-self: center;
      user-select: none;
      
      /* fade effect on edges */
      mask-image: linear-gradient(to right, transparent 0%, black 10%, black 90%, transparent 100%);
      -webkit-mask-image: linear-gradient(to right, transparent 0%, black 10%, black 90%, transparent 100%);
    }

    .animated-banner span {
      display: inline-block;
      padding-left: 100%;
      animation: scroll-text 15s linear infinite;
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
      padding: 2rem;
      transition: padding 0.3s ease;
    }

    /* Footer */
    .footer {
      background: var(--primary-color);
      padding: 1rem 2rem;
      text-align: center;
      font-size: 0.9rem;
      color: white;
      box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
      margin-top: auto;
    }

    /* Mobile Styles */
    @media (max-width: 768px) {
      .layout-container {
        grid-template-columns: 1fr;
      }

      .sidebar {
        position: fixed;
        top: 0;
        left: -250px;
        height: 100%;
        transform: translateX(0);
        z-index: 999;
        width: var(--sidebar-width);
      }

      .layout-container.sidebar-open .sidebar {
        transform: translateX(250px);
      }

      .layout-container.collapsed.sidebar-open .sidebar {
        transform: translateX(70px);
      }
    }

  </style>
</head>

<body>
  <div class="layout-container" id="layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div>
        <div class="sidebar-brand">
          <i class="fa fa-chart-line text-gray-800"></i> <span style="text-shadow: 2px 2px 5px rgba(0,0,0,1)">ARC</span>
        </div>
        <nav class="sidebar-nav">
          <ul>
            <li><a href="{{ route('Artists.Dashboard.index') }}">
                        <i class="fa fa-home text-gray-800"></i>
                             <span style="text-shadow: 3px 3px 5px rgba(0,0,0,1)">Dashboard</span></a></li>

            <li><a href="{{ route('Artists.Tracks.index') }}">
                       <i class="fa fa-music text-red-800"></i>
                            <span style="text-shadow: 3px 3px 5px rgba(0,0,0,1)">Tracks</span></a></li>

            <li><a href="{{ route('Artists.ArtistSection.index') }}">
                       <i class="fa fa-user text-blue-800"></i>
                            <span style="text-shadow: 3px 3px 5px rgba(0,0,0,1)">Artist</span></a></li>

            <li><a href="{{ route('Artists.Streams.index') }}">
                       <i class="fa fa-upload text-purple-800"></i> 
                            <span style="text-shadow: 3px 3px 5px rgba(0,0,0,1)">Consumption Data</span></a></li>

            <li><a href="{{ route('Artists.Royalties.index') }}">
                       <i class="fa fa-calculator text-indigo-800"></i> 
                            <span style="text-shadow: 3px 3px 5px rgba(0,0,0,1)">Royalty Calculations</span></a></li>

            <li><a href="{{ route('Artists.Reports.index') }}">
                       <i class="fa fa-chart-bar text-violet-800"></i> 
                            <span style="text-shadow: 3px 3px 5px rgba(0,0,0,1)">Reports</span></a></li>

            <li><a href="{{ route('Artists.Payments.index') }}">
                       <i class="fa fa-money-bill-wave text-green-500 text:hover-green-500"></i>
                            <span style="text-shadow: 3px 3px 5px rgba(0,0,0,1)">Payments</span></a></li>
          </ul>
        </nav>
      </div>
      <div class="text-sm mt-auto"><i>
        <marquee class="marquee-continuous">&copy; 2025 Automated Royalty Collection</marquee>
      </i></div>
    </aside>

    <!-- Main area -->
    <div class="flex flex-col min-h-screen">
      <!-- Header -->
      <header class="header">
        <button class="toggle-btn" onclick="toggleSidebar()">
          <i class="fas fa-bars"></i>
        </button>
        <div class="header-title">
          User Dashboard
        </div>

        <div class="animated-banner" aria-label="Creative inspirational banner">
          <span>🎨 Inspire. Create. Repeat. — Let your music for you! ✨</span>
        </div>

        <div class="user-profile">
          <span class="text-gray-600 font-medium" style="color: white">My Profile</span>
          <img src="https://via.placeholder.com/40" alt="User Avatar" />
        </div>
      </header>

      <!-- Main Content -->
      <main class="main-content flex-grow">
        <!-- Replace this with your page content -->
        @yield('content')
      </main>

      <!-- Footer -->
      <footer class="footer">
        &copy; 2025 ARC. All rights reserved.
      </footer>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      const layout = document.getElementById('layout');
      if (window.innerWidth <= 768) {
        layout.classList.toggle('sidebar-open');
      } else {
        layout.classList.toggle('collapsed');
      }
    }
  </script>
</body>
</html>
