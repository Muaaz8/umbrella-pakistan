<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/new_frontend/style.css') }}" />
    <style>
        :root {
            --navy-blue: #082755;
            --navy-blue-opac: #082755cb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-page {
            height: 100vh;
            transition: none;
        }

        .content-with-login {
            height: 100%;
        }

        .left-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            justify-content: center;
            position: relative;
            z-index: 10;
            color: white;
            margin-right: 6rem;
            height: 100%;
            padding: 3rem;
        }

        .left-container>img {
            width: 300px;
            background-color: white;
            border-radius: 1rem;
            padding: 1rem;
        }

        .login-container {
            padding: 1.5rem;
        }

        .left-container .logo-heading>span {
            font-size: 3.5rem;
        }

        .left-container .logo-heading {
            font-size: 2rem;
        }

        .login-logo a {
            text-decoration: none;
            color: black;
            display: flex;
            justify-content: center;
        }

        .login-logo img {
            width: 80%;
        }

        .left-container-para {
            padding: 0 1.5rem;
        }

        .login-input {
            border: none;
            outline: none;
        }

        .input-container {
            border: 2px solid var(--blue);
            width: max-content;
            padding: 2px 10px;
            border-radius: 0.5rem;
        }

        .left-side {
            background-image: url("/assets/new_frontend/22035.jpg");
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            height: 100%;
            border-radius: 0 50% 50% 0;
        }

        .left-side::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--navy-blue-opac);
            border-radius: 0 50% 50% 0;
        }

        .login-page .form-control {
            border: none;
            border-bottom: var(--bs-border-width) solid var(--bs-border-color);
            border-radius: 0;
            padding: 1.25rem 3rem 0 0.25rem !important;
        }

        .login-page .form-control:focus {
            color: var(--bs-body-color);
            background-color: var(--bs-body-bg);
            border-bottom: 2px solid var(--blue);
            outline: 0;
            box-shadow: none;
        }

        .login-page .form-floating>label {
            padding: 1rem 0;
        }

        .back-btn {
            display: flex;
            align-items: center;
            color: white;
        }

        .back-btn span {
            margin-left: 1rem
        }

        .back-btn i {
            font-size: 2rem;
        }

        .login-page a {
            text-decoration: none;
        }

        .login-page .password-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .login-input-fields .password-container .checkbox-container {
            display: flex;
            align-items: center;
        }

        .remove-pass {
            position: absolute;
            top: 50%;
            right: 2%;
            cursor: pointer;
            z-index: 10;
            background: white;
            color: gray;
        }

        .remember-check {
            outline: none;
        }

        .left-container>p {
            text-align: center;
            padding: 0 1.5rem;
        }

        @media (min-width: 1400px) {
            .form-floating>.form-control-plaintext~label, .form-floating>.form-control:focus~label, .form-floating>.form-control:not(:placeholder-shown)~label, .form-floating>.form-select~label {
                color: rgba(var(--bs-body-color-rgb), .65);
                transform: scale(.9) translateY(-1.1rem) translateX(.15rem);
            }

            .login-input-fields input {
                font-size: 1.25rem;
            }

            .left-container > h2 {
               font-size: 2.25rem;
            }

            .left-container .logo-heading>span {
                font-size: 3.75rem;
            }

            .left-container .logo-heading {
                font-size: 2.2rem;
            }

            .left-container>p {
                font-size: 1.1rem;
            }

            .login-logo img {
                width: 90%;
                max-width: 900px;
            }

            .login label {
                font-size: 1.15rem;
            }

            .login-heading > h1 {
                font-size: 2.75rem;
            }

            .register-account > span, .register-account > a, .password-container > a {
                font-size: 1.15rem;
            }

            .login-input-fields i {
                font-size: 1.2rem;
            }
        }

        @media screen and (max-width: 992px) {
            .login-container {
                padding: 1.5rem;
            }

            .left-container {
                padding: 2rem;
            }

            .left-container>h2 {
                font-size: 1.5rem;
            }

            .left-container .logo-heading>span {
                font-size: 2.5rem;
            }

            .left-container .logo-heading {
                font-size: 1.2rem;
            }

            .left-container .left-container-para {
                padding: 0 0.8rem;
                font-size: 0.8rem;
            }

            .password-container a,
            .checkbox-container>label {
                font-size: 0.9rem;
            }
        }

        @media screen and (max-width: 768px) {
            .login-logo img {
                width: 100%;
            }

            .login-container {
                padding: 0.5rem;
            }

            .login-section {
                padding-right: 1rem !important;
            }

            .left-container {
                margin-right: 3rem;
                padding: 1rem;
            }

            .left-container .left-container-para {
                padding: 0 0;
            }

            .register-account {
                font-size: 0.8rem;
            }

            .password-container a,
            .checkbox-container>label {
                font-size: 0.8rem;
            }

            .left-container>h2 {
                font-size: 1.3rem;
            }

            .left-container .logo-heading>span {
                font-size: 2rem;
            }

            .left-container .logo-heading {
                font-size: 1rem;
            }

            .back-btn i {
                font-size: 1.5rem;
            }
        }

        @media screen and (max-width: 576px) {
            .left-container>h2 {
                display: none;
            }

            .left-container {
                display: none;
            }

            .left-side {
                top: 0;
                height: 30%;
                border-radius: 0 0 50% 50%;
            }

            .left-side:after {
                border-radius: 0 0 50% 50%;
            }

            .login-section {
                height: 70%;
            }

            .left-container {
                margin-right: 0;
            }

            .left-container>.logo-heading {
                font-size: 1.6rem;
            }

            .left-container>.logo-heading>span {
                font-size: 3rem;
            }

            .login-logo img {
                width: 90%;
            }

            .login-container {
                height: 50%;
            }
        }
        .back-btn {
            position: absolute;
            top: 3%;
            left: 3%;
            z-index: 100;
            cursor: pointer;
        }
        .back_white{
            color: white;
            z-index: 101;
        }
    </style>
