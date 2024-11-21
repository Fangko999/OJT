<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index(){
        return view('index');
    }

    public function testmail()
    {
        $name = 'test name for email';
        Mail::send('emails.test', compact('name'), function($email){
            $email->to('chichphangnen@gmail.com', 'Quản lý nhân sự')
                  ->subject('Test Email')
                  ->from('your-email@example.com', 'Your Name');
        });
    }
}
