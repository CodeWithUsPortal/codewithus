<?php

namespace App\Domain;

class AsanaFunctions{
    public function showRegistrationForm()
    {
        Asana::getCurrentUser();
        //return view('auth.register');
    }
}
