<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Cohorts;

class CohortsController extends Controller
{

    public function __construct(){ }

    public function create(Request $request)
    {
        $this->validate($request,[
            'cohorts_name' => 'required|string'
        ]);

        try {
            // inserting object.
            $cohorts = new Cohorts;
            $cohorts->cohorts_name = $request->input('cohorts_name');
            $cohorts->cohorts_desc = $request->input('cohorts_desc');
            $cohorts->save();
            // input initalized.
            $skills = $request->input('skills_id');
            $subskills = $request->input('subskills_id');
            $resource = $request->input('resource_id');
            // ...
            $input['skills_id'] = $skills;
            $input['subskills_id'] = $subskills;
            $input['resource_id'] = $resource;
            // insert foriegn keys.
            $cohorts->skills()->sync($input['skills_id']);
            $cohorts->subskills()->attach($input['subskills_id']);
            $cohorts->resource()->attach($input['resource_id']);

            return response()->json(['message' => 'New Cohorts Created.'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Creation failed !'.$th], 409);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $update_cohorts = Cohorts::find($id);
            $update_cohorts->update($request->all());
            // input initalized.
            $skills = $request->input('skills_id');
            $subskills = $request->input('subskills_id');
            $resource = $request->input('resource_id');
            // ...
            $input['skills_id'] = $skills;
            $input['subskills_id'] = $subskills;
            $input['resource_id'] = $resource;
            // update foriegn keys
            $update_cohorts->skills()->sync($input['skills_id']);
            $update_cohorts->subskills()->sync($input['subskills_id']);
            $update_cohorts->resource()->sync($input['resource_id']);

            return response()->json(['message' => 'Cohorts Updated Successfully'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Cohorts Updation Failed!'.$th], 409);
        }
    }

    public function destroy($id)
    {
        try {
            
            $delete_cohorts = Cohorts::findOrFail($id);
            $delete_cohorts->skills()->detach();
            $delete_cohorts->subskills()->detach();
            $delete_cohorts->resource()->detach();
            $delete_cohorts->delete();

            return response()->json(['message' => 'Cohorts Deleted Successfully'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Cohorts Deleted Failed!'.$th], 409);
        }
    }
}
