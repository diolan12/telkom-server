<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\FirebaseHelper;
use App\Models\Rest\Account;
use Carbon\Carbon;
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
    public function login(Request $request)
    {
        parent::__construct($request, 'account');

        $credentials = $this->validate($request, [
            'nik' => 'required',
            'password' => 'required'
        ]);

        // check if this NIK exist
        $account = Account::withoutTrashed()->where('nik', $credentials['nik'])->first();
        if ($account == null) {
            $this->code = 401;
            $this->response = [
                'type' => 'ERROR',
                'message' => "NIK or password wrong"
            ];
            return $this->respond();
        }

        if (!Hash::check($credentials['password'], $account['password'])) {
            $this->code = 401;
            $this->response = [
                'type' => 'ERROR',
                'message' => "NIK or password wrong"
            ];
            return $this->respond();
        }

        $jwt = $this->firebaseHelper->encode($account);

        $this->response = [
            'type' => 'SUCCESS',
            'message' => $jwt
        ];

        return $this->respond();
    }

    public function verify(Request $request)
    {
        parent::__construct($request, 'account');
        $token = $request->bearerToken();
        if ($token == null) {
            $this->code = 401;
            $this->response = [
                'type' => 'ERROR',
                'message' => 'Blank authorization header'
            ];
            return $this->respond();
        }
        try {
            $decodedJWT = $this->firebaseHelper->decode($token);
        } catch (\Throwable $th) {
            $this->code = 401;
            $this->response = [
                'type' => 'ERROR',
                'message' => $th->getMessage()
            ];
            return $this->respond();
        }
        // $this->response = [
        //     'type' => 'SUCCESS',
        //     'message' => $decodedJWT
        // ];
        // return $this->respond();

        // instantiate account
        $account = Account::where('nik', $decodedJWT->jti)->first();
        $this->response = [
            'type' => 'SUCCESS',
            'message' => $account
        ];
        return $this->respond();
    }
    
    public function uploadPicture(Request $request)
    {
        parent::__construct($request, 'account');
        // checking JWT header
        $token = $request->bearerToken();
        if ($token == null) {
            $this->code = 401;
            $this->response = [
                'type' => 'ERROR',
                'message' => 'Blank authorization header'
            ];
            return $this->respond();
        }
        // decoding JWT
        try {
            $decodedJWT = $this->firebaseHelper->decode($token);
        } catch (\Throwable $th) {
            $this->code = 401;
            $this->response = [
                'type' => 'ERROR',
                'message' => $th->getMessage()
            ];
            return $this->respond();
        }
        // instantiate account
        $account = Account::where('nik', $decodedJWT->jti)->first();

        // checking image in request
        if (!$request->hasFile("image")) {
            $this->code = 422;
            $this->response = [
                'type' => 'ERROR',
                'message' => 'Blank image file'
            ];
            return $this->respond();
        }

        $filename = Carbon::now();
        $image = $request->file('image');
        $userPicture = $decodedJWT->picture;
        // if
        $image->move(storage_path('app/assets/profile'), "$filename.jpg");
        $account->photo = $filename;
        $account->save();

        $this->response = [
            'type' => 'SUCCESS',
            'message' => url('/assets/profile/'.$filename)
        ];
        return $this->respond();
    }
}
