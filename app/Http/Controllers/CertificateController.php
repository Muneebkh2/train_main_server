<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Resource;
use App\Certificate;

class CertificateController extends Controller
{
    public function __construct(){ 
        // $this->middleware('auth');
    }

    public function show($id)
    {
        $res = Certificate::where('resource_id', $id)->get();
        return $res;
    }

    public function create(Request $request, $id)
    {
        $this->validate($request,[
            'resource_id' => 'required|int',
            'title' => 'required|string',
            'issued_by' => 'required|string',
            'issued_year' => 'required|date',
            'expertise_level' => 'required|string'
        ]);

        try {
            $certificate = new Certificate();
            $certificate->title = $request->input('title');
            $certificate->issued_by = $request->input('issued_by');
            $certificate->issued_year = $request->input('issued_year');
            $certificate->expertise_level = $request->input("expertise_level");
            $certificate->resource_id = $request->input('resource_id');
            $certificate->save();

            // $resource = $request->input('resource_id');
            // $certificate->resource()->attach($id);
            
            return response()->json(['message' => 'Certification Updated Successfully.'], 201);

        } catch (\Throwable $th) {

            return response()->json(['message' => 'Creation failed !'.$th], 409);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $update_certificate = Certificate::find($id);
            $update_certificate->update($request->all());
            
            return response()->json(['message' => 'New Certificate Created.'], 201);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Updation failed !'.$th], 409);
        }
    }

    public function destroy($id)
    {
        try {
            
            $delete_certificates = Certificate::findOrFail($id);
            $delete_certificates->delete();
            
            return response()->json(['message' => 'Certificate Deleted Successfully.'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Deletation failed !'.$th], 409);
        }
    }
}