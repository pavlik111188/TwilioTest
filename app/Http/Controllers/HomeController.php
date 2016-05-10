<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Calls;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function setEmail(Request $request) {

        \Session::put('emailN', $request->emailN);
        return redirect('/register');
    }
    public function index()
    {
        //Twilio::message('+18085551212', 'Pink Elephants and Happy Rainbows');
        return view('welcome');

    }

    public function buyNumber(Request $request) {
        $accountSid = $_ENV['TWILIO_ACCOUNT_SID'];
        $authToken = $_ENV['TWILIO_AUTH_TOKEN'];

        $client = new \Services_Twilio($accountSid, $authToken);
        try {
            $phoneNumber = $request->phone;
            $purchasedNumber = $client->account->incoming_phone_numbers->create(array('PhoneNumber' => $phoneNumber));

            echo $purchasedNumber->sid;
        }
        catch ( \Services_Twilio_RestException $e ) {
            echo $e->getMessage();
        }

    }

    public function callNumber(Request $request) {
        $version = "2010-04-01";
        $sid = $_ENV['TWILIO_ACCOUNT_SID'];
        $token = $_ENV['TWILIO_AUTH_TOKEN'];
        $from_number = "+15807012741";
        $to_number = $request->phone;
        $url = "http://twimlets.com/message";
        $message = "Hello world.";
        $client = new \Services_Twilio($sid, $token, $version);
        Calls::create([
            'number' => $request->phone
        ]);
        try
        {
            $call = $client->account->calls->create(
                $from_number,
                $to_number,
                $url.'?Message='.urlencode($message)
            );

        }
        catch (\Services_Twilio_RestException $e)
        {
            echo 'Error: ' . $e->getMessage();
        }
    }


    public function checkNumber(Request $request)
    {
        //return $this->isValidNumber($request->phone);
        $sid = $_ENV['TWILIO_ACCOUNT_SID'];
        $token = $_ENV['TWILIO_AUTH_TOKEN'];
        $client = new \Lookups_Services_Twilio($sid, $token);
        //$number = $client->phone_numbers->get($number, array("CountryCode" => "US", "Type" => "carrier"));
        try {
            $number = $client->phone_numbers->get($request->phone, array("CountryCode" => "US", "Type" => "carrier"));
            return $number->phone_number;
        } catch ( \Services_Twilio_RestException $e ) {
            $clientNew = new \Services_Twilio($sid, $token);
            $numbersNew = $clientNew->account->available_phone_numbers->getList('US', 'Local', array(
                "AreaCode" => "510"
            ));
            foreach($numbersNew->available_phone_numbers as $number) {
                echo "<br />".$number->phone_number. "<button class='btn-warning' onclick='buyNumber(".$number->phone_number.");'>Buy</button>";
            }
        }
        /*if ($this->isValidNumber($request->phone)) {
            return $this->isValidNumber($request->phone);
        } else {
            return $this->isValidNumber($request->phone);
        }*/
        /*$sid = $_ENV['TWILIO_ACCOUNT_SID'];
        $token = $_ENV['TWILIO_AUTH_TOKEN'];
        $client = new \Lookups_Services_Twilio($sid, $token);
        try {
            $number = $client->phone_numbers->get($request->phone, array("CountryCode" => "US", "Type" => "carrier"));
            //$number->carrier->type;
            dd($number);
            return "Phone number: ". $number->phone_number ."<br /> Type: ".$number->carrier->type . "<br /> Operator: ".$number->carrier->name;
        }
        catch (Exception $e) {
            if($e->getStatus() == 404) {
                return false;
            } else {
                throw $e;
            }
        }*/
        //$number = $client->phone_numbers->get($request->phone, array("CountryCode" => "US", "Type" => "carrier"));
        //return "Phone number: ". $number->phone_number ."<br /> Type: ".$number->carrier->type . "<br /> Operator: ".$number->carrier->name; // => Sprint Spectrum, L.P.
    }
    public function render($request, \Exception $e)
    {
        if ($e instanceof \ForbiddenException) {
            return redirect()->route('home')->withErrors(['error' => $e->getMessage()]);
        }

        return parent::render($request, $e);
    }
}