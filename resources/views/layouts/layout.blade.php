<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    @yield('styles-links')
</head>

<body>

    @yield('modals')

    <div class="container">

            <div class="container">
                <div id="notificationPanel" class="notification-panel">
                    <span class="close-notification" onclick="closeNotification()">&times;</span>
                    <h3>Notifications</h3>
                    <ul id="notificationList" class="notification-list">
                        <!-- Add your notifications here dynamically -->
                    </ul>
                </div>
            </div>

        <header>
            <div class="side-navbar">
                @yield('side-navbar')
            </div>
            <div class="top-navbar">
                <div class="logo-container">
                    <img class="logo" src="{{ asset('images/logo.jpg') }}" alt="" width="90px" height="auto"/>
                </div>
                <div>
                    <div class="top-account">
                        <img class="notif" src="{{ asset('images/notif.png') }}" alt=""
                            onclick="toggleNotificationPanel()">
                        <span class="red-dot" id="notificationDot"></span>

                        {{-- <div class="username">{{ $username }}</div> --}}
                        <div class="username">admin</div>

                        <!-- Log out -->
                        <div class="dropdown">
                            <label for="logout">
                                <img class="icon-user" id="logoutBtn" src="{{ asset('images/icon-user.png') }}" alt="">
                            </label>
                            <input type="checkbox" id="logout">
                            <div class="dropdown-menu" id="dropdownMenu">
                                {{-- <a class="dropdown-item" href="javascript:void(0);" onclick="confirmLogout()">Logout</a> --}}
                                <a class="dropdown-item" href="/"
                                    onclick="return confirm('Are you sure you want to log out?')">Log out</a>
                            </div>
                        </div>
                        {{-- <img class="icon-user" src="{{ asset('images/icon-user.png') }}" alt="" />
                        <select class="logout" name="" id="">
                            <option value=""><a href="{{ route('logout') }}">Logout</a></option>
                        </select> --}}
                    </div>
                </div>
            </div>
        </header>
        
        <main>
            @yield('main-content')
        </main>
        
        <footer>
            @yield('footer')
        </footer>

    </div>

    {{-- @yield('labas-content') --}}

    <script src="{{ asset('js/notification.js') }}"></script>
    <script src="{{ asset('js/logout.js') }}"></script>
    <script src="{{ asset('js/add.js') }}"></script>
    <script src="{{ asset('js/edit.js') }}"></script>
    <script src="{{ asset('js/easyAdd.js') }}"></script>
    {{-- <script src="{{ asset('js/easyEdit.js') }}"></script> --}}

    <script>
        const lowQuantityNotifications = {!! json_encode($lowQuantityNotifications) !!};
    </script>

    @yield('script')

</body>

</html>
