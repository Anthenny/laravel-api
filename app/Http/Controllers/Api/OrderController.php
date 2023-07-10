<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller {

    public function index( Request $request ) {
//        $query = $request->query();

        $orders = Order::latest()->paginate( 2 );

        if ( $orders->isEmpty() ) {
            return response( [ 'message' => 'Er zijn helaas nog geen orders' ] );
        }

        return response( [ 'orders' => $orders ] );
    }

    // Show one order
    public function show( $id ) {
        $order = Order::find( $id );

        if ( ! $order ) {
            return response( [ 'message' => 'Er is geen order met dit id.' ] );
        }

        return response( [ 'order' =>  $order ] );
    }

    public function showUserOrders($email) {
        $order = Order::where('email', '=', $email)->get();

        if ( ! $order ) {
            return response( [ 'message' => 'Er is geen order met deze email.' ] );
        }

        return response( [ 'orders' =>  $order ] );
    }

    // Store a order
    public function store( Request $request ) {
        $data = $request->all();

        $validator = Validator::make( $data, [
            'amount'    => [ 'required', 'integer' ],
            'completed' => [ 'required', 'bool' ],
        ] );

        if ( $validator->fails() ) {
            return response( [ 'error' => $validator->errors(), 'message' => 'Er ging iets fout' ] );
        }

        $order = Order::create( $data );

        return response( [ 'order' => $order, 'message' => 'Order created successfully' ] );
    }

    // Update one order
    public function update( Request $request, $id ) {
        $data  = $request->all();
        $order = Order::find( $id );

        if ( ! $order ) {
            return response( [ 'message' => 'er is geen order gevonden met dit id' ] );
        }

        $validator = Validator::make( $data, [
            'amount'    => [ 'nullable', 'bool' ],
            'completed' => [ 'nullable', 'integer' ],
        ] );

        if ( $validator->fails() ) {
            return response( [ 'error' => $validator->errors(), 'message' => 'Validation error' ] );
        }

        $order->update( $data );

        // TODO order resource
        return response( [ 'order' => $order, 'message' => 'Order updated successfully' ] );
    }

    // TODO delete order

    // TODO mark order as complete maar kan ook in patch show all complete orders en show all uncompleted orders
}
