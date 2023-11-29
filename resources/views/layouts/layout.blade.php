{{-- @php dd($bestSellerNotifications) @endphp --}}

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

    {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    @yield('styles-links')
</head>

<body>

    {{-- @if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Check if there are validation errors for the add modal
            const newModal = document.getElementById("newModal");
            const overlay = document.querySelector(".overlay");

            if (newModal) {
                newModal.style.display = "block";
                overlay.style.display = "block";
            }

            // Check if there are validation errors for any of the edit modals
            @foreach ($users as $user)
                @if($errors->has('name', 'email', 'password', 'role', "editModal{{$user->id}}"))
                    const editModal{{ $user->id }} = document.getElementById("editModal{{ $user->id }}");
                    const editOverlay{{ $user->id }} = document.querySelector(".editOverlay{{ $user->id }}");

                    if (editModal{{ $user->id }}) {
                        editModal{{ $user->id }}.style.display = "block";
                        editOverlay{{ $user->id }}.style.display = "block";
                    }
                @endif
            @endforeach
        });
    </script>
@endif --}}

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Open the modal if there are validation errors
                const newModal = document.getElementById("newModal");
                const overlay = document.querySelector(".overlay");
                const editModals = document.getElementById("editModal");
                const editOverlays = document.querySelector(".editOverlay");

                if (newModal && overlay) {
                    newModal.style.display = "block";
                    overlay.style.display = "block";
                }

                if (editModals && editOverlays) {
                    editModals.style.display = "block";
                    editOverlays.style.display = "block";
                }
            });
        </script>
    @endif


    @yield('modals')

    <div class="container">

        <!-- Notification -->
        <div id="notificationPanel" class="notification-panel">
            <span class="close-notification" onclick="closeNotification()">&times;</span>
            <h3 class="h3-notif">Notifications</h3>
            <ul id="notificationList" class="notification-list">
                <!-- Display low-quantity notifications -->
                @foreach ($lowQuantityNotifications as $notification)
                    <li class="notification-item">
                        {!! $notification['message'] !!}
                        <span class="dot"></span>
                    </li>
                @endforeach

                <!-- Display best-seller notifications -->
                @foreach ($bestSellerNotifications as $notification)
                    <li class="notification-item best-seller">
                        {!! $notification['message'] !!}
                        <span class="dot"></span>
                    </li>
                @endforeach
            </ul>
        </div>


        {{-- <!-- Notification -->
        <div id="notificationPanel" class="notification-panel">
            <span class="close-notification" onclick="closeNotification()">&times;</span>
            <h3 class="h3-notif">Notifications</h3>
            <ul id="notificationList" class="notification-list">
                <!-- Display low-quantity notifications -->
                @foreach ($lowQuantityNotifications as $notification)
                    <li class="notification-item">
                        {!! $notification['message'] !!}
                        <span class="dot"></span>
                    </li>
        
                    <!-- Check if there are forecast messages and display them -->
                    @if (!empty($notification['forecastMessage']))
                        @foreach (explode('<br>', $notification['forecastMessage']) as $forecast)
                            <li class="notification-item forecast-message">
                                {!! $forecast !!}
                                <span class="dot"></span>
                            </li>
                        @endforeach
                    @endif
                @endforeach
        
                <!-- Display best-seller notifications -->
                @foreach ($bestSellerNotifications as $notification)
                    <li class="notification-item best-seller">
                        {!! $notification['message'] !!}
                        <span class="dot"></span>
                    </li>
                @endforeach
            </ul>
        </div> --}}


        <header>
            <div class="side-navbar">
                @yield('side-navbar')
            </div>
            <div class="top-navbar">
                <div class="logo-container">
                    <img class="logo" src="{{ asset('images/logo.jpg') }}" alt="" width="90px"
                        height="auto" />
                </div>
                <div>
                    <div class="top-account">
                        <img class="notif" src="{{ asset('images/notif.png') }}" alt="notif img" width="100"
                            height="auto" onclick="toggleNotificationPanel()">
                        <span class="red-dot" id="notificationDot">{{ $totalNotifications }}</span>
                        {{-- <span class="red-dot" id="notificationDot">{{ count($lowQuantityNotifications) }}</span> --}}

                        {{-- <div class="username">{{ $username }}</div> --}}

                        @if (auth()->user()->role === 'Admin')
                            <div class="username">admin</div>
                        @endif

                        @if (auth()->user()->role === 'Staff')
                            <div class="username" style="margin-left: 12px; margin-right: 12px">staff</div>
                        @endif

                        <!-- Log out -->
                        <div class="dropdown">
                            <label for="logout">
                                @if (auth()->user()->role === 'Admin')
                                    <img class="icon-user" id="logoutBtn" src="{{ asset('images/icon-user.png') }}"
                                        alt="" width="100" height="auto">
                                @endif
                                @if (auth()->user()->role === 'Staff')
                                    <img class="icon-user" id="logoutBtn" src="{{ asset('images/icon-users.png') }}"
                                        alt="" width="100" height="auto">
                                @endif
                            </label>
                            <input type="checkbox" id="logout">
                            <div class="dropdown-menu" id="dropdownMenu">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                        this.closest('form').submit();"
                                        class="dropdown-item">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                                {{-- <form method="POST" action="{{ route('logout') }}" >
                                    <button type="submit" class="dropdown-item" onclick="event.preventDefault();
                                    this.closest('form').submit();">Log out</button>
                                </form> --}}
                                {{-- <a method="POST" class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="return confirm('Are you sure you want to log out?')">Log out</a> --}}
                            </div>
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
    <script src="{{ asset('js/edit.js') }}"></script>
    {{-- <script src="{{ asset('js/easyAdd.js') }}"></script> --}}

    <script>
        const lowQuantityNotifications = {!! json_encode(
            $lowQuantityNotifications,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE,
        ) !!};
    </script>

    <script>
        var bestSellerNotifications = @json($bestSellerNotifications);
    </script>


    @yield('script')

    <script src="{{ asset('js/notification.js') }}"></script>

</body>

</html>
