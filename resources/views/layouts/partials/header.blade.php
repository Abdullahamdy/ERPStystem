@inject('request', 'Illuminate\Http\Request')
<!-- Main Header -->
<header class="main-header no-print">
    <!-- Logo -->
    <a href="{{route('home')}}" class="logo">
        <img src="/public/logo.png" alt="Logo" class="logo-img">
        <span class="logo-lg">{{ Session::get('business.name') }} <i class="fa fa-circle text-success" id="online_indicator"></i></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top header" role="navigation" style="background-color: #eaedff !important; border-bottom: 1px solid #ccc;">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" style="color: #007bff;">
            &#9776;
            <span class="sr-only">Toggle navigation</span>
        </a>

        @if(Module::has('Superadmin'))
            @includeIf('superadmin::layouts.partials.active_subscription')
        @endif

        @if(!empty(session('previous_user_id')) && !empty(session('previous_username')))
            <a href="{{route('sign-in-as-user', session('previous_user_id'))}}" class="btn btn-flat btn-danger m-8 btn-sm mt-10">
                <i class="fas fa-undo"></i> @lang('lang_v1.back_to_username', ['username' => session('previous_username')] )
            </a>
        @endif

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu right-section">
            <!-- Search bar -->
            <form class="navbar-form navbar-left" role="search" style="margin-left: 20px;">
                <div class="form-group has-search">
                    <span class="fa fa-search form-control-feedback"></span>
                    <input type="text" class="form-control search-bar" placeholder="@lang('lang_v1.search')">
                </div>
            </form>

            @if(Module::has('Essentials'))
                @includeIf('essentials::layouts.partials.header_part')
            @endif

            <!-- Shortcut button -->
            <div class="btn-group">
                <button id="header_shortcut_dropdown" type="button" class="btn btn-blue dropdown-toggle btn-flat pull-left m-8 btn-sm mt-10" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-plus-circle fa-lg"></i>
                </button>
                <ul class="dropdown-menu">
                    @if(config('app.env') != 'demo')
                        <li><a href="{{route('calendar')}}">
                            <i class="fas fa-calendar-alt" aria-hidden="true"></i> @lang('lang_v1.calendar')
                        </a></li>
                    @endif
                    @if(Module::has('Essentials'))
                        <li><a href="#" class="btn-modal" data-href="{{action('\Modules\Essentials\Http\Controllers\ToDoController@create')}}" data-container="#task_modal">
                            <i class="fas fa-clipboard-check" aria-hidden="true"></i> @lang('essentials::lang.add_to_do')
                        </a></li>
                    @endif
                    <!-- Help Button -->
                    @if(auth()->user()->hasRole('Admin#' . auth()->user()->business_id))
                        <!--<li><a id="start_tour" href="#">-->
                        <!--    <i class="fas fa-question-circle" aria-hidden="true"></i> @lang('lang_v1.application_tour')-->
                        <!--</a></li>-->
                    @endif
                </ul>
            </div>

            <!-- Calculator button -->
            <button id="btnCalculator" title="@lang('lang_v1.calculator')" type="button" class="btn btn-blue btn-flat pull-left m-8 btn-sm mt-10 popover-default hidden-xs" data-toggle="popover" data-trigger="click" data-content='@include("layouts.partials.calculator")' data-html="true" data-placement="bottom">
                <strong><i class="fa fa-calculator fa-lg" aria-hidden="true"></i></strong>
            </button>

            @if($request->segment(1) == 'pos')
                @can('view_cash_register')
                <button type="button" id="register_details" title="{{ __('cash_register.register_details') }}" data-toggle="tooltip" data-placement="bottom" class="btn btn-blue btn-flat pull-left m-8 btn-sm mt-10 btn-modal" data-container=".register_details_modal" data-href="{{ action('CashRegisterController@getRegisterDetails')}}">
                    <strong><i class="fa fa-briefcase fa-lg" aria-hidden="true"></i></strong>
                </button>
                @endcan
                @can('close_cash_register')
                <button type="button" id="close_register" title="{{ __('cash_register.close_register') }}" data-toggle="tooltip" data-placement="bottom" class="btn btn-danger btn-flat pull-left m-8 btn-sm mt-10 btn-modal" data-container=".close_register_modal" data-href="{{ action('CashRegisterController@getCloseRegister')}}">
                    <strong><i class="fa fa-window-close fa-lg"></i></strong>
                </button>
                @endcan
            @endif

            @if(in_array('pos_sale', $enabled_modules))
                @can('sell.create')
                <a href="{{action('SellPosController@create')}}" title="@lang('sale.pos_sale')" data-toggle="tooltip" data-placement="bottom" class="btn btn-flat pull-left m-8 btn-sm mt-10 btn-blue">
                    <strong><i class="fa fa-th-large"></i> &nbsp; @lang('sale.pos_sale')</strong>
                </a>
                @endcan
            @endif

            @if(Module::has('Repair'))
                @includeIf('repair::layouts.partials.header')
            @endif

            @can('profit_loss_report.view')
                <button type="button" id="view_todays_profit" title="{{ __('home.todays_profit') }}" data-toggle="tooltip" data-placement="bottom" class="btn btn-blue btn-flat pull-left m-8 btn-sm mt-10">
                    <strong><i class="fas fa-money-bill-alt fa-lg"></i></strong>
                </button>
            @endcan

            <div class="m-8 pull-left mt-15 hidden-xs" style="color: #007bff;"><strong>{{ @format_date('now') }}</strong></div>

            <ul class="nav navbar-nav">
                @include('layouts.partials.header-notifications')
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #007bff;">
                        <!-- The user image in the navbar-->
                        @php
                            $profile_photo = auth()->user()->media;
                        @endphp
                        @if(!empty($profile_photo))
                            <img src="{{$profile_photo->display_url}}" class="user-image" alt="User Image">
                        @endif
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span>{{ Auth::User()->first_name }} {{ Auth::User()->last_name }}</span>
                        @if(!empty($profile_photo))
                            <!-- Add user image after username -->
                            <img src="{{$profile_photo->display_url}}" class="username-image" alt="User Image">
                        @endif
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            @if(!empty(Session::get('business.logo')))
                                <img src="{{ asset('uploads/business_logos/' . Session::get('business.logo') ) }}" alt="Logo">
                            @endif
                            <p>
                                {{ Auth::User()->first_name }} {{ Auth::User()->last_name }}
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{action('UserController@getProfile')}}" class="btn btn-default btn-flat">@lang('lang_v1.profile')</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{action('Auth\LoginController@logout')}}" class="btn btn-default btn-flat">@lang('lang_v1.sign_out')</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
            </ul>
        </div>
                            <div class="form-group pull-right">
                          <div class="input-group">
                            <button type="button" class="btn btn-primary" id="dashboard_date_filter">
                              <span>
                                <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                              </span>
                              <i class="fa fa-caret-down"></i>
                            </button>
                          </div>
                    </div>

    </nav>
