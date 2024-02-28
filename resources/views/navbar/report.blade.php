<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">
</head>

<body>
    {{-- <center>
        <h2>Transactions between {{ $fromDate->format('M. d, Y') }} and {{ $toDate->format('M. d, Y') }}</h2>
    </center> --}}

    <center>
        <h2>Transactions between {{ $fromDate->format('M. d, Y') }} and {{ $toDate->format('M. d, Y') }}
            for {{ $customerName }}</h2>
    </center>
    <center>
        <table>
            <tr>
                <th colspan="13" class="table-th">TRANSACTIONS</th>
            </tr>
            @php
                $rowNumber = 1;
            @endphp

            <tr>
                <th>No.</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
                {{-- <th>Profit</th> --}}
                <th>Date</th>
                {{-- <th>Actions</th> --}}
            </tr>

            <tbody class="all-data">
                @if ($transactions->isEmpty())
                    <tr>
                        <td colspan="11">No transactions were made on the chosen date.</td>
                    </tr>
                @else
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="transcact-td">{{ $rowNumber++ }}</td>
                            <td class="transcact-td">{{ $transaction->customer_name }}</td>
                            <td class="transcact-td">{{ $transaction->product_name }}</td>
                            <td class="transcact-td">{{ $transaction->qty }}</td>
                            <td class="nowrap transcact-td">₱ {{ number_format($transaction->selling_price) }}</td>
                            <td class="nowrap transcact-td">₱ {{ number_format($transaction->total_price) }}</td>
                            {{-- <td class="nowrap transcact-td">₱ {{ number_format($transaction->profit) }}</td> --}}
                            <td>{{ optional($transaction->created_at)->format('M. d, Y') }}</td>
                            {{-- <td class="actions">
                                <div class="actions-container">
                                    <form action="{{ route('admin.transactionEdit', $transaction->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('GET')
                                        <button type="submit" class="edit" id="edit">
                                            <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.transactionDestroy', $transaction->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure you want to delete this?')"
                                            type="submit" class="delete" id="delete">
                                            <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td> --}}
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tbody id="content" class="search-data"></tbody>

        </table>

    </center>

    <div class="button-container">
        <button type="button" class="back" onclick="closeTab()">Go back</button>
        <button class="print" onclick="window.print()">Print Report</button>
    </div>

    <script>
        function closeTab() {
            window.close();
        }
    </script>

</body>

</html>
