<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller {

    // Show all products
    public function index(Request $request) {
        // TODO laravel daily
        $query = $request->query;

        $products = DB::table( 'products' )
                      ->where( 'reserved', 1 )
                      ->paginate( 2 );

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
}
