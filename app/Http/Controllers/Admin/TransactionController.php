<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function index(){
        $transactions = Transaction::latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => $transactions
        ], 200);
    }
    public function store(Request $request){
        $params = $request->all();

        $transaction = DB::transaction(function() use ($params) {

            $transactionParams = [
                'transaction_code' => 'P100' . mt_rand(1,1000),
                'name' => auth()->user()->name,
                'total_price' => $params['total'],
                'accept' => $params['accept'],
                'return' => $params['return'],
			];

			$transaction = Transaction::create($transactionParams);

            return response()->json([
                'status' => 'success',
                'data' => $transaction
            ], 200);

			$carts = Cart::all();

			if ($transaction && $carts) {
				foreach ($carts as $cart) {

                    $itemBaseTotal = $cart->quantity * $cart->price;

					$orderItemParams = [
						'transaction_id' => $transaction->id,
						'product_id' => $cart->product_id,
						'qty' => $cart->quantity,
                        'name' => $cart->name,
						'base_price' => $cart->price,
						'base_total' => $itemBaseTotal,
					];

					$orderItem = TransactionDetail::create($orderItemParams);

					if ($orderItem) {
						$product = Product::findOrFail($cart->product_id);
						$product->quantity -= $cart->quantity;
						$product->save();
                    }

                    $cart->delete();
				}
            }

            return $transaction;
        });



		if ($transaction) {
			return redirect()->route('admin.transactions.show', $transaction->id)->with([
				'message' => 'Success order',
				'alert-type' => 'success'
			]);
		}
    }


    public function show(Transaction $transaction){
        return view('admin.transactions.show', compact('transaction'));
    }



    public function destroy(Transaction $transaction){

        $transaction->delete();

        return redirect()->back()->with([
            'message' => 'success delete',
            'alert-type' => 'danger'
        ]);
    }

    public function print_struck(Transaction $transaction){

        return view('admin.transactions.nota', compact('transaction'));
    }
}
