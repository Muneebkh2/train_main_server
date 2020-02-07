<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Skills;
use App\Subskills;

class SkillsController extends Controller
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
        $res = Skills::all();
        return response()->json($res);
    }

    public function create(Request $request)
    {
        $this->validate($request,[
            'skills_title' => 'required|string',
            'skills_desc' => 'required|string'
        ]);

        try {
            $skills = new Skills();
            $skills->skills_title = $request->input('skills_title');
            $skills->skills_desc = $request->input('skills_desc');
            $skills->save();

            return response()->json(['message' => 'New Skill Created.'], 201);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Creation Failed!'.PHP_EOL.$th], 409);
        }

    }

    public function update(Request $request, $id)
    {

        try {
            $req = $request->all();
            $u_skills = Skills::find($id);
            // var_dump($u_skills);
            $u_skills->update($req);
    
            return response()->json(['message' => 'Skills Updated Successfully.'], 200);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'updation Failed!'.PHP_EOL.$th], 409);
        }

    }

    public function destroy($id)
    {
        try {
            Subskills::where('skills_id', $id)->delete();
            Skills::findOrFail($id)->delete();
            return response()->json(['message' => 'Skills deleted Successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'deleteion Failed!'.PHP_EOL.$th], 409);
        }
    }

}
