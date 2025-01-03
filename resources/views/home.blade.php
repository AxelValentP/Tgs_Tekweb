<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toddit</title>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        .test {
            width: 100%;
            height: 100vh;
            background-image: linear-gradient(rgba(12, 3, 51, 0.4), rgba(12, 3, 51, 0.4));
            position: relative;
            padding: 0 5%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        nav {
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            padding: 20px 8%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        a {
            text-decoration: none;
            color: white;
            background-color: transparent;
        }

        button {
            text-decoration: none;
            color: white;
            background-color: transparent;
            border-style: none;
        }

        .strong {
            font-size: 15px;
            transition: all ease 0.5s;
        }

        .strong:hover {
            opacity: 0.8;
            font-size: 16px;
        }

        .content {
            text-align: center;
        }

        .content h1 {
            font-size: 50px;
        }

        .content form button {
            display: inline-block;
            font-size: 24px;
            border: 2px solid;
            border-radius: 20px;
            padding: 7px 35px;
            margin-top: 15px;
            transition: all ease 0.3s;
            background-color: transparent;
            color: white;
        }

        .content button:hover {
            font-size: 25px;
            opacity: 0.8;
            color: gray;
        }

        .content a:hover {
            font-size: 25px;
            opacity: 0.8;
        }

        .bgv {
            position: absolute;
            bottom: 0;
            right: 0;
            z-index: -1;
        }

        @media (min-aspect-ratio: 16/9) {
            .bgv {
                width: 100%;
                height: auto;
            }
        }

        @media (max-aspect-ratio: 16/9) {
            .bgv {
                width: auto;
                height: 100%;
            }
        }

        .content-wrapper{
    opacity: 0;
    transition: opacity 1s ease-in;
}
.content-wrapper.loaded {
      opacity: 1;
    }

    </style>
</head>

<body>
    <div class="test">

        <video autoplay loop muted plays-inline class="bgv">
            <source src="video/video.mp4" type="video/mp4">
        </video>

        <div class="content-wrapper">
        <nav class="bar">
            <a href="index.html">
                <h1>Toddit</h1>
            </a>
            <form method="GET" action="{{ route('login') }}">
                @csrf
                <button type="submit" class="strong">SIGN IN</button>
            </form>

            
        </nav>
        <div class="content">
            <form method="GET" action="{{ route('signup') }}">
                @csrf
                <h1 style="color: white;">Start Socializing!</h1>
                <button type="submit"> SIGN UP</button>
            </form>

        </div>
    </div>
        </div>
        

</body>
<script>
     window.addEventListener('load', function() {
      document.querySelector('.content-wrapper').classList.add('loaded');
    });
</script>

</html>