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

class AuthController extends Controller
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
    public function __construct(Request $request)
    {
        parent::__construct();
        if ($this->firebaseHelper == null) {
            $this->firebaseHelper = FirebaseHelper::instance();
        }
        if ($request->bearerToken()) {
            $token = $request->bearerToken();
            $tokenDecoded = $this->firebaseHelper->decode($token);
            $this->account = Account::where('nik', $tokenDecoded->jti)->first();
            // return $this->success($this->account);
        }
    }

    /**
     * Handle the login post method.
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $this->validate($request, [
            'nik' => 'required',
            'password' => 'required'
        ]);

        // check if this NIK exist
        $account = Account::withoutTrashed()->where('nik', $credentials['nik'])->first();
        if ($account == null) {
            return $this->error("NIK tidak terdaftar", 401);
        }

        if (!Hash::check($credentials['password'], $account['password'])) {
            return $this->error("Password tidak cocok", 401);
        }

        $jwt = $this->firebaseHelper->encode($account);

        return $this->success($jwt);
    }

    /**
     * Verify a JWT then return Account entity.
     *
     * @return JsonResponse<Account>
     */
    public function verify(Request $request)
    {
        if ($this->account != null) {
            return $this->success($this->account);
        }
        return $this->error("No bearer token received");
    }

    public function changePassword(Request $request)
    {
        $credentials = $this->validate($request, [
            'oldPassword' => 'required',
            'newPassword' => 'required'
        ]);

        // check if old password is valid
        if (!Hash::check($credentials['oldPassword'], $this->account['password'])) {
            return $this->error("Password did not match", 401);
        }

        $this->account->password = Hash::make($credentials['newPassword']);;
        $this->account->save();

        return $this->success('Password changed successfully');
    }

    public function uploadPicture(Request $request)
    {
        // checking image in request
        if (!$request->hasFile("image")) {
            return $this->error('Blank image file', 422);
        }

        $filename = Carbon::now();
        $image = $request->file('image');
        $userOldPhoto = $this->account->photo;
        // delete if user old photo not default
        $image->move(storage_path('app/assets/profile'), "$filename.jpg");
        $this->account->photo = $filename;
        $this->account->save();

        return $this->success(url('/assets/profile/' . $filename));
    }
}
