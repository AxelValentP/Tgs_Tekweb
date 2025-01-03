<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.8);
            z-index: 1;
        }

        .bottom-text {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            text-align: center;
            font-size: large;
            z-index: 1;
            width: 100%;
            padding: 0 20px;
        }

        .modal-img {
            width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <video autoplay loop muted playsinline class="video-background">
        <source src="{{ asset('video/friends.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="overlay">
        <div class="pt-10" style="color: white; text-align:center; font-size:xx-large">
            <h1><strong>"Share Your Story, See the World"</strong></h1>
        </div>
    </div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4 w-100 max-w-md rounded-lg mx-3 my-4">
            <div class="text-center mb-4">
                <h2 class="text-3xl font-bold text-gray-700">Welcome Back to Todit!</h2>
                <p class="text-gray-500">Login to your account</p>
            </div>



            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label text-gray-700">Email address</label>
                    <input type="email" id="email" name="email" class="form-control p-2 border border-gray-300 rounded-md" placeholder="Enter your email" required value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="form-control p-2 border border-gray-300 rounded-md" placeholder="Enter your password" required>
                </div>
                <div class="mb-3 text-end">
                    <a class="text-sm text-blue-500 hover:underline">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Login
                </button>
            </form>



            <div class="mt-4 text-center">
                <form method="GET" action="{{ route('signup') }}">
                    <p class="text-gray-500">Don't have an account?
                        <button type="submit">Sign up</button>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <div class="bottom-text">
        <p>Share your thoughts, join discussions, and connect with a global community</p>
        <p>Upload content, engage in conversations, and discover new perspectives!</p>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <img src="{{ asset('images/failedlogin.png') }}" alt="Error" class="modal-img">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Login Failed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
    <script>
        $(document).ready(function() {
            $('#errorModal').modal('show'); // Show the modal if errors exist
        });
    </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>