<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index( Request $request ) {
//        $query = $request->query();

        $users = User::paginate( 10 );

        if ( $users->isEmpty() ) {
            return response( [ 'message' => 'Er zijn helaas nog geen gebruikers' ] );
        }

        return response( [ 'users' => $users ] );
    }

    public function show( $id ) {
        $user = User::find( $id );

        if ( ! $user ) {
            return response( [ 'message' => 'Er is geen gebruiker met dit id.' ] );
        }

        return response( [ 'user' =>  $user ] );
    }

    public function update( Request $request, $id ) {
        $data  = $request->all();
        $user = User::find( $id );

        if( $request->password) {
            return response( [ 'message' => 'Je kan het wachtwoord niet veranderen op deze pagina, probeer dit met jouw email' ] );
        }

        if ( !$user ) {
            return response( [ 'message' => 'er is geen user gevonden met dit id' ] );
        }

        $validator = Validator::make( $data, [
            'first_name' => [ 'nullable', 'string' ],
            'last_name' => [ 'nullable', 'string' ],
            'email' => [ 'nullable', 'string' ],
            'phone_number' => [ 'nullable', 'string' ],
            'house_number' => [ 'nullable', 'string' ],
            'additions' => [ 'nullable', 'string' ],
            'postal_code' => [ 'nullable', 'string' ],
            'completed' => [ 'nullable', 'integer' ],
        ] );

        if ( $validator->fails() ) {
            return response( [ 'error' => $validator->errors(), 'message' => 'Validation error' ] );
        }

        $user->update( $data );

        return response( [ 'user' => $user, 'message' => 'Gebruiker updated successfully' ] );
    }

    public function destroy($id) {
        $user = User::find($id);

        if (!$user) {
            return response (['message' => 'Er is geen gebruiker gevonden met dit id']);
        }

        $user->delete();

        return response(null, 204);
//        TODO alles updaten
    }
}
