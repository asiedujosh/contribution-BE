<?php

namespace App\Http\Controllers;
use App\Traits\HttpResponses;
use App\Models\branches;
use Illuminate\Http\Request;

class branchesController extends Controller
{
    use HttpResponses;
    //
    public function index (Request $request){
        $pageNo = $request->input('page');
        $perPage = $request->input('perPage');
        $branches = branches::orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $pageNo);
        return $this->success([
            'data' => $branches,
            'pagination' => [
                'total' => $branches->total(),
                'current_page' => $branches->currentPage(),
                'last_page' => $branches->lastPage()  
            ]
        ]); 
    }


    public function searchBranches(Request $request) {
        // $keyword = $request->input('keyword');
        $results = branches::latest()->filter(request(['keyword']))->get();
        return $this->success([
            'data' => $results
           ]);
    }


    public function store(Request $request){
        $branches = new branches;
        $branches->branchCode = $request->branchCode;
        $branches->street = $request->street;
        $branches->city = $request->city;
        $branches->state = $request->state;
        $branches->zipCode = $request->zipCode;
        $branches->country = $request->country;
        $branches->contact = $request->contact;
        $res = $branches->save();

        if($res){
         return $this->success([
             'data' => $branches
            ]);
        }
    }


    public function updateBranch(Request $request, $id){
        $formField = [
            'branchCode' => $request->branchCode,
            'street' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'zipCode' => $request->zipCode,
            'country' => $request->country,
            'contact' => $request->contact
        ];

        $res = branch::where('id', $id)->update($formField);
        if($res){
            return $this->success([
            'data' => $res
            ]);
        }
    }


    public function deleteBranch($id){
        $res = branch::where('id', $id)->delete();
        return $this->success([
            'message' => "Branch deleted Successfully"
        ]);
    }


}
