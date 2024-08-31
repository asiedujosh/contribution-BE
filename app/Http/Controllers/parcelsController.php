<?php

namespace App\Http\Controllers;
use App\Traits\HttpResponses;
use App\Models\parcels;

use Illuminate\Http\Request;

class parcelsController extends Controller
{
    use HttpResponses;
    //
    public function index (Request $request){
        $pageNo = $request->input('page');
        $perPage = $request->input('perPage');
        $parcels = parcels::orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $pageNo);
        return $this->success([
            'data' => $parcels,
            'pagination' => [
                'total' => $parcels->total(),
                'current_page' => $parcels->currentPage(),
                'last_page' => $parcels->lastPage()  
            ]
        ]); 
    }


    public function searchParcel(Request $request) {
        // $keyword = $request->input('keyword');
        $results = parcels::latest()->filter(request(['keyword']))->get();
        return $this->success([
            'data' => $results
           ]);
    }


    public function store(Request $request){
        $parcels = new parcels;
        $parcels->referenceNumber = $request->referenceNumber;
        $parcels->senderName = $request->senderName;
        $parcels->senderAddress = $request->senderAddress;
        $parcels->senderContact = $request->senderContact;
        $parcels->receipientName = $request->receipientName;
        $parcels->receipientAddress = $request->receipientAddress;
        $parcels->receipientContact = $request->receipientContact;
        $parcels->receipientType = $request->receipientType;
        $parcels->fromBranchId = $request->fromBranchId;
        $parcels->toBranchId = $request->toBranchId;
        $parcels->weight = $request->weight;
        $parcels->height = $request->height;
        $parcels->width = $request->width;
        $parcels->length = $request->length;
        $parcels->price = $request->price;
        $parcels->status = $request->status;
        $res = $parcels->save();

        if($res){
         return $this->success([
             'data' => $parcels
            ]);
        }
    }


    public function updateParcel(Request $request, $id){
        $formField = [
            'referenceNumber' => $request->referenceNumber,
            'senderName' => $request->senderName,
            'senderAddress' => $request->senderAddress,
            'senderContact' => $request->senderContact,
            'receipientName' => $request->receipientName,
            'receipientAddress' => $request->receipientAddress,
            'receipientContact' => $request->receipientContact,
            'receipientType' => $request->receipientType,
            'fromBranchId' => $request->fromBranchId,
            'toBranchId' => $request->toBranchId,
            'weight' => $request->weight,
            'height' => $request->height,
            'width' => $request->width,
            'length' => $request->length,
            'price' => $request->price,
            'status' => $request->status
        ];

        $res = parcels::where('id', $id)->update($formField);
        if($res){
            return $this->success([
            'data' => $res
            ]);
        }
    }


    public function deleteParcel($id){
        $res = parcels::where('id', $id)->delete();
        return $this->success([
            'message' => "Parcels deleted Successfully"
        ]);
    }

}
