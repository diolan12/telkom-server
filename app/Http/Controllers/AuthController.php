<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\FirebaseHelper;
use App\Models\Rest\Account;
use PHPUnit\Util\Json;

class AuthController extends RestController
{
    /**
     * The firebase helper instance.
     *
     * @var \App\FirebaseHelper
     */
    protected $firebaseHelper;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->firebaseHelper = new FirebaseHelper();
    }

    /**
     * Handle the login post method.
     *
     * @return Json
     */
    public function login(Request $request) {
        parent::__construct($request, 'account');

        $credentials = $this->validate($request, [
            'nik' => 'required',
            'password' => 'required'
        ]);

        // check if this NIK exist
        $account = Account::withoutTrashed()->where('nik', $credentials['nik'])->first();
        if($account == null) {
            $this->code = 401;
            $this->response = [
                'type' => 'ERROR',
                'message' => "NIK or password wrong"
            ];
            return $this->response;
        }

        if (!Hash::check($credentials['password'], $account['password'])){
            $this->code = 401;
            $this->response = [
                'type' => 'ERROR',
                'message' => "NIK or password wrong"
            ];
            return $this->response;
        }

        $jwt = $this->firebaseHelper->encode($account);

        $this->response = [
            'type' => 'SUCCESS',
            'message' => $jwt
        ];

        return $this->respond();
    }

    public function verify(Request $request) {
        parent::__construct($request, 'account');
        $token = $request->bearerToken();
        try {
            $decodedJWT = $this->firebaseHelper->decode($token);
        } catch (\Throwable $th) {
            $this->code = 401;
            $this->response = [
                'type' => 'ERROR',
                'message' => $th->getMessage()
            ];
            return $this->response;
        }
        $this->response = [
            'type' => 'SUCCESS',
            'message' => $decodedJWT
        ];
        return $this->respond();
    }
}
