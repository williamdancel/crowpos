<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CrowPOS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <style>
        /* Loading Overlay Styles */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            flex-direction: column;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #4f46e5;
            animation: spin 1s ease-in-out infinite;
        }
        
        .loading-text {
            color: white;
            margin-top: 20px;
            font-size: 1.2rem;
            font-weight: 500;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Optional: Prevent body scroll when loading */
        body.loading-active {
            overflow: hidden;
        }
        
        /* Make buttons have relative positioning for z-index */
        .btn {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Redirecting...</div>
    </div>
    
    <div class="glow"></div>

    <div class="wrap">
        <div class="card">
            <div>
                <img src="/images/crowPOS.png" alt="CrowPOS Logo" style="width: 48px; height: 48px;">
            </div>

            <h1>CrowPOS</h1>

            <p class="tagline">
                Simple point of sale for daily business.
            </p>

            @if (Route::has('login'))
                @auth
                    <a class="btn" href="{{ url('/pos') }}" id="openPosBtn">Open POS</a>
                @else
                    <a class="btn" href="{{ route('login') }}" id="loginBtn">Login</a>
                @endauth
            @endif

            <div class="small">
                © {{ date('Y') }} CrowPOS
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the buttons
            const loginBtn = document.getElementById('loginBtn');
            const openPosBtn = document.getElementById('openPosBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            // Function to show loading screen
            function showLoading() {
                loadingOverlay.style.display = 'flex';
                document.body.classList.add('loading-active');
            }
            
            // Add click event to login button if it exists
            if (loginBtn) {
                loginBtn.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent immediate navigation
                    showLoading();
                    
                    // Simulate a small delay before redirecting (optional)
                    setTimeout(() => {
                        window.location.href = loginBtn.href;
                    }, 300);
                });
            }
            
            // Add click event to open POS button if it exists
            if (openPosBtn) {
                openPosBtn.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent immediate navigation
                    showLoading();
                    
                    // Simulate a small delay before redirecting (optional)
                    setTimeout(() => {
                        window.location.href = openPosBtn.href;
                    }, 300);
                });
            }
            
            // Also show loading if the page is taking a long time to unload
            window.addEventListener('beforeunload', function() {
                // This will trigger when the page starts to unload for navigation
                showLoading();
            });
        });
    </script>
</body>
</html>