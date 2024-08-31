<?php

namespace App\Http\Controllers;
use App\Traits\HttpResponses;
use App\Traits\SmsTrait;
use App\Models\member;
use App\Models\contribution;

use Illuminate\Http\Request;

class contributionController extends Controller
{
    use HttpResponses;
    use SmsTrait;
    //
    public function index (Request $request){
        $pageNo = $request->input('page');
        $perPage = $request->input('perPage');
        $contribution = contribution::orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $pageNo);
        return $this->success([
            'data' => $contribution,
            'pagination' => [
                'total' => $contribution->total(),
                'current_page' => $contribution->currentPage(),
                'last_page' => $contribution->lastPage()  
            ]
        ]); 
    }


    public function allContribution (Request $request){
        $contribution = contribution::all();
        return $this->success([
            'data' => $contribution
        ]);
    }

    public function countContribution(){
        $countContribution = contribution::count();
        return $this->success([
          'data' => $countContribution
        ]);
      }


    public function sumContribution(){
      $sum = contribution::sum('amount');
      return $this->success([
        'data' => $sum
      ]);
    }


    public function searchContribution(Request $request) {
        // $keyword = $request->input('keyword');
        $results = contribution::latest()->filter(request(['keyword']))->get();
        return $this->success([
            'data' => $results
           ]);
    }


    public function store(Request $request){
        $contribution = new contribution;
        $contribution->contributionId = $request->contributionId;
        $contribution->memberId = $request->memberId;
        $contribution->amount = $request->amount;
        $contribution->reason = $request->reason;
        $res = $contribution->save();

        if($res){
            $memberDetail = member::where('memberId', $request->memberId)->first();
            $contact =  $memberDetail->contact;
            $contactMessage = 'You have contributed an amount of '. $request->amount;
            $this->sendSms($memberDetail->contact,  $contactMessage);
         return $this->success([
             'data' => $contribution
            ]);
        }
    }


    public function updateContribution(Request $request, $id){
        $formField = [
            'memberId' => $request->memberId,
            'amount' => $request->amount,
            'reason' => $request->reason
        ];

        $res = contribution::where('id', $id)->update($formField);
        if($res){
            return $this->success([
            'data' => $res
            ]);
        }
    }


    public function deleteContribution($id){
        $res = contribution::where('id', $id)->delete();
        return $this->success([
            'message' => "Contribution deleted Successfully"
        ]);
    }



}
