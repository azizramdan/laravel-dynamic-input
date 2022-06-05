@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
</div>
@endif

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
        @forelse ($transactions as $index => $item)
            <tr>
                <th>{{ $index + 1 }}</th>
                <td>{{ $item->date }}</td>
                <td>{{ $item->customer_name }}</td>
                <td>Rp. {{ number_format($item->total) }}</td>
                <td>{{ $item->status }}</td>
                <td>
                    <a href="/{{ $item->id }}/edit" class="btn btn-warning">Edit</a>
                    <button class="btn btn-danger" onclick="destroy({{ $item->id }})">Hapus</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

<form id="delete-form" action="/" method="post">
    @csrf
    @method('delete')
</form>
@endsection

@push('script')
    <script>
        function destroy(id) {
            const isConfirmed = confirm('Apakah Anda yakin akan hapus data?')

            if (isConfirmed) {
                $('#delete-form').attr('action', `/${id}`)
                $('#delete-form').submit()
            }
        }
    </script>
@endpush