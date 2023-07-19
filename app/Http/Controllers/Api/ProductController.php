<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller {

    // Show all products
    public function index( Request $request ) {
        $query = $request->query();

        if ( ! empty( $query['category'] ) ) {
            $category_value = $query['category'];
        } else {
            $category_value = 'oorbellen';
        }

        // Default waardes
        $orderBy_value               = 'created_at';
        $orderBy_order               = 'asc';
        $order_queries['created_at'] = 'asc';
        $where_queries['reserved']   = false;
        $where_queries['category']   = 'oorbellen';

        if ( ! empty( $query['category'] ) ):
            $where_queries['category'] = $query['category'];
        endif;

        if ( ! empty( $query['color'] ) ):
            $where_queries['color'] = $query['color'];
        endif;

        // alleen 1 order querie in combinatie met 3 where queries
        if ( ! empty( $query['weight'] ) ):
            $orderBy_value           = 'weight';
            $orderBy_order           = $query['weight'];
            $order_queries['weight'] = $query['weight'];
        endif;

        if ( ! empty( $query['price'] ) ):
            $orderBy_value          = 'price';
            $orderBy_order          = $query['price'];
            $order_queries['price'] = $query['price'];
        endif;

        if ( ! empty( $query['created_at'] ) ):
            $orderBy_value               = 'created_at';
            $orderBy_order               = $query['created_at'];
            $order_queries['created_at'] = $query['created_at'];
        endif;

        $products = DB::table( 'products' )
                      ->where(
                          function ( $q ) use ( $where_queries ) {
                              foreach ( $where_queries as $key => $value ) {
                                  $q->where( $key, '=', $value );
                              }
                          }
                      )
                      ->orderBy( $orderBy_value, $orderBy_order )
                      ->paginate( 10 );

        if ( $products->isEmpty() ) {
            return response( [ 'products' => ProductResource::collection( $products ), 'message' => 'Er zijn helaas nog geen producten' ] );
        }

        return response( [ 'products' => $products ] );
    }

    // Store a product
    public function store( StoreProductRequest $request ) {
        $data = $request->all();

        $validator = Validator::make( $data, [
            'title'       => [ 'required', 'string', 'max:30', Rule::unique( 'products' ) ],
            'slug'        => [ 'required', 'string' ],
            'color'       => [ 'required', 'string' ],
            'thumbnail'   => [ 'required', 'string' ],
            'category'    => [ 'required', 'string' ],
            'price'       => [ 'required', 'numeric' ],
            'amount'      => [ 'required', 'integer' ],
            'reserved'    => [ 'required', 'bool' ],
            'weight'      => [ 'required', 'integer' ],
            'description' => [ 'required', 'string' ],
        ] );

        if ( $validator->fails() ) {
            return response( [ 'error' => $validator->errors(), 'message' => 'Er ging iets fout' ] );
        }

        $product = Product::create( $data );

        return response( [ 'product' => new ProductResource( $product ), 'message' => 'Product created successfully' ] );
    }

    // Show one product
    public function show( $id ) {
        $product = Product::find( $id );

        if ( ! $product ) {
            return response( [ 'product' => new ProductResource( $product ), 'message' => 'Er is geen product met dit id.' ] );
        }

        return response( [ 'product' => new ProductResource( $product ) ] );
    }

    // Update one product
    public function update( Request $request, $id ) {
        $data    = $request->all();
        $product = Product::find( $id );

        if ( ! $product ) {
            return response( [ 'message' => 'er is geen product gevonden met dit id' ] );
        }

        $validator = Validator::make( $data, [
            'title'       => [ 'nullable', 'string', 'max:30', Rule::unique( 'products' ) ],
            'slug'        => [ 'nullable', 'string' ],
            'thumbnail'   => [ 'nullable', 'string' ],
            'category'    => [ 'nullable', 'string' ],
            'price'       => [ 'nullable', 'integer' ],
            'amount'      => [ 'nullable', 'integer' ],
            'reserved'    => [ 'nullable', 'bool' ],
            'weight'      => [ 'nullable', 'integer' ],
            'description' => [ 'nullable', 'string' ],
        ] );

        if ( $validator->fails() ) {
            return response( [ 'error' => $validator->errors(), 'message' => 'Validation error' ] );
        }

        $product->update( $data );

        return response( [ 'product' => new ProductResource( $product ), 'message' => 'Product updated successfully' ] );
    }

    // Delete one product
    public function destroy( $id ) {

        $product = Product::find( $id );

        if ( ! $product ) {
            return response( [ 'message' => 'er is geen product gevonden met dit id' ] );
        }

        $product->delete();

        return response( null, 204 );
    }

    public function checkout() {
        \Stripe\Stripe::setApiKey(env( 'STRIPE_SECRET' ));

        $products = Product::all();
        // TODO ipv alle producten loopen we door cart
        // TODO user object meegeven zodat die firstname etc ook dynamisch is
        $lineItems  = [];
        $totalPrice = 0;

        foreach ( $products as $product ) {
            $totalPrice  += $product->price;
            $lineItems[] = [
                'price_data' => [
                    'currency'     => 'eur',
                    'product_data' => [
                        'name' => $product->title,
                    ],
                    'unit_amount'  => $product->price * 100,
                ],
                'quantity'   => 1,
            ];
        }

        $YOUR_DOMAIN = 'http://localhost/api/checkout';

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'payment_method_types' => [ 'card', 'ideal' ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success',
            'cancel_url' => $YOUR_DOMAIN . '/cancel',
        ]);

        $order = new Order();
        // todo enum values production
        $order->first_name   = 'Anthenny';
        $order->last_name    = 'de Hoon';
        $order->email        = 'A@hotmail.com';
        $order->phone_number = '0636082244';
        $order->house_number = '19';
        $order->additions    = 'C';
        $order->postal_code  = '2924BN';
        $order->total_price  = $totalPrice;
        $order->completed    = false;
        $order->session_id   = 'test';
        $order->products     = $products;

        $order->save();

        return response( [ 'message' => 'succesvol de order geplaatst', 'url' => $checkout_session->url] );
    }

    public function success() {
        return response( [ 'message' => 'succesvol de order geplaatst']);
    }

    public function cancel() {
        return response( [ 'message' => 'canceled']);
    }
}
