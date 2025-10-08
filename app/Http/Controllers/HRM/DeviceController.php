<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class DeviceController extends Controller
{
    public function index(){
        //$zktecoLib = new Zkteco('118.67.221.57');
        $zktecoLib = new Zkteco(ip:'118.67.221.57', port:4370, shouldPing:false, timeout:25, password:12345); // Password means CMD Key
        dd($zktecoLib->deviceName());
    }
}
