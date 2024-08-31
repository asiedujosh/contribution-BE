<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\SmsTrait;
use App\Models\member;
use App\Models\sms;
use App\Traits\HttpResponses;

class smsController extends Controller
{
    use HttpResponses;
    use SmsTrait;
    //

    public function index (Request $request){
        $pageNo = $request->input('page');
        $perPage = $request->input('perPage');
        $sms = sms::orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $pageNo);
        return $this->success([
            'data' => $sms,
            'pagination' => [
                'total' =>  $sms->total(),
                'current_page' =>  $sms->currentPage(),
                'last_page' =>  $sms->lastPage()  
            ]
        ]); 
    }

    public function sendPersonalSms (Request $request){
        $contact = $request->contact;
        $message = $request->message;
        $this->sendSms($contact, $message); 
        return $this->success([
            'data' => true
          ]);
    }

    public function sendBulkSms (Request $request){
        $messageType = $request->sendType;
        
        switch($messageType){
            case "All":
            $allMembers = member::all();
            if (count($allMembers) > 0) {
                foreach($allMembers as $member){
                    $this->sendSms($member['contact'], $request->message); 
                }
            }

            // Send sms to all
            $sms = new sms;
            $sms->smsType = $messageType;
            $sms-> message = $request->message;
            $res = $sms->save();
            if($res){
                return $this->success([
                    'data' => true
                  ]);
            }
            break;

            case "Male":
            $maleMembers = member::where('gender', '1')->get();
            if (count($maleMembers) > 0) {
                foreach($maleMembers as $member){
                    $this->sendSms($member->contact, $request->message); 
                }
            }

            //  Send sms to all
             $sms = new sms;
             $sms->smsType = $messageType;
             $sms-> message = $request->message;
             $res = $sms->save();

             if($res){
                return $this->success([
                    'data' => true
                  ]);
            }

            break;
            default:
            $femaleMembers = member::where('gender', '2')->get();
            if (count($femaleMembers) > 0) {
                foreach( $femaleMembers as $member){
                    $this->sendSms($member->contact, $request->message); 
                }
            }

             // Send sms to all
             $sms = new sms;
             $sms->smsType = $messageType;
             $sms-> message = $request->message;
             $res = $sms->save();

             if($res){
                return $this->success([
                    'data' => true
                  ]);
            }

        }
    }
}
