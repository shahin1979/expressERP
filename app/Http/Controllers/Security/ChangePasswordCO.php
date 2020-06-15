<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordCO extends Controller
{
    public function index(Request $request)
    {
        return view('security.change-password-index');
    }

    public function update(Request $request)
    {
        $request['email'] = Auth::user()->email;
        $this->validator($request->all())->validate();

        $pass = Auth::user()->getAuthPassword();

        if (!Hash::check($request['c_password'], $pass)) {
            return redirect('home')->with('error','Current Password Incorrect');
        }

        $request->user()->fill([
            'password' => Hash::make($request['password'])
        ])->save();

        return redirect('home')->with('success','Your Password Updated');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
}
