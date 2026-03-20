<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login | ARC</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --primary-dark: #1c2541;
            --primary-medium: #3a506b;
            --primary-teal: #5bc0be;
            --accent-gold: #f2c94c;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #1c2541 0%, #3a506b 50%, #5bc0be 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-gold);
        }
        
        .brand {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-dark);
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        .brand i {
            color: var(--accent-gold);
            margin-right: 0.5rem;
        }
        
        .admin-badge {
            background: rgba(242, 201, 76, 0.15);
            color: #b48a00;
            padding: 0.25rem 1rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 3px rgba(242, 201, 76, 0.1);
        }
        
        .login-btn {
            width: 100%;
            background: var(--primary-dark);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .login-btn:hover {
            background: var(--primary-medium);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .back-link {
            color: var(--primary-medium);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: var(--accent-gold);
        }
        
        .error-message {
            background: #fee;
            color: #c00;
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .success-message {
            background: #efe;
            color: #0a0;
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <i class="fas fa-shield-alt"></i>
            <span>ARC Admin</span>
        </div>
        
        <div class="text-center">
            <span class="admin-badge">
                <i class="fas fa-lock mr-1"></i> Administrator Access Only
            </span>
        </div>

        @if($errors->any())
            <div class="error-message">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email Address
                </label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       class="form-input"
                       placeholder="admin@example.com"
                       required 
                       autofocus>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <input type="password" 
                       name="password" 
                       class="form-input"
                       placeholder="••••••••"
                       required>
            </div>

            <div class="mb-4 flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                
                <!-- ✅ Updated: Admin password reset (separate from users) -->
                <a href="{{ route('admin.password.request') }}" class="back-link text-sm">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Access Admin Panel
            </button>
        </form>

        <!-- ✅ REMOVED: "Back to Artist Login" link - complete separation -->
        
        <div class="mt-6 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} ARC - Automated Royalty Collection
        </div>
    </div>

    <script>
        // Add floating animation to brand icon
        document.querySelector('.brand i').style.animation = 'float 3s ease-in-out infinite';
        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>