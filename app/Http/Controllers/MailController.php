<?php

namespace App\Http\Controllers;

use App\Mail\MailNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index(Request $request){

        $input = $request->all();

        $mailData = [
            'title' => 'Новая запись',
            'body' => 'Дата: '.$input['date'].' / Номер телефона: '.$input['phone'].' / Имя: '.$input['name'].' / Столик: '.$input['place']
        ];
        Mail::to('nonameyoutube@rambler.ru')->send(new MailNotify($mailData));
    }

    public function indexBanquet(Request $request){
        $input = $request->all();

        $mailData = [
            'title' => 'Новая запись банкета',
            'body' => 'Дата: '.$input['date'].' / Номер телефона: '.$input['phone'].' / Имя: '.$input['name'].' / Число человек: '.$input['amountOfPerson'].
                ' / Блюда:'.json_encode($input['dish'])
        ];
        Mail::to('nonameyoutube@rambler.ru')->send(new MailNotify($mailData));
    }
}