</head>

<body>
    <main class="login-page container-fluid">
        <section class="content-with-login row align-items-center justify-content-center">
            <div class="left-side col-12 col-sm-6 position-relative">
                <div class="goBackBtn">
                    <div onclick="window.location.href='/'" class="back-btn">
                        <i class="fas fa-arrow-left back_white"></i>
                        <span>Go to Homepage</span>
                    </div>
                </div>
                <div class="left-container">
                    <h2>Welcome to</h2>
                    <h1 class="logo-heading text-center">
                        <span class="text-center">COMMUNITY</span><br />
                        HEALTHCARE CLINICS
                    </h1>
                    @php
                    $page = DB::table('pages')->where('url', '/login')->first();
                    $section = DB::table('section')
                    ->where('page_id', $page->id)
                    ->where('section_name', 'side-text')
                    ->where('sequence_no', '1')
                    ->first();
                    $top_content = DB::table('content')
                    ->where('section_id', $section->id)
                    ->first();
                    $image_content = DB::table('images_content')
                    ->where('section_id', $section->id)
                    ->first();
                    @endphp
                    @if ($top_content)
                    {!! $top_content->content !!}
                    @else
                    <p class="text-center left-container-para">
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsum
                        consequatur commodi facere officia aliquam deserunt similique
                        praesentium vero sed, illum, porro fugit minima magni!
                    </p>
                    @endif
                </div>
            </div>
            <div class="login-section ps-3 pe-5 position-relative z-3 col-12 col-sm-6">
                <div class="login-container">
                    <div class="login d-flex flex-column gap-2">
                        <div class="login-header d-flex justify-content-between align-items-center">
                            <div class="login-logo d-flex align-items-center justify-content-center w-100">
                                <a href="https://www.communityhealthcareclinics.com/"><img src="{{ asset('assets/new_frontend/logo.png') }}" alt="logo" /></a>
                            </div>
                        </div>
                        <div class="login-heading">
                            <h1 class="text-center">Login</h1>
                        </div>
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="login-input-fields d-flex flex-column gap-2">
                                @if ($errors->any())
                                    <div class="alert alert-danger p-2">
                                        <ul class="m-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <input type="hidden" id="timezone" name="timezone" value="" />
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="login" id="floatingInput"
                                        placeholder="name@example.com" />
                                    <label for="floatingInput">Email address</label>
                                </div>
                                <div class="form-floating position-relative">
                                    <input type="password" class="form-control" name="password" id="floatingPassword"
                                        placeholder="Password" />
                                    <label for="floatingPassword">Password</label>
                                    <i class="remove-pass fa-regular fa-eye-slash"></i>
                                </div>
                                <div class="password-container">
                                    <div class="checkbox-container">
                                        <input type="checkbox" name="remember" id="remember"
                                            class="remember-check" /><label for="remember" class="ms-2">Remember
                                            me</label>
                                    </div>
                                    <a href="{{ url('/password/reset') }}">Forgot Password?</a>
                                </div>
                                <button type="submit" class="btn btn-primary">Login</button>
                                <div class="register-account">
                                    <span>Don't have an account?</span>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Register
                                        here</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Registration Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-login-reg-btn my-3">
                            <a href="{{ route('pat_register') }}"> REGISTER AS A PATIENT</a>
                            <a href="{{ route('doc_register') }}">REGISTER AS A DOCTOR </a>
                        </div>
                        <div class="login-or-sec">
                            <hr />
                            OR
                            <hr />
                        </div>
                        <div>
                            <p>Already have account?</p>
                            <a href="{{ route('login') }}">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        const password = document.querySelector("#floatingPassword");
        const eye = document.querySelector(".remove-pass");

        eye.addEventListener("click", () => {
            if (password.getAttribute("type") === "password") {
                password.setAttribute("type", "text");
                eye.classList.add("fa-eye");
                eye.classList.remove("fa-eye-slash");
            } else {
                password.setAttribute("type", "password");
                eye.classList.add("fa-eye-slash");
                eye.classList.remove("fa-eye");
            }
        });
    </script>
</body>

</html>