</header>

<style>
.pull-right {
    float: left !important;
    margin-top: 12px;
}
    /* Blue button style */
    .btn-blue {
        background-color: #eaedff;
        border: none;
        color: #007bff;
        padding: 10px;
        border-radius: 5px;
        margin-left: 10px;
    }
    .btn-success {
        background: #eaedff !important;
        color: #007bff !important;
        border: none !important;
    }
    .btn-blue:hover {
        background-color: #d1d1d1;
    }
    .skin-blue-light .main-header .navbar .sidebar-toggle:hover {
    background-color: #ffffff !important;
}
    .bg-blue {
        background-color: #eaedff !important;
        color: #007bff !important;
    }
    .btn-blue:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }

    /* White header background */
    .header {
        background-color: #fff !important;
        border-bottom: 1px solid #ccc;
    }

    /* Blue for navbar elements */
    .navbar-custom-menu .nav > li > a {
        color: #007bff !important;
    }

    /* Sidebar toggle button blue */
    .sidebar-toggle {
        color: #007bff !important;
    }

    .user-menu .user-footer a {
        color: #007bff;
    }

    /* Logo styling */
    .logo-img {
        width: 85px;
        height: auto;
        margin-right: 10px;
        display: inline-block;
    }
    .main-header .logo img {
        max-height: 75px !important;
    }

    /* Search bar container styling */
    .has-search {
        position: relative;
    }

    /* Search bar input styling */
    .search-bar {
        border-radius: 5px;
        border: 1px solid #ccc;
        padding-left: 35px; /* Padding to ensure space for the icon */
        width: 200px;
    }

    /* Search icon styling */
    .form-control-feedback {
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        color: #aaa;
        pointer-events: none;
    }

    /* Username image styling */
    .username-image {
        width: 30px;
        height: 30px;
        margin-left: 10px;
        border-radius: 50%;
        vertical-align: middle;
    }
</style>
