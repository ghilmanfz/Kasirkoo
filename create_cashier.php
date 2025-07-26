<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\User;

try {
    $user = User::firstOrCreate(['email' => 'kasir@test.com'], [
        'name' => 'Test Kasir',
        'email' => 'kasir@test.com',
        'password' => bcrypt('password'),
        'level' => 0
    ]);
    
    echo 'Cashier user created/found: ' . $user->email . ' (Level: ' . $user->level . ')' . PHP_EOL;
    
    // Also check if admin exists
    $admin = User::where('level', 1)->first();
    if ($admin) {
        echo 'Admin user found: ' . $admin->email . ' (Level: ' . $admin->level . ')' . PHP_EOL;
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
