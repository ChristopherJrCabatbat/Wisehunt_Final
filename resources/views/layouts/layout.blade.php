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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    @yield('styles-links')
</head>

<body>

    {{-- @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Open the modal if there are validation errors
                const newModal = document.getElementById("newModal");
                const overlay = document.querySelector(".overlay");
                newModal.style.display = "block";
                overlay.style.display = "block";
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

        <!-- Inside your Blade template -->
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
                        <span class="red-dot" id="notificationDot">{{ count($lowQuantityNotifications) }}</span>
                        {{-- <span class="red-dot" id="notificationDot">{{ count($notifications ?? []) }}</span> --}}

                        {{-- <div class="username">{{ $username }}</div> --}}
                        <div class="username">admin</div>

                        <!-- Log out -->
                        <div class="dropdown">
                            <label for="logout">
                                <img class="icon-user" id="logoutBtn" src="{{ asset('images/icon-user.png') }}"
                                    alt="" width="100" height="auto">
                            </label>
                            <input type="checkbox" id="logout">
                            <div class="dropdown-menu" id="dropdownMenu">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="return confirm('Are you sure you want to log out?')">Log out</a>
                            </div>
                        </div>

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

    <script>
        window.csrfToken = "{{ csrf_token() }}";
    </script>

    <script src="{{ asset('js/logout.js') }}"></script>
    <script src="{{ asset('js/add.js') }}"></script>
    <script src="{{ asset('js/edit.js') }}"></script>
    <script src="{{ asset('js/easyAdd.js') }}"></script>

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
