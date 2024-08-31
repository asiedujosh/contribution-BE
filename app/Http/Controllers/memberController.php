<?php

namespace App\Http\Controllers;
use App\Traits\HttpResponses;
use App\Traits\SmsTrait;
use App\Models\member;
use GuzzleHttp\Client;



use Illuminate\Http\Request;

class memberController extends Controller
{
    use HttpResponses;
    use SmsTrait;
    //

public function allMembers (Request $request){
    $member = member::where('status', 'active')->get();
    return $this->success([
        'data' => $member
    ]);
}

    public function index (Request $request){
    $pageNo = $request->input('page');
    $perPage = $request->input('perPage');
    $members = member::where('status', 'active')->orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $pageNo);
    return $this->success([
        'data' => $members,
        'pagination' => [
            'total' => $members->total(),
            'current_page' => $members->currentPage(),
            'last_page' => $members->lastPage()  
        ]
    ]); 
}

public function blockedList (Request $request){
    $pageNo = $request->input('page');
    $perPage = $request->input('perPage');
    $members = member::where('status', 'deactive')->orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $pageNo);
    return $this->success([
        'data' => $members,
        'pagination' => [
            'total' => $members->total(),
            'current_page' => $members->currentPage(),
            'last_page' => $members->lastPage()  
        ]
    ]); 
}

public function countMember(){
    $countMember = member::where('status', 'active')->count();
    return $this->success([
      'data' => $countMember
    ]);
  }

public function searchActiveMember(Request $request) {
    // $keyword = $request->input('keyword');
    $results = member::latest() ->where('status', 'active')->filter(request(['keyword']))->get();
    return $this->success([
        'data' => $results
       ]);
}

public function searchBlockedMember(Request $request){
    $results = member::latest() ->where('status', 'deactive')->filter(request(['keyword']))->get();
    return $this->success([
        'data' => $results
       ]);
}


public function testSms(Request $request){
    $this->sendSms($request->contact, 'Welcome to the Afun Royal Family, You are now added to our platform');
    return response()->json(['message' => 'SMS sent successfully']);
}


public function store(Request $request){
    $member = new member;
    $member->memberId = $request->memberId;
    $member->firstName = $request->firstName;
    $member->middleName = $request->middleName;
    $member->lastName = $request->lastName;
    $member->gender = $request->gender === "Male" ? "1" : "2";
    $member->DOB = $request->DOB;
    $member->address = $request->address;
    $member->occupation = $request->occupation;
    $member->contact = $request->contact;
    $member->contactOne = $request->contactOne;
    $member->email = $request->email;
    $member->status = "active";
    $res = $member->save();

    $message = 'Hello ' . $request->firstName . ', Welcome to the AFUN ROYAL FAMILY, you are now added to our platform';

    if($res){
        $this->sendSms($request->contact,  $message);
     return $this->success([
         'data' => $member
        ]);
    }
}


public function updateMember(Request $request, $id){
    $formField = [
        'firstName' => $request->firstName,
        'middleName' => $request->middleName,
        'lastName' => $request->lastName,
        'gender' => $request->gender === "Male" ? "1" : "2",
        'DOB' => $request->DOB,
        'address' => $request->address,
        'occupation' => $request->occupation,
        'contact' => $request->contact,
        'contactOne' => $request->contactOne,
        'email' => $request->email,
    ];

    $res = member::where('id', $id)->update($formField);
    if($res){
        return $this->success([
        'data' => $res
        ]);
    }
}

public function blockMember(Request $request, $id){
    $formField = [
        'status' => 'deactive'
    ];

    $res = member::where('id', $id)->update($formField);
    if($res){
        return $this->success([
        'data' => $res
        ]);
    } 
}


public function deleteMember($id){
    $res = member::where('id', $id)->delete();
    return $this->success([
        'message' => "Member deleted Successfully"
    ]);
}

}
