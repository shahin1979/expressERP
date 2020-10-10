<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
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

        $request->user()->password_updated_at = Carbon::now();
        $request->user()->save();

        return redirect('home')->with('success','Your Password Updated');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function resetIndex()
    {
        $users = User::query()->where('status',true)->pluck('name','id');
        return view('security.reset-password-index',compact('users'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::query()->find($request['user_id']);

        $user->fill([
            'password' => Hash::make($request['password'])
        ])->save();

        return redirect()->route('home')->with('success','Password Reset Successful');
    }
}
