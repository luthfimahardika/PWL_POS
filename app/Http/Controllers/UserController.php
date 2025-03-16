<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        
        $user = UserModel::create(
            [
                'username' => 'manager11',
                'nama' => 'Manager11',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ],
        );
        $user->username = 'manager12';

        $user->save();

        $user->iwasChanged(); // true
        $user->iwasChanged('username'); // true
        $user->iwasChanged(['username', 'level_id']); // true
        $user->iwasChanged('nama'); // false
        dd($user->iwasChanged(['nama', 'username'])); // true
    }
}
