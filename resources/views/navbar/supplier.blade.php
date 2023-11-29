@extends('../layouts.layout')

@section('title', 'Supplier')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-supplier-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/supplier-styles.css') }}">
@endsection

@section('modals')

    <div class="overlay editOverlay"></div>

    {{-- Add Modal --}}
    <div id="newModal" class="modal">
        <div class="modal-content">
            <span class="close closeModal">&times;</span>

            <form class="modal-form" action="{{ route('admin.supplierStore') }}" method="POST">
                @csrf
                <center>
                    <h2 style="margin: 0%; color:#333;">Add Supplier</h2>
                </center>
                <label class="modal-top" for="">Company Name:</label>
                <input required autofocus type="text" name="supplier" id="autofocus" />
                <label for="">Contact Name:</label>
                <input required type="text" name="contact_person" id="" />
                <label for="">Contact Number:</label>
                <input required type="text" pattern="[0-9]{5,11}" title="Enter a valid contact number" name="contact_num"
                    id="" value="">
                <label for="">Address:</label>
                <input required type="text" name="address" id="" />
                <label for="">Product/s:</label>
                <input required type="text" name="product_name" id="" />


                <input class="add" type="submit" value="Add" />
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    @foreach ($suppliers as $supplier)
        <div id="editModal{{ $supplier->id }}" class="modal editModal">
            <div class="modal-content">
                <span class="close closeEditModal">&times;</span>

                <form class="edit-modal-form" action="{{ route('admin.supplierUpdate', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <center>
                        <h2 style="margin: 0%; color:#333;">Edit Supplier</h2>
                    </center>
                    <label class="modal-top" for="">Company Name:</label>
                    <input required type="text" class="autofocus" name="supplier" id="" autofocus
                        value="{{ old('supplier', $supplier->supplier) }}" />
                    <label for="">Contact Name:</label>
                    <input required type="text" name="contact_person" id=""
                        value="{{ old('contact_person', $supplier->contact_person) }}" />
                    <label for="">Contact Number:</label>
                    <input required type="text" pattern="[0-9]{5,11}" title="Enter a valid contact number"
                        name="contact_num" name="contact_num" id=""
                        value="{{ old('contact_num', $supplier->contact_num) }}" />
                    <label for="">Address:</label>
                    <input required type="text" name="address" id=""
                        value="{{ old('address', $supplier->address) }}" />
                    <label for="">Product/s:</label>
                    <input required type="text" name="product_name" id=""
                        value="{{ old('product_name', $supplier->product_name) }}" />


                    <input class="add" type="submit" value="Update" />
                </form>
            </div>
        </div>
    @endforeach


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
                <a class="sidebar active" href="{{ route('admin.supplier') }}">
                    <img src="{{ asset('images/supplier.png') }}" class="supplier-i" alt="">
                    SUPPLIER</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.user') }}">
                    <i class="fa-solid fa-circle-user user-i" style="color: #ffffff;"></i>
                    {{-- <img src="{{ asset('images/supplier.png') }}" class="user-i" alt=""> --}}
                    USERS</a>
            </div>
        </li>
    </ul>

@endsection

@section('main-content')

    <div class="content">
        <div class="taas">
            <form id="addCustomerForm">
                <button class="add" type="button" id="newButton">Add Supplier</button>
            </form>
        </div>
        <div class="table">
            <table>

                <tr>
                    <th colspan="11" class="table-th">SUPPLIERS</th>
                </tr>

                @php
                    // Calculate the initial row number based on the current page
                    $rowNumber = ($suppliers->currentPage() - 1) * $suppliers->perPage() + 1;
                @endphp

                <tr>
                    <th>No.</th>
                    <th>Company Name</th>
                    <th>Contact Name</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Product/s</th>
                    <th>Actions</th>
                </tr>

                <tbody>
                    @if ($suppliers->isEmpty())
                        <tr>
                            <td colspan="7">No data found.</td>
                        </tr>
                    @else
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $rowNumber++ }}</td>
                                <td>{{ $supplier->supplier }}</td>
                                <td>{{ $supplier->contact_person }}</td>
                                <td>{{ $supplier->contact_num }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td>{{ $supplier->product_name }}</td>

                                <td class="actions">
                                    <div class="actions-container">
                                        <button type="button" class="edit editButton" id="edit"
                                            data-id="{{ $supplier->id }}">
                                            <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                        </button>

                                        <form action="{{ route('admin.supplierDestroy', $supplier->id) }}"
                                            method="POST">
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

            <div class="pagination">{{ $suppliers->links('layouts.customPagination') }}</div>

        </div>
    </div>
@endsection

@section('footer')

@endsection

@section('script')

@endsection
