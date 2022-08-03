<?php

namespace GreyZero\WebCallCenter\Controllers;

class AuthController extends Controller{
    public function getLogin(){
        return view('wcc::login');
    }

    public function postLogin(){
        if(auth()->attempt(request()->only(['username', 'password'])))
            return redirect(config('web-call-center.prefix').'/'.auth()->user()->role);
        return view('wcc::login', ['fail' => true]);
    }

    public function getLogout(){
        auth()->logout();
        return redirect()->route('wcc.login');
    }
}
