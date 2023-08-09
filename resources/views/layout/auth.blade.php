<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD | @yield('siteTitle', 'POS')</title>
    
<link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}">
<link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    
<link rel="stylesheet" href="{{ asset('assets/css/shared/iconly.css') }}">
<script
  src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
  crossorigin="anonymous"></script>
</head>

<body>
<div id="auth">
    <div class="row h-100">
        <div class="col-lg-6 offset-3 col-12">
            <div id="auth-left">
                <div class="auth-logo text-center">
                    <a href="#"><img src="{{ asset('assets/images/logo/logo1.svg')}}" alt="Logo"></a>
                </div>

                @yield('content')
                
            </div>
        </div>
        <!-- <div class="col-lg-4 d-none d-lg-block">
            <div id="auth-right">
    
            </div>
        </div> -->
    </div>
</div>
        

<script src="{{ asset('/assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('/assets/js/app.js') }}"></script>
@stack('scripts')

</body>

</html>
