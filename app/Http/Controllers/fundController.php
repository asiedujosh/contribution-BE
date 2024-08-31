<?php

namespace App\Http\Controllers;
use App\Traits\HttpResponses;
use App\Models\fund;

use Illuminate\Http\Request;

class fundController extends Controller
{
    use HttpResponses;
    //
    public function index (Request $request){
        $pageNo = $request->input('page');
        $perPage = $request->input('perPage');
        $fund = fund::orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $pageNo);
        return $this->success([
            'data' => $fund,
            'pagination' => [
                'total' => $fund->total(),
                'current_page' => $fund->currentPage(),
                'last_page' => $fund->lastPage()  
            ]
        ]); 
    }

    public function searchFund(Request $request) {
        // $keyword = $request->input('keyword');
        $results = fund::latest()->filter(request(['keyword']))->get();
        return $this->success([
            'data' => $results
           ]);
    }

    public function countFund(){
        $countFund = fund::count();
        return $this->success([
          'data' => $countFund
        ]);
      }

    public function sumFund(){
        $sum = fund::sum('amount');
        return $this->success([
          'data' => $sum
        ]);
      }

    public function store(Request $request){
        $fund = new fund;
        $fund->memberId = $request->memberId;
        $fund->reason = $request->reason;
        $fund->amount = $request->amount;
        $fund->remark = $request->remark;
        $res = $fund->save();

        if($res){
         return $this->success([
             'data' => $fund
            ]);
        }
    }

    public function updateFund(Request $request, $id){
        $formField = [
            'memberId' => $request->memberId,
            'contributionId' => $request->contributionId,
            'amount' => $request->amount,
            'remark' => $request->remark,
            'account' => $request->account
        ];

        $res = fund::where('id', $id)->update($formField);
        if($res){
            return $this->success([
            'data' => $res
            ]);
        }
    }

    public function deleteFund($id){
        $res = fund::where('id', $id)->delete();
        return $this->success([
            'message' => "Fund deleted successfully"
        ]);
    }
}
