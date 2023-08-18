<?php

/**
* @author Ilham Gumilang <gumilang.dev@gmail.com>
* date 20221111
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Function to show login form
     * 
     * @return Renderable
     */
    public function index()
    {
        return view('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }

    /**
     * Function to handle login request
     * @param string email
     * @param string password
     * @return Response
     */
    public function login(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required'
            ], [
                'email.required' => 'Email is required',
                'password.required' => 'Password is required',
            ]);
            
            if ($validate->fails()) {
                return back()
                    ->withErrors($validate)
                    ->withInput();
            }

            $credential = ['email' => $request->email, 'password' => $request->password];
            if (!Auth::attempt($credential)) {
                return back()
                    ->with(['error_message_alert' => 'Email Anda Salah/Tidak Terdaftar'])
                    ->withInput();
            }

            return redirect()->route('dashboard')->with(['message_alert' => 'Login Success']);

        } catch (\Throwable $th) {
            return back()
                ->withInput()
                ->with(['error_message_alert' => $th->getMessage()]);
        }
    }
}
