<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Authentication' }}</title>
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" />
        <style>
            body {
                background: linear-gradient(135deg, #004aad, #007bff);
                font-family: 'Poppins', sans-serif;
            }
            .auth-box {
                width: 420px;
                background: #fff;
                border-radius: 20px;
                padding: 20px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            }
            .auth-box img {
                width:40%;
                margin-bottom: 15px;
                text-align:end !important;
            }
            .form-control:focus {
                border-color: #007bff;
                box-shadow: none;
            }
            .btn-custom {
                background-color: #007bff;
                border: none;
                font-weight: 500;
            }
            .btn-custom:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="d-flex align-items-center justify-content-center vh-100">
            <div class="auth-box">
                <div class="logo text-end">
                    <img src="{{ asset('logo.png') }}" alt="Logo"> 
                </div>
                <h4 class="mb-4 text-start">{{ $title }}</h4>
                {{ $slot }}
                @isset($footer)
                    <p class="mt-4 mb-0 text-muted text-center">{!! $footer !!}</p>
                @endisset
            </div>
        </div>
        <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
