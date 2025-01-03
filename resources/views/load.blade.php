<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="style.css" />
</head>
<style>
  @import url("https://fonts.googleapis.com/css2?family=Michroma&display=swap");

  * {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
  }

  body {
    font-family: "Michroma", sans-serif;
    height: 100vh;
    
  }



  .container {
    background-color: black;
    color: #fff;
    height: 100%;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }

  .loading-page {
    position: absolute;
    top: 0;
    left: 0;
    background: linear-gradient(to right, #333333, #000000);
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    align-items: center;
    justify-content: center;
    color: #191654;
  }

  #svg {
    height: 150px;
    width: 150px;
    stroke: white;
    fill-opacity: 0;
    stroke-width: 3px;
    stroke-dasharray: 4500;
    animation: draw 8s ease;
  }

  @keyframes draw {
    0% {
      stroke-dashoffset: 4500;
    }
    100% {
      stroke-dashoffset: 0;
    }
  }

  .name-container {
    height: 30px;
    overflow: hidden;
  }

  .logo-name {
    color: #fff;
    font-size: 20px;
    letter-spacing: 12px;
    text-transform: uppercase;
    margin-left: 20px;
    font-weight: bolder;
  }
</style>

<body>
  <div class="container">
    <p>Let's Connect to The World</p>
  </div>




  <div class="loading-page">
    <!-- Updated SVG with a 'T' shape path -->
    <svg id="svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
      <!-- Simple capital T shape: top bar + vertical bar -->
      <path d="M0,0 
               L512,0 
               L512,80 
               L304,80 
               L304,512 
               L208,512 
               L208,80 
               L0,80 
               Z" />
    </svg>

    <div class="name-container">
      <div class="logo-name">toddit</div>
    </div>
  </div>



  <!-- GSAP -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/gsap.min.js"
    integrity="sha512-gmwBmiTVER57N3jYS3LinA9eb8aHrJua5iQD7yqYCKa5x6Jjc7VDVaEA0je0Lu0bP9j7tEjV3+1qUm6loO99Kw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    // Animate the logo name into view
    gsap.fromTo(".logo-name",
      { y: 50, opacity: 0 },
      { y: 0, opacity: 1, duration: 2, delay: 1.5 }
    );

    
    gsap.fromTo(".loading-page",
      { opacity: 1 },
      { opacity: 0, display: "none", duration: 1.5, delay: 4 }
    );

    // After 6 seconds, redirect to the 'explore' route
    setTimeout(() => {
      window.location.href = "{{ route('explore') }}";
    }, 6000);
    
  </script>
</body>
</html>
