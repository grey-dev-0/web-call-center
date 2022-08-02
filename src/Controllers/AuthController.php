<?php

namespace GreyZero\WebCallCenter\Controllers;

class AuthController extends Controller{
    public function getLogin(){
        return view('wcc::login');
    }
}
