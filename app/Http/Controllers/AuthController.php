<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\FirebaseHelper;
use App\Models\Rest\Account;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Instanceof_;
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
         * The Account model entity.
         * If not authenticated, this will return null
         *
         * @var ?\App\Models\Rest\Account
         */
    protected $account;

    /**
     * Create a new controller, Account if not null, and FirebaseHelper instance.
     * If Auth::user() returning value of Account, then this will instantiate $account
     *
     * @return void
     */
    public function __construct()
    {
        $account = Auth::user();
        if ($account != null) {
            $this->account = $account;
        }
        $this->firebaseHelper = new FirebaseHelper();
    }

    /**
     * Handle the login post method.
     *
     * @return JsonResponse
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

    /**
     * Verify a JWT then return Account entity.
     *
     * @return JsonResponse<Account>
     */
    public function verify(Request $request)
    {
        parent::__construct($request, 'account');
        $this->response = [
            'type' => 'SUCCESS',
            'message' => $this->account
        ];
        return $this->respond();
    }
    
    public function changePassword(Request $request)
    {
        parent::__construct($request, 'account');
        $credentials = $this->validate($request, [
            'oldPassword' => 'required',
            'newPassword' => 'required'
        ]);

        // check if old password is valid
        if (!Hash::check($credentials['oldPassword'], $this->account['password'])) {
            $this->code = 401;
            $this->response = [
                'type' => 'ERROR',
                'message' => "Password did not match"
            ];
            return $this->respond();
        }

        $this->account->password = Hash::make($credentials['newPassword']);;
        $this->account->save();

        $this->response = [
            'type' => 'SUCCESS',
            'message' => 'Password changed successfully'
        ];
        return $this->respond();
    }

    public function uploadPicture(Request $request)
    {
        parent::__construct($request, 'account');

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
        $userOldPhoto = $this->account->photo;
        // delete if user old photo not default
        $image->move(storage_path('app/assets/profile'), "$filename.jpg");
        $this->account->photo = $filename;
        $this->account->save();

        $this->response = [
            'type' => 'SUCCESS',
            'message' => url('/assets/profile/' . $filename)
        ];
        return $this->respond();
    }
}
