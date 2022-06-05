<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'customer_name' => ['required', 'string'],
            'status' => ['required', 'in:cash,credit'],
            'category_id' => ['required', 'array'],
            'category_id.*' => ['required', 'numeric'],
            'name' => ['required', 'array'],
            'name.*' => ['required', 'string'],
            'qty' => ['required', 'array'],
            'qty.*' => ['required', 'numeric'],
            'unit' => ['required', 'array'],
            'unit.*' => ['required', 'in:Kg,Ons'],
            'price' => ['required', 'array'],
            'price.*' => ['required', 'numeric'],
        ]);

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
}
