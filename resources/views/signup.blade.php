<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign-Up Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            height: 100vh;
            position: relative;
            overflow: hidden;
            margin: 0;
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
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 50px;
        }
        .overlay h1 {
            color: white;
            text-align: center;
            font-size: xx-large;
            padding: 0 20px;
        }
        .card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.8);
            z-index: 1;
            width: 100%;
            max-width: 400px;
            padding: 10px 20px;
            margin: 10px 0;
            border-radius: 10px;
        }
        .card .form-label, .card .form-control {
            font-size: 14px;
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
    </style>
</head>
<body class="bg-gray-100">
    <video autoplay loop muted playsinline class="video-background">
        <source src="{{ asset('video/signupvid.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="overlay">
        <div class="pt-px">
            <h1><strong>"Join Us Today, Begin Your Story"</strong></h1>
        </div>
    </div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg w-100 mx-3 my-4">
            <div class="text-center mb-3">
                <h2 class="text-3xl font-bold text-gray-700">Create an Account</h2>
                <p class="text-gray-500">Join Todit today</p>
            </div>

            <form action="{{ url('/signup') }}" method="POST">
    @csrf  

    <div class="mb-2">
        <label for="name" class="form-label text-gray-700">Username</label>
        <input type="text" id="name" class="form-control p-2 border border-gray-300 rounded-md" placeholder="Enter your full name" name="name" required>
    </div>
    <div class="mb-2">
        <label for="email" class="form-label text-gray-700">Email Address</label>
        <input type="email" id="email" class="form-control p-2 border border-gray-300 rounded-md" placeholder="Enter your email" name="email" required>
    </div>
    <div class="mb-2">
        <label for="password" class="form-label text-gray-700">Password</label>
        <input type="password" id="password" class="form-control p-2 border border-gray-300 rounded-md" placeholder="Create a password" name="password" required>
    </div>
    <div class="mb-2">
        <label for="password_confirmation" class="form-label text-gray-700">Confirm Password</label>
        <input type="password" id="password_confirmation" class="form-control p-2 border border-gray-300 rounded-md" placeholder="Confirm your password" name="password_confirmation" required>
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
        Sign Up
    </button>
</form>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <div class="mt-3 text-center">
            <form method="GET" action="{{route('login') }}">
                <p class="text-gray-500">Already have an account? 
                    <button type="submit" class="text-blue-500 hover:underline">Log In</button>
                </p>
            </form>    
            </div>
        </div>
    </div>

    <div class="bottom-text">
        <p>Join a community where your voice matters</p>
        <p>Share, connect, and grow with us</p>
    </div>

    <script>
       
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
