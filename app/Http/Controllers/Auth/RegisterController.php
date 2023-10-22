<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register');
    }
    public function register(Request $request)
    {
    $this->validate($request, [
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    $user = User::create([
        'name' => $request->input('name'),
        'surname' => $request->input('surname'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
    ]);
   
    auth()->login($user);

    return redirect('/');
    }
}
