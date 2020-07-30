<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\SendMailable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail()
    {
//        Mail::to('poula.helmy@stud.fci-cu.edu.eg')->send(new SendMailable());
//        echo 'email sent';

//        dispatch(new SendEmailJob());
//
//        echo 'email sent';

        $emailJob = (new SendEmailJob())->delay(Carbon::now()->addSeconds(3));
        dispatch($emailJob);

        echo 'email sent';

    }
}

