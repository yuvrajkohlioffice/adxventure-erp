<?php
namespace App\Http\Controllers;
use App\Client;
use Illuminate\Support\Facades\Mail;
use App\Models\ProjectCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectCategoryController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|unique:project_category,name',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $projectCategories =  new ProjectCategory();
        $projectCategories->name = $request->category;
        $projectCategories->save();
        $url = url('/project/category/index');
        return $this->success('created','Project Category ',$url);
    }
    public function index(){
        $projectCategories = ProjectCategory::orderBy('name', 'ASC')->withCount('project')->paginate(20);
        return view('admin.projectcategory.index',compact('projectCategories'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'category' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        try {
            $projectCategory = ProjectCategory::findOrFail($request->id);
            $projectCategory->name = $request->category;
            $projectCategory->save();
    
            $url = url('/project/category/index');
            return $this->success('Updated', 'Project Category', $url);
        } catch (ModelNotFoundException $e) {
            return response()->json(['errors' => 'Project Category not found'], 404);
        }
    }

    public function delete($id)
    {
        $category = ProjectCategory::findOrFail($id);
        if ($category) {
            $category->delete();
            return redirect()->route('project.category.index')->with('success', 'Category deleted successfully.');
        } else {
            return redirect()->route('project.category.index')->with('error', 'Category not found.');
        }
    }


  
}
