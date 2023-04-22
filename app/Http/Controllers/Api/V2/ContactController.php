<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Messages\MailMessage;
use App\Mail\EmailManager;
use App\Mail\SecondEmailVerifyMailManager;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'first_name' => 'required',
            'email' => 'required|email|unique:contacts',
            'phone_number' => 'required|numeric',
            'subject' => 'required',
            'message' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' =>false,
                'message'  => 'Validation Error',
                'data'  => $validator->messages()
            ],200);
        } else {
            $contact = Contact::create([
                'first_name' => $request->first_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);

            if($contact){
                return response()->json([
                    'status' => true,
                    'message'  => 'Your form submited'
                ],201);
            }else{
                return response()->json([
                    'status' =>false,
                    'message'  => 'Your form not submited Please Try again'
                ],404);
            }
        }
    }

    public function testEmail(Request $request){
        $array['view'] = 'emails.newsletter';
        $array['subject'] = "SMTP Test";
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = "This is a test email.";

        try {
            Mail::to($request->email)->send(new EmailManager($array));
            // Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }

        flash(translate('An email has been sent.'))->success();
        return back();
    }
    
    public function subscribe(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'email' => 'required|email|unique:subscribers',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' =>false,
                'message'  => 'Validation Error',
                'data'  => $validator->messages()
            ],200);
        } else {
            // dd($request->all());
            $this->subscribeEmail($request->email);
            dd('done');
            // $subscribe = Subscriber::create([
            //     'email' => $request->email,
            // ]);
            
            // if($subscribe){
            //     return response()->json([
            //         'status' => true,
            //         'message'  => 'Your form subscribe'
            //     ],201);
            // }else{
            //     return response()->json([
            //         'status' =>false,
            //         'message'  => 'Your form not subscribe Please Try again'
            //     ],404);
            // }
        }
    }

    public function subscribeEmail($email){
        $array['view'] = 'emails.subscribe';
        $array['subject'] = "Email Subscribe";
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = "Thanks for subscription";
        // dd($email);
        try {
            Mail::to($email)->send(new EmailManager($array));
            // $send = Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }

        // $array['view'] = 'emails.subscribe';
        // $array['subject'] = translate('Email Subscribe');
        // $array['content'] = translate("Thanks for subscription");

        // return (new MailMessage)
        //     ->view('emails.subscribe', ['array' => $array])
        //     ->line('Mail: '.$email)
        //     ->subject(translate('Email Subscribe - ').env('APP_NAME'));

        // flash(translate('An email has been sent.'))->success();
        // return back();
    }
}
