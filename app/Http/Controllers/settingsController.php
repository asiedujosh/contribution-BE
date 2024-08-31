<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use App\Models\system_settings;

use Illuminate\Http\Request;

class settingsController extends Controller
{
    use HttpResponses;
    //
    public function index (Request $request){
        $pageNo = $request->input('page');
        $perPage = $request->input('perPage');
        $systemSettings = system_settings::orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $pageNo);
        return $this->success([
            'data' => $systemSettings,
            'pagination' => [
                'total' => $systemSettings->total(),
                'current_page' => $systemSettings->currentPage(),
                'last_page' => $systemSettings->lastPage()  
            ]
        ]); 
    }


    public function searchSystemSettings(Request $request) {
        // $keyword = $request->input('keyword');
        $results = system_settings::latest()->filter(request(['keyword']))->get();
        return $this->success([
            'data' => $results
           ]);
    }


    public function store(Request $request){
        $systemSettings = new system_settings;
        $systemSettings->name = $request->name;
        $systemSettings->email = $request->email;
        $systemSettings->contact = $request->contact;
        $systemSettings->address = $request->address;
        $systemSettings->coverImg = $request->coverImg;
        $res = $systemSettings->save();

        if($res){
         return $this->success([
             'data' => $systemSettings
            ]);
        }
    }


    public function updateSystemSettings(Request $request){
        $formField = [
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'address' => $request->address,
            'coverImg' => $request->coverImg
        ];

        $res = system_settings::where('id', $id)->update($formField);
        if($res){
            return $this->success([
            'data' => $res
            ]);
        }
    }


    public function deleteSystemSettings($id){
        $res = system_settings::where('id', $id)->delete();
        return $this->success([
            'message' => "System settings deleted Successfully"
        ]);
    }
}
