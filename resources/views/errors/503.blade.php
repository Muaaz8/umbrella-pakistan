<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
      integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="style.css" />
    <title>Umbrella Maintenance</title>
    <style>
.maintenance_content h1{
    color: #08295a;
    text-shadow: 2px 3px 2px #bfd6fc;
}
.maintenance_content h4{
    color: #08295a;
    text-shadow: 2px 3px 2px #bfd6fc;
    max-width: 650px;
}
/* .social__icons i{
    font-size: 40px;
    color: #d40002;
    text-shadow: 3px 3px 2px #bfd6fc;
    margin-left: 7px;
} */
.fb:hover{
  box-shadow: 2px 4px 6px #209aee80;
    border-radius: 25%;
    transition: 0.3s;
    transform: translateY(6%);
}

.ins:hover{
  box-shadow: 2px 4px 6px #904eb3a8;
    border-radius: 25%;
    transition: 0.3s;
    transform: translateY(6%);
}
.link:hover{
  box-shadow: 2px 4px 6px #0078d4a3;
    border-radius: 25%;
    transition: 0.3s;
    transform: translateY(6%);
}
.twi:hover{
  box-shadow: 2px 4px 6px #148ee691;
    border-radius: 25%;
    transition: 0.3s;
    transform: translateY(6%);
}
body{
    background-color: #bfd6fc36;
}

    </style>
  </head>

  <body>
    <section class="d-flex justify-content-center align-items-center flex-column px-2">
      <div>
        <lottie-player
          src="https://assets1.lottiefiles.com/private_files/lf30_k0wpj0cx.json"
          background="transparent"
          speed="0.3"
          style="width: 300px; height: 300px"
          loop
          autoplay
        ></lottie-player>
      </div>
      <div class="text-center maintenance_content">
        <h1>Under Maintenance</h1>
        <h4>
          We are sorry for the inconvenience. In the meantime, please contact
          the OSC for any questions.
        </h4>
        <div class="social__icons mt-2">
            <a href="https://www.facebook.com/umbrellamd1" target="_blank"><img class="fb" src="{{ asset('assets/images/facebookmain.png') }}" alt=""></a>
            <a href="#"><img class="ins" src="{{ asset('assets/images/instagrammain.png') }}" alt=""></a>
            <a href="https://www.linkedin.com/company/umbrella-health-care-systems" target="_blank"><img class="link" src="{{ asset('assets/images/linkedinmain.png') }}" alt=""></a>
            <a href="https://twitter.com/Umbrellamd_" target="_blank"><img class="twi" src="{{ asset('assets/images/twittermain.png') }}" alt=""></a>
        </div>
      </div>
    </section>
  </body>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"
  ></script>
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</html>
