<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister() { return view('admin.register'); }
    public function showLogin() { return view('admin.login'); }

    public function register(Request $r)
    {
        $r->validate(['name'=>'required','email'=>'required|email|unique:users','password'=>'required|min:6']);
        $user = User::create([
            'name'=>$r->name, 'email'=>$r->email, 'password'=>Hash::make($r->password), 'is_admin' => true
        ]);
        Auth::login($user);
        $r->session()->regenerate();
        return redirect()->route('admin.polls.index');
    }

    public function login(Request $r)
    {
        $credentials = $r->only('email','password');
        $credentials['is_admin'] = true;
        if (Auth::attempt($credentials)) {
            $r->session()->regenerate();
            return redirect()->route('admin.polls.index');
        }
        return back()->withErrors(['email'=>'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}