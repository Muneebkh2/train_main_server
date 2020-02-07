<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Subskills;
use App\Skills;

class SubskillsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        // $this->middleware('auth');
    }

    public function index()
    {
        $res = Subskills::all();
        return response()->json($res);
    }


    public function getBySkillId(Request $request)
    {
        $res = Subskills::whereIn('skills_id', $request)->with('Skills')->get();
        return response()->json($res);
    }

    public function create(Request $request)
    {
        $this->validate($request,[
            'subskills_title' => 'required|string',
            'subskills_desc' => 'required|string',
            'skills_id' => 'required|int'
        ]);

        try {
            $subskills = new Subskills();
            $subskills->subskills_title = $request->input('subskills_title');
            $subskills->subskills_desc = $request->input('subskills_desc');
            $subskills->skills_id = $request->input('skills_id');
            $subskills->save();
            return response()->json(['message' => 'New Skill Created'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Creation Failed!'.PHP_EOL.$th], 409);
        }

    }

    public function update(Request $request, $id)
    {

        try {
            // var_dump($request);
            $req = $request->all();
            // dd($req);
            $u_subskills = Subskills::find($id);
            $u_subskills->update($req);
            return response()->json(['message' => 'Subskills Updated Successfully.'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'updation Failed!'.PHP_EOL.$th], 409);
        }

    }

    public function destroy($id)
    {
        try {
            Subskills::findOrFail($id)->delete();
            return response()->json(['message' => 'Subskills deleted Successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'deleteion Failed!'.PHP_EOL.$th], 409);
        }
    }

}
