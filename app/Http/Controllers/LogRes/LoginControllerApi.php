<?php

namespace App\Http\Controllers\LogRes;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginControllerApi extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $pesan = [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password tidak boleh kosong',
        ];

        $validasi = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ],$pesan);

            if ($validasi->fails()) {
                $val = $validasi->errors()->all();
                return $this->pesanError($val[0]);
            }

         $user = User::where('email', $request->email)->first();

         if (!$user || !Hash::check($request->password, $user->password)) {
            return response('Login invalid', 503);
         }

         $token = $user->createToken('myAppToken');
         return (new UserResource($user))->additional([
            'token' => $token->plainTextToken,
        ]);
    }

    public function pesanError($pesan){
        return response()->json([
            'message' => $pesan,
        ], 405);
    }
}
