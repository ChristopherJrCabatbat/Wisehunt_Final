<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    @yield('styles-links')
</head>

<body>

    @if (session('staff'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                alert("{{ session('staff') }}");
            });
        </script>
    @endif
    @if (session('admin'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                alert("{{ session('admin') }}");
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Open the modal if there are validation errors
                const newModal = document.getElementById("newModal");
                const overlay = document.querySelector(".overlay");
                if (newModal && overlay) {
                    newModal.style.display = "block";
                    overlay.style.display = "block";
                }
            });
        </script>
    @endif


    @yield('modals')

    <div class="container">

        <!-- Notification -->
        <div id="notificationPanel" class="notification-panel"
            data-route="{{ route('admin.productEdit', ['id' => '__productId__']) }}">
            <span class="close-notification" onclick="closeNotification()">&times;</span>
            <h3 class="h3-notif">Notifications</h3>
            <ul id="notificationList" class="notification-list">
                <!-- Display low-quantity notifications -->
                @foreach ($lowQuantityNotifications as $notification)
                    <li class="notification-item" onclick="navigateToProductView('{{ $notification['productId'] }}')">
                        {!! $notification['message'] !!}
                        <span class="dot"></span>
                    </li>
                @endforeach

                <!-- Display best-seller notifications -->
                @foreach ($bestSellerNotifications as $notifications)
                    <li class="notification-item best-seller">
                        {!! $notifications['message'] !!}
                        <span class="dot"></span>
                    </li>
                @endforeach
            </ul>
        </div>


        <header>
            <div class="side-navbar">
                @yield('side-navbar')

                {{-- <div class="logout-bottom-left dropdown">
                    @if (auth()->user()->role === 'Admin')
                        <div class="logout-container" id="logoutBtn">
                            Log out <i class="fas fa-sign-out"></i>
                        </div>
                    @endif
                    @if (auth()->user()->role === 'Staff')
                        <div class="logout-container-staff" id="logoutBtn">
                            Log out <i class="fas fa-sign-out"></i>
                        </div>
                    @endif
                    <input type="checkbox" id="logout">

                    @if (auth()->user()->role === 'Admin')
                        <div class="dropdown-menu" id="dropdownMenu">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                            this.closest('form').submit();"
                                    class="dropdown-item">
                                    {{ __('Confirm Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    @endif

                    @if (auth()->user()->role === 'Staff')
                        <div class="dropdown-menu-staff" id="dropdownMenu">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                            this.closest('form').submit();"
                                    class="dropdown-item">
                                    {{ __('Confirm Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    @endif
                </div> --}}


            </div>
            <div class="top-navbar">
                <div class="logo-container">
                    <img class="logo" src="{{ asset('images/logo-blue.jpg') }}" alt="" width="90"
                        height="auto" />
                    <div class="company">WISEHUNT COMPANY</div> <img class="globo"
                        src="{{ asset('images/globo.png') }}" alt="" width="90" height="auto">
                </div>
                <div>
                    <div class="top-account">
                        <img class="notif" src="{{ asset('images/notif.png') }}" alt="notif img" width="100"
                            height="auto" onclick="toggleNotificationPanel()">
                        @if (auth()->user()->role === 'Admin')
                            <span class="red-dot" id="notificationDot">{{ $totalNotifications }}</span>
                        @endif
                        @if (auth()->user()->role === 'Staff')
                            <span class="red-dot-staff" id="notificationDot">{{ $totalNotifications }}</span>
                        @endif

                        {{-- <span class="red-dot" id="notificationDot">{{ count($lowQuantityNotifications) }}</span> --}}

                        {{-- <div class="username">{{ $username }}</div> --}}

                        @if (auth()->user()->role === 'Admin')
                            <div class="username">Admin</div>
                        @endif

                        @if (auth()->user()->role === 'Staff')
                            <div class="username" style="margin-left: 12px; margin-right: 12px">Staff</div>
                        @endif


                        <!-- User Profile Dropdown -->
                        <div class="user-photo-dropdown">
                            <img class="icon-user" src="{{ asset($userPhoto) }}" alt="" width="100"
                                height="auto">

                            @if (auth()->user()->role === 'Admin')
                            <div class="dropdown-content">
                                <div class="edit-logout">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="{{ route('admin.userEdit', auth()->user()->id) }}">Edit user profile</a>
                                        <a href="javascript:void(0);" 
                                           onclick="event.preventDefault(); 
                                           if(confirm('Are you sure you want to log out?')) this.closest('form').submit();"
                                           class="dropdown-item">Log out <i class="fas fa-sign-out"></i></a>
                                    </form>
                                </div>
                            </div>
                            
                            @endif
                           
                            @if (auth()->user()->role === 'Staff')
                                <div class="dropdown-content">
                                    <div class="edit-logout">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a href="{{ route('staff.userEdit', auth()->user()->id) }}">Edit user
                                                profile</a>
                                                <a href="javascript:void(0);" 
                                                onclick="event.preventDefault(); 
                                                if(confirm('Are you sure you want to log out?')) this.closest('form').submit();"
                                                class="dropdown-item">Log out <i class="fas fa-sign-out"></i></a>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            {{-- @if (auth()->user()->role === 'Staff')
                                <div class="dropdown-content">
                                    <a href="{{ route('staff.userEdit', auth()->user()->id) }}">Edit user profile</a>
                                </div>
                            @endif --}}

                        </div>


                    </div>
                </div>
            </div>
        </header>

        <main id="main">
            @yield('main-content')
        </main>

        <footer>
            @yield('footer')
        </footer>

    </div>

    <script>
        window.csrfToken = "{{ csrf_token() }}";
    </script>

    <script src="{{ asset('js/logout.js') }}"></script>
    <script src="{{ asset('js/add.js') }}"></script>

    <script>
        const lowQuantityNotifications = {!! json_encode(
            $lowQuantityNotifications,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE,
        ) !!};
    </script>

    <script>
        var bestSellerNotifications = @json($bestSellerNotifications);
    </script>


    {{-- Edit Profile --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var userPhoto = document.querySelector('.icon-user');
            var dropdownContent = document.querySelector('.dropdown-content');

            // Toggle dropdown display on photo click
            userPhoto.addEventListener('click', function(event) {
                dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' :
                    'block';
                event.stopPropagation(); // Prevent click from immediately propagating to document
            });

            // Clicking outside the dropdown hides it
            document.addEventListener('click', function(event) {
                if (event.target !== userPhoto) {
                    dropdownContent.style.display = 'none';
                }
            });
        });
    </script>



    @yield('script')

    <script src="{{ asset('js/notification.js') }}"></script>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
    @if (session('message'))
        <script>
            alert('{{ session('message') }}');
        </script>
    @endif

</body>

</html>
