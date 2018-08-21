<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MLM') }}</title>

    
    
 
    <!-- Styles -->
    <link href="{{ asset('css/fonts.googleapis.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    

    <script src="{{ asset('js/cytoscape.min.js') }}"></script>
    {{-- Use this line for registration only for laravel blade use the inline vuejs --}}
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>

    <style> 
        .autocomplete-result.is-active,
        .autocomplete-result:hover {
            background-color: #4AAE9B;
            color: white;
        }
    </style>
               
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                   <img src="{{ asset('storage/images/logo_xs.png') }}" class="nav-logo" alt="logo"  />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav   mr-auto"> 
                        <li class="nav-item">
                            {{-- <router-link to="/about">About</router-link> --}}
                            <a class="nav-link" href="/transactions/add">Transact</a>
                        </li>    
                        <li class="nav-item">
                            {{-- <router-link to="/about">About</router-link> --}}
                            <a class="nav-link" href="/transactions">Transactions</a>
                        </li>    
                        <li class="nav-item"  >
                                {{-- <router-link to="/about">About</router-link> --}}
                                <a class="nav-link" href="/products">Products</a>
                            </li>
                        
                        <li class="nav-item">
                            {{-- <router-link to="/about">About</router-link> --}}
                            <a class="nav-link" href="/members">Members</a>
                        </li>
                        <li class="nav-item">
                                {{-- <router-link to="/about">About</router-link> --}}
                                <a class="nav-link" href="/membership">Membership</a>
                            </li>
                        <li class="nav-item" style="display:none">
                            {{-- <router-link to="/about">About</router-link> --}}
                            <a class="nav-link" href="/about">About</a>
                        </li>
                        <li class="nav-item" style="display:none">
                            {{-- <router-link to="/about">About</router-link> --}}
                            <a class="nav-link" href="/posts">Post</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <span id="user_id" style="display:none">{{ Auth::user()->id }} </span>
                                <span id="user_name" style="display:none">{{ Auth::user()->name }} </span> 
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
          

        <main class="py-4">
            @yield('content')
        </main>

        
        <footer class="">
                
            <div class="container" style="text-align:center; padding-bottom:23px;">
                <img src="{{ asset('storage/images/footer-flower.png') }}" class="footer-flower"  /> 
                <br/>
                <a class="footer-logo" href="{{ url('/') }}">
                    <img src="{{ asset('storage/images/logo-with-ring-small3.png') }}" class="footer-logo" alt="logo" style="width:80px;"  />
                 </a>
                <p class="footer-title" style="color:white; margin: 10px auto 0px auto;">
                    LA Bio-organics Inc. <br/>
                    <span class="footer-sub-title" style="font-style: italic;">“From God - for the benefit of his children”</span> 
                </p>  
            </div>
        </footer>


    </div>
    
    
     <!-- Scripts -->
     @auth
        <script src="{{ asset('js/app.js') }}" defer></script>
     @endauth
     
</body>
</html>
