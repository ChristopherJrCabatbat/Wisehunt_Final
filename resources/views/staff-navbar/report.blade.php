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
    <center>
        <h2>Transactions between {{ $fromDate->format('M. d, Y') }} and {{ $toDate->format('M. d, Y') }}</h2>
    </center>

    <center>
        <table>
            @php
                $rowNumber = 1;
            @endphp
            <tr>
                {{-- <th colspan="13" class="table-th">Transactions between {{ $fromDate->format('M. d, Y') }} and
                    {{ $toDate->format('M. d, Y') }}</th> --}}
                <th colspan="13" class="table-th">TRANSACTIONS</th>
            </tr>
            <tr>
                <th>No.</th>
                <th>Customer</th>
                <th>Contact No.</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Amount Tendered</th>
                <th>Change Due</th>
                <th>Total Earned on Item</th>
                <th>Date</th>
            </tr>
            <tbody>
                @if ($transactions->isEmpty())
                    <tr>
                        <td colspan="11">No transactions were made on the chosen date.</td>
                    </tr>
                @else
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $rowNumber++ }}</td>
                            <td>{{ $transaction->customer_name }}</td>
                            <td>{{ $transaction->contact_num }}</td>
                            <td>{{ $transaction->product_name }}</td>
                            <td>{{ $transaction->qty }}</td>
                            <td>{{ $transaction->unit_price }}</td>
                            <td>{{ $transaction->total_price }}</td>
                            <td>{{ $transaction->amount_tendered }}</td>
                            <td>{{ $transaction->change_due }}</td>
                            <td>{{ $transaction->total_earned }}</td>
                            <td>{{ $transaction->created_at->format('M. d, Y') }}</td>
                            {{-- <td>{{ $transaction->created_at->date }}</td> --}}
                        </tr>
                    @endforeach
            </tbody>
            @endif
        </table>
    </center>
    <button onclick="window.print()">Print Report</button>


</body>

</html>
