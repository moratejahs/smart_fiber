<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->


<head>
    <title>Smart Fiber</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('smartfiber.png') }}" type="image/x-icon">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/spinner.css') }}">

    <style>
        body {
            margin: 0;
            background: linear-gradient(135deg, #14BC23, #0a9517);
            overflow: hidden;
            position: relative;
            min-height: 100vh;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, #FFC000, #14BC23);
            animation: float 8s infinite ease-in-out;
            opacity: 0.4;
            filter: blur(8px);
        }

        .circle-1 {
            width: 400px;
            height: 400px;
            top: -200px;
            left: -200px;
            animation-delay: 0s;
        }

        .circle-2 {
            width: 300px;
            height: 300px;
            bottom: -150px;
            right: -150px;
            animation-delay: 2s;
            background: linear-gradient(45deg, #14BC23, #FFC000);
        }

        .circle-3 {
            width: 250px;
            height: 250px;
            top: 40%;
            left: 5%;
            animation-delay: 1s;
            background: linear-gradient(225deg, #FFC000, #ffdb66);
        }

        .circle-4 {
            width: 280px;
            height: 280px;
            bottom: 15%;
            right: 10%;
            animation-delay: 3s;
            background: linear-gradient(135deg, #0a9517, #14BC23);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg) scale(1);
            }

            50% {
                transform: translateY(-20px) rotate(5deg) scale(1.1);
            }
        }

        .card {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            padding: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid rgba(20, 188, 35, 0.2);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #14BC23;
            box-shadow: 0 0 0 3px rgba(20, 188, 35, 0.1);
        }

        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: all 0.6s;
        }

        .btn:hover:before {
            left: 100%;
        }
    </style>
</head>

<body>

    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100 justify-content-center">
            <div class="col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                            @csrf
                            <div>

                                <center>
                                    <img src="{{ asset('smartfiber.png') }}" width="200" alt="">
                                    <div>
                                        <small class="text-muted">Powered by AI Detection</small>
                                    </div>
                                </center>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Enter username">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Enter password">
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn text-white" id="loginBtn"
                                    style="background-color: #14BC23;">
                                    <span class="login-text">Log in</span>
                                    <i data-feather="loader" class="spinner d-none"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const button = document.getElementById('loginBtn');
            const spinner = button.querySelector('.spinner');
            const text = button.querySelector('.login-text');

            button.disabled = true;
            text.style.opacity = '0';
            text.style.transition = 'opacity 0.3s';
            spinner.classList.remove('d-none');
            spinner.style.position = 'absolute';
            spinner.style.marginLeft = '0';
            spinner.style.left = '50%';
            spinner.style.transform = 'translateX(-50%)';
        });
        feather.replace();
    </script>
</body>
<!-- [Body] end -->

</html>
<div class="circle circle-1"></div>
<div class="circle circle-2"></div>
<div class="circle circle-3"></div>
<div class="circle circle-4"></div>
