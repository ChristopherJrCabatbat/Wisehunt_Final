@extends('../layouts.layout')

@section('title', 'User')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-supplier-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-styles.css') }}">
@endsection

@section('modals')

    <div class="overlay"></div>

    {{-- Add Modal --}}
    <div id="newModal" class="modal">
        <div class="modal-content">
            <span class="close closeModal">&times;</span>

            <form class="modal-form" action="{{ route('admin.userStore') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <center>
                    <h2 style="margin: 0%; color:#333;"><i class="fa-regular fa-plus"></i>Add User</h2>
                </center>
                <label class="modal-tops" for="">Name:</label>
                <input required autofocus type="text" name="name" id="autofocus" value="{{ old('name') }}" />

                <label for="">Email:</label>
                <input required type="email" name="email" id="" value="{{ old('email') }}" />
                @if ($errors->has('email'))
                    <div class="user-text-danger">{{ $errors->first('email') }}</div>
                @endif

                <label for="">Password:</label>
                <input required type="password" name="password" />
                @if ($errors->has('password'))
                    <div class="user-text-danger">{{ $errors->first('password') }}</div>
                @endif

                <label for="">Confirm Password:</label>
                <input required type="password" name="password_confirmation" id="">

                <label for="">Image:</label>
                <div class="input_container">
                    <input type="file" name="photo" id="fileUpload">
                </div>

                <label for="">Role:</label>
                <select required name="role" id="" class="">
                    <option disabled selected value="">-- Select Role --</option>
                    <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Staff" {{ old('role') === 'Staff' ? 'selected' : '' }}>Staff</option>
                </select>

                <input class="add" type="submit" value="Add" />
            </form>
        </div>
    </div>

@endsection

@section('side-navbar')

    <ul>
        <li>
            <div class="dashboard-container">
                <a class="sidebar top" href="{{ route('admin.dashboard') }}">
                    <img class="icons-taas" src="{{ asset('images/dashboard-xxl.png') }}" alt="">
                    DASHBOARD</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.product') }}">
                    <img src="{{ asset('images/product-xxl.png') }}" class="product-i" alt="">
                    PRODUCT</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.transaction') }}">
                    <img src="{{ asset('images/transaction.png') }}" class="transaction-i" alt="">
                    TRANSACTION</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.customer') }}">
                    <img src="{{ asset('images/customer.png') }}" class="customer-i" alt="">
                    CUSTOMER</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.supplier') }}">
                    <img src="{{ asset('images/supplier.png') }}" class="supplier-i" alt="">
                    SUPPLIER</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.delivery') }}">
                    <img src="{{ asset('images/delivery.png') }}" class="delivery-i" alt="">
                    DELIVERY</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                    <a class="sidebar active" href="{{ route('admin.user') }}">
                        <i class="fa-solid fa-circle-user user-i" style="color: #ffffff;"></i>
                        USER</a>

            </div>
        </li>
    </ul>

@endsection

@section('main-content')

    <div class="content">
        <div class="taas">
            <form id="addCustomerForm">
                <button class="add" type="button" id="newButton">Add User</button>
            </form>
        </div>
        <div class="table">
            <table>

                <tr>
                    <th colspan="11" class="table-th">USERS</th>
                </tr>

                @php
                    // Calculate the initial row number based on the current page
                    $rowNumber = ($users->currentPage() - 1) * $users->perPage() + 1;
                @endphp

                <tr>
                    {{-- <th>No.</th> --}}
                    <th>Name</th>
                    <th>Email</th>
                    {{-- <th>Password</th> --}}
                    <th>Photo</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>

                <tbody>
                    @if ($users->isEmpty())
                        <tr>
                            <td colspan="7">No data found.</td>
                        </tr>
                    @else
                        @foreach ($users as $user)
                            <tr>
                                {{-- <td>{{ $rowNumber++ }}</td> --}}
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                {{-- <td>{{ Crypt::decryptString($user->password) }}</td> --}}
                                <td>
                                    <img src="{{ asset($user->photo) }}" alt="{{ $user->name }}" width="auto"
                                        height="50px" style="background-color: transparent">
                                </td>
                                <td>{{ $user->role }}</td>

                                <td class="actions">
                                    <div class="actions-container">
                                        <form action="{{ route('admin.userEdit', $user->id) }}" method="POST">
                                            @csrf
                                            @method('GET')
                                            <button type="submit" class="edit editButton" id="edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.userDestroy', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure you want to delete this?')"
                                                type="submit" class="delete" id="delete">
                                                <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    @endif
                </tbody>

            </table>

            <div class="pagination">{{ $users->links('layouts.customPagination') }}</div>

        </div>
    </div>
@endsection

@section('footer')

@endsection

@section('script')

@endsection
