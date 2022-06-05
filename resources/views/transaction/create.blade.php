@extends('layouts.app')

@section('title', 'Add Transaction')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="/" id="create-form" method="post">
    @csrf
    <div class="mb-3">
        <label for="date" class="form-label">Date</label>
        <input type="date" class="form-control" id="date" name="date" value="{{ @old('date') }}" required>
    </div>
    <div class="mb-3">
        <label for="customer_name" class="form-label">Customer Name</label>
        <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ @old('customer_name') }}" required>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Payment</label>
        <select class="form-select" name="status">
            <option value="cash" {{ @old('status') == 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="credit" {{ @old('status') == 'credit' ? 'selected' : '' }}>Credit</option>
        </select>
    </div>

    <div class="d-flex justify-content-between mt-4 mb-2">
        <h4>Items</h4>
        <button type="button" class="btn btn-primary" onclick="addItem()">Add Item</button>
    </div>

    <table class="table" id="items">
        <thead>
            <tr>
                <th scope="col">Category</th>
                <th scope="col">Name</th>
                <th scope="col">Qty</th>
                <th scope="col">Unit</th>
                <th scope="col">Price</th>
                <th scope="col">Total</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div class="mt-4 text-end">
        <button class="btn btn-primary">Submit</button>
    </div>
</form>
@endsection

@push('script')
    <script>
        const categories = {!! $categories !!}
        let totalItems = 0
        const oldValues = {
            categoryId: {!! old('category_id') ? json_encode(old('category_id')) : '[]' !!},
            name: {!! old('name') ? json_encode(old('name')) : '[]' !!},
            qty: {!! old('qty') ? json_encode(old('qty')) : '[]' !!},
            unit: {!! old('unit') ? json_encode(old('unit')) : '[]' !!},
            price: {!! old('price') ? json_encode(old('price')) : '[]' !!},
        }

        function addItem(categoryId = null, name = null, qty = null, unit = null, price = null) {
            const total = qty && price ? qty * price : '-'
            let categoryOptions = ''

            categories.forEach(category => {
                categoryOptions += /*html*/`
                    <option value="${category.id}" ${category.id == categoryId ? 'selected' : ''}>${category.code} - ${category.name}</option>
                `
            })

            const itemHtml = /* html */`
                <tr data-index="${totalItems}">
                    <td>
                        <select class="form-select" name="category_id[]" required>
                            ${categoryOptions}
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="name[]" value="${name || ''}" required>
                    </td>
                    <td>
                        <input type="number" min="1" class="form-control" name="qty[]" value="${qty || 0}" onchange="calculateTotal(${totalItems})" required>
                    </td>
                    <td>
                        <select class="form-select" name="unit[]" required>
                            <option value="Kg" ${unit == 'Kg' ? 'selected' : ''}>Kg</option>
                            <option value="Ons" ${unit == 'Ons' ? 'selected' : ''}>Ons</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" min="1" class="form-control" name="price[]" value="${price || 1000}" onchange="calculateTotal(${totalItems})" required>
                    </td>
                    <td>
                        <b class="total">${total}</b>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${totalItems})"><b>X</b></button>
                    </td>
                </tr>
            `

            $('#items tbody').append(itemHtml)
            totalItems++
        }

        function removeItem(index) {
            $(`#items tbody tr[data-index=${index}]`).remove()
            totalItems--
        }

        function calculateTotal(index) {
            const qty = $(`#items tbody tr[data-index=${index}] input[name="qty[]"]`).val()
            const price = $(`#items tbody tr[data-index=${index}] input[name="price[]"]`).val()
            const total = qty * price

            $(`#items tbody tr[data-index=${index}] .total`).text(total)
        }

        if (oldValues.categoryId.length) {
            oldValues.categoryId.forEach((value, index) => {
                addItem(value, oldValues.name[index], oldValues.qty[index], oldValues.unit[index], oldValues.price[index])
            })
        }

        $('#create-form').on('submit', function (e) {
            if (!totalItems) {
                alert('Minimal ada 1 item')
                return false
            }

            const categoryIds = []

            $('#create-form select[name="category_id[]"]').each(function () {
                categoryIds.push($(this).val())
            })
            
            if ((new Set(categoryIds)).size !== categoryIds.length) {
                alert('Tidak boleh ada kategori yang sama')
                return false
            }
        })
    </script>
@endpush