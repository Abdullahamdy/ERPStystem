<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ (app()->getLocale() === 'ar') ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'POS') }}</title> 

    @include('layouts.partials.css')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <style>
      html[dir="ltr"] .right-col-content {
        text-align: left;
        display: block !important; /* Or any other desired layout */
    }

     body {
        background-image: url('/public/back2.jpg');
        background-size: cover;
        background-position: center;
        position: relative;
        height: 100vh;
     }

    body::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.5); /* White with 50% transparency */
        z-index: 1;
    }

    .right-col-content {
        background-color: rgba(255, 255, 255, 0.8);
        padding: 5% 5% !important;
        z-index: 2;
        position: relative;
        z-index:99999;
        border-radius: 10px;
    }
.select2-container--default .select2-selection--single, .select2-selection .select2-selection--single,.form-control {
            z-index:99999 !important;

}
.btn.btn-flat {
    border-radius: 0;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
    border-width: 1px;
    border-radius: 20px;
}
btn-login {
    padding: 15px 0px 15px !important;
    margin-right: 0% !important;
}
.col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9 {
        z-index: 9999999999999;
    }

    /* Handle direction: apply RTL styling when needed */
    html[dir="rtl"] .right-col-content {
        text-align: right;
    }

    html[dir="rtl"] .form-control-feedback {
        left: auto;
        right: 15px; /* Move icon to the right in RTL */
    }

    html[dir="ltr"] .right-col-content {
        text-align: left;
        display: block !important; /* Or any other desired layout */
    }

    .content {
        position: relative;
        z-index: 2;
    }
    
    @media (max-width: 767px) {
    img {
        width: 50%;
        margin-top: 10%;
        float:none !important;
    }
    .right-col-content {
        padding: 8% 5% !important;
        margin-right: 55px;
    }
    .btn-login {
        margin-right:0px !important;
    }
}

    @media (max-width: 767px) {
        img {
            width: 50%; /* Adjust logo size for smaller screens */
            margin-top: 10%; /* Adjust top margin for smaller screens */
        }
 .header {
            background-color: #fff;
            padding: 15px;
            display: grid !important;
            align-items: center;
            justify-content: space-between;
            z-index:99999999999;
            border-bottom: 1px solid #ccc;
        }
        .select2_register {
            width: 100%; /* Full width dropdown on mobile */
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .right-col-content {
            padding: 15% 10% !important; /* Medium screen adjustments */
        }
    }

    .login-page-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        position: relative;
        background-size: cover;
        background-position: center;
    }

    .login-form-container {
        background-color: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        width: 350px;
        text-align: center;
        z-index: 2;
        position: relative;
    }

    .login-header img.login-logo {
        width: 150px;
        margin-bottom: 20px;
    }

    .form-group {
        position: relative;
        margin-bottom: 20px;
    }

    .form-control {
        width: 100%;
        height: 45px;
        padding-left: 40px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .form-control-feedback {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: #888;
    }

    .btn-login {
        background-color: #007bff;
        border: none;
        padding: 10px;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        width: 100%;
    }

    .captcha {
        margin-bottom: 20px;
    }

    .demo-shops {
        padding-top: 20px;
        text-align: center;
    }

    body {
        font-family: 'Arial', sans-serif;
    }

    @media only screen and (max-width: 600px) {
        .login-form-container {
            width: 100%;
            padding: 15px;
        }

        .login-header img.login-logo {
            width: 120px;
        }
    }
 .form-header, .right-col a, .text-white a,   .right-col label {
    color: #08c !important;
    font-family: system-ui;
    font-size: larger;
}
  html[dir="ltr"] .right-col-content {
        text-align: left;
        display: block !important; /* Or any other desired layout */
    }


 .header {
            background-color: #fff;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index:99999999999;
            border-bottom: 1px solid #ccc;
        }
        .header .left-section {
            display: flex;
            align-items: center;
            z-index: 99999999;
        }
        .header .left-section img {
            width: 50px;
            margin-right: 15px;
        }
        .header .left-section .language-select {
            margin-right: 20px;
        }
        .header .right-section {
            font-size: 14px;
            color: #333;
            z-index: 99999999;
        }
        .header .right-section span {
            margin-left: 10px;
        }
        .header .right-section .current-time {
            color: #007bff;
            font-weight: bold;
        }
</style>

</head>

<body>
  <header class="header">
        <!-- Left Section (Logo and Language Selector) -->
        <div class="left-section">
            <img src="/public/logo.png" alt="Logo">
            <select class="form-control input-sm language-select" id="change_lang">
                @foreach(config('constants.langs') as $key => $val)
                    <option value="{{$key}}" 
                        @if( (empty(request()->lang) && config('app.locale') == $key) 
                        || request()->lang == $key) 
                            selected 
                        @endif
                    >
                        {{$val['full_name']}}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- Right Section (Information) -->
        <div class="right-section">
    <!-- Display current date and time based on selected language -->
    @if(app()->getLocale() == 'ar')
        <span>{{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('l j F Y') }}</span> <!-- Arabic Date -->
        <span class="current-time">{{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('h:i A') }}</span> <!-- Arabic Time -->
        <span>الرياض</span>
    @else
        <span>{{ \Carbon\Carbon::now()->locale('en')->translatedFormat('l j F Y') }}</span> <!-- English Date -->
        <span class="current-time">{{ \Carbon\Carbon::now()->locale('en')->translatedFormat('h:i A') }}</span> <!-- English Time -->
        <span>Riyadh</span>
    @endif
</div>

    </header>

    @inject('request', 'Illuminate\Http\Request')
    @if (session('status'))
        <input type="hidden" id="status_span" data-status="{{ session('status.success') }}" data-msg="{{ session('status.msg') }}">
    @endif
    <div class="container-fluid">
        <div class="row eq-height-row">
            <div class="col-md-7 col-sm-7 col-xs-12 right-col eq-height-col" >
                <div class="row">
                   

                    <div class="col-md-3 col-xs-4" style="text-align: left; display:none">
                        <select class="form-control input-sm select2_register" id="change_lang" style="margin: 10px;">
                            @foreach(config('constants.langs') as $key => $val)
                                <option value="{{$key}}" 
                                    @if( (empty(request()->lang) && config('app.locale') == $key) 
                                    || request()->lang == $key) 
                                        selected 
                                    @endif
                                >
                                    {{$val['full_name']}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 col-xs-8" style="text-align: right;padding-top: 10px;">
                        @if(!($request->segment(1) == 'business' && $request->segment(2) == 'register'))
                            <!-- Register Url -->
                            @if(config('constants.allow_registration'))
                                <a href="{{ route('business.getRegister') }}@if(!empty(request()->lang)){{'?lang=' . request()->lang}} @endif" class="btn bg-maroon btn-flat" ><b>{{ __('business.not_yet_registered')}}</b> {{ __('business.register_now') }}</a>
                                <!-- pricing url -->
                                @if(Route::has('pricing') && config('app.env') != 'demo' && $request->segment(1) != 'pricing')
                                    &nbsp; <a href="{{ action('\Modules\Superadmin\Http\Controllers\PricingController@index') }}">@lang('superadmin::lang.pricing')</a>
                                @endif
                            @endif
                        @endif

                        @if($request->segment(1) != 'login')
                            &nbsp; &nbsp;<span class="text-white">{{ __('business.already_registered')}} </span><a href="{{ action('Auth\LoginController@login') }}@if(!empty(request()->lang)){{'?lang=' . request()->lang}} @endif">{{ __('business.sign_in') }}</a>
                        @endif
                    </div>
                
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @include('layouts.partials.javascripts')
    
    <!-- Scripts -->
    <script src="{{ asset('js/login.js?v=' . $asset_v) }}"></script>
    
    @yield('javascript')

    <script type="text/javascript">
        $(document).ready(function(){
            $('.select2_register').select2();

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            // Trigger language change
            $('#change_lang').change(function() {
                var selectedLang = $(this).val();
                var currentUrl = window.location.href;
                window.location.href = currentUrl.split('?')[0] + '?lang=' + selectedLang;
            });
        });
    </script>
</body>

</html>
