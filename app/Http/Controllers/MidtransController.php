<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Midtrans\Config;
use Midtrans\Snap;

class MidtransController extends Controller
{
   

public function __construct()
{
    Config::$serverKey = config('services.midtrans.server_key');
    Config::$isProduction = config('services.midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;
}
}
