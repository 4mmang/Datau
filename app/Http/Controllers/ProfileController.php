<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profileAdmin(){
        return view('admin.profile');
    }
    
    public function profile(){
        return view('profile');
    }

}
