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
    public function __construct()
    {
        parent::__construct();
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
        $credentials = $this->validate($request, [
            'nik' => 'required',
            'password' => 'required'
        ]);

        // check if this NIK exist
        $account = Account::withoutTrashed()->where('nik', $credentials['nik'])->first();
        if ($account == null) {
            return $this->error("NIK or password wrong", 401);
        }

        if (!Hash::check($credentials['password'], $account['password'])) {
            return $this->error("NIK or password wrong", 401);
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
        return $this->success($this->account);
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
