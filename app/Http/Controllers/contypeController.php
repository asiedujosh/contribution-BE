<?php

namespace App\Http\Controllers;
use App\Traits\HttpResponses;
use App\Models\contype;

use Illuminate\Http\Request;

class contypeController extends Controller
{
    use HttpResponses;
    //

    public function index (Request $request){
        $pageNo = $request->input('page');
        $perPage = $request->input('perPage');
        $contype = contype::orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $pageNo);
        return $this->success([
            'data' => $contype,
            'pagination' => [
                'total' => $contype->total(),
                'current_page' => $contype->currentPage(),
                'last_page' => $contype->lastPage()  
            ]
        ]); 
    }


    public function searchContype(Request $request) {
        // $keyword = $request->input('keyword');
        $results = contype::latest()->filter(request(['keyword']))->get();
        return $this->success([
            'data' => $results
           ]);
    }


    public function store(Request $request){
        $contype = new contype;
        $contype->contributionId = $request->contributionId;
        $contype->contypeName = $request->contypeName;
        $contype->description = $request->description;
        $res = $contype->save();

        if($res){
         return $this->success([
             'data' => $contype
            ]);
        }
    }


    public function updateContype(Request $request, $id){
        $formField = [
            'contributionId' => $request->contributionId,
            'contypeName' => $request->contypeName,
            'reason' => $request->reason
        ];

        $res = contype::where('id', $id)->update($formField);
        if($res){
            return $this->success([
            'data' => $res
            ]);
        }
    }


    public function deleteContype($id){
        $res = contype::where('id', $id)->delete();
        return $this->success([
            'message' => "Contype deleted Successfully"
        ]);
    }



}
