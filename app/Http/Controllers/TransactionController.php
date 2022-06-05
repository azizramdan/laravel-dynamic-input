<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();
        
        return view('transaction.index', compact('transactions'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('transaction.create', compact('categories'));
    }

    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated) {
                $total = 0;

                foreach ($validated['price'] as $index => $price) {
                    $total += $price * $validated['qty'][$index];
                }
                
                $transaction = Transaction::create([
                    'date' => $validated['date'],
                    'customer_name' => $validated['customer_name'],
                    'status' => $validated['status'],
                    'total' => $total,
                ]);

                foreach ($validated['category_id'] as $index => $categoryId) {
                    $transaction->items()->attach($validated['category_id'][$index], [
                        'name' => $validated['name'][$index],
                        'qty' => $validated['qty'][$index],
                        'unit' => $validated['unit'][$index],
                        'price' => $validated['price'][$index],
                        'total' => $validated['qty'][$index] * $validated['price'][$index],
                    ]);
                }
            });
        } catch (Exception $e) {
            throw $e;
        }

        return redirect('/')->with('success', 'Berhasil menambah data transaksi');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus data transaksi');
    }

    public function edit(Transaction $transaction)
    {
        $categories = Category::all();
        $transaction->load('items');

        return view('transaction.edit', compact('categories', 'transaction'));
    }

    public function update(Transaction $transaction, UpdateTransactionRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $transaction) {
                $total = 0;

                foreach ($validated['price'] as $index => $price) {
                    $total += $price * $validated['qty'][$index];
                }
                
                $transaction->update([
                    'date' => $validated['date'],
                    'customer_name' => $validated['customer_name'],
                    'status' => $validated['status'],
                    'total' => $total,
                ]);

                $sync = [];

                foreach ($validated['category_id'] as $index => $categoryId) {
                    $sync[$validated['category_id'][$index]] = [
                        'name' => $validated['name'][$index],
                        'qty' => $validated['qty'][$index],
                        'unit' => $validated['unit'][$index],
                        'price' => $validated['price'][$index],
                        'total' => $validated['qty'][$index] * $validated['price'][$index],
                    ];
                }

                $transaction->items()->sync($sync);
            });
        } catch (Exception $e) {
            throw $e;
        }

        return redirect('/')->with('success', 'Berhasil ubah data transaksi');
    }
}
