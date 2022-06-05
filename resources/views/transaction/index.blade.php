@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="text-end">
    <a href="/create" class="btn btn-primary">Add</a>
</div>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Date</th>
            <th scope="col">Customer</th>
            <th scope="col">Total</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $index => $item)
            <tr>
                <th>{{ $index + 1 }}</th>
                <td>{{ $item->date }}</td>
                <td>{{ $item->customer_name }}</td>
                <td>Rp. {{ number_format($item->total) }}</td>
                <td>{{ $item->status }}</td>
                <td>
                    <a href="/{{ $item->id }}/edit" class="btn btn-warning">Edit</a>
                    <button class="btn btn-danger">Hapus</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection