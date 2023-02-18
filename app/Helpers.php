<?php

use App\Models\User;

if(!function_exists('generate_username')) {
    function generate_username($fullName): string
    {
        $username = Str::lower(Str::slug($fullName));
        if(User::where('username', '=', $username)->exists()){
            $uniqueUserName = $username.'-'.Str::lower(Str::random(4));
            $username = generate_username($uniqueUserName);
        }
        return $username;
    }
}


?>