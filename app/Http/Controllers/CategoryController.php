<?php
namespace App\Http\Controllers;
use App\Client;
use Illuminate\Support\Facades\Mail;
use App\Models\{Category,ProjectCategory,CategoryService};
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|unique:category,name',
            'category_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'category_attachment' => 'nullable|mimes:pdf|max:5000',
            'whatshapp_message' => 'required',
            'email_message' => 'nullable',
        ]);
        
        $validator->after(function ($validator) use ($request) {
            if (!$request->hasFile('category_image') && !$request->hasFile('category_attachment')) {
                $validator->errors()->add('category_image', 'At least one of category image or attachment is required.');
                $validator->errors()->add('category_attachment', 'At least one of category image or attachment is required.');
            }
        });
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $attachment = null;
        $image = null;

        if($request->hasFile('category_image')) {
            $image = $request->file('category_image');
            $currentYear = date('Y');
            $currentMonth = date('m');
            $storagePath = "category/images/{$currentYear}/{$currentMonth}/";
            $fileName = time() . '_' . $image->getClientOriginalName();
            $image->move($storagePath, $fileName);
            $image= $storagePath . $fileName; 
        }

        if($request->hasFile('category_attachment')){
            $attachment = $request->file('category_attachment');
            $currentYear = date('Y');
            $currentMonth = date('m');
            $storagePath = "category/pdf/{$currentYear}/{$currentMonth}/";
            $fileName = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move($storagePath, $fileName);
            $attachment= $storagePath . $fileName; 
        }
        $category = new Category();
        $category->name = $request->category;
        $category->image = $image;
        $category->pdf = $attachment;
        $category->whatshapp_message = $request->whatshapp_message;
        $category->email_message = $request->email_message;
        $category->save();

        $url = url('/category/index');
        return $this->success('created', 'Category', $url);
    }

    public function index(){
        $categories = Category::orderBy('name', 'ASC')->withCount('project')->paginate(20);
        $services = ProjectCategory::select('id', 'name')->orderBy('name', 'ASC')->get();
        return view('admin.category.index',compact('categories','services'));
    }

    public function show($id){
        $category = Category::find($id);
        $services = ProjectCategory::select('id', 'name')->orderBy('name', 'ASC')->get();
        $templates = CategoryService::where('category_id', $id)->get()->keyBy('service_id');
        return view('admin.category.show',compact('category','services','templates'));
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'category' => 'required|string',
            'category_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'category_attachment' => 'nullable|mimes:pdf|max:5000',
            'whatshapp_message' => 'required',
            'email_message' => 'nullable',
        ]);
    
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $category = Category::where('category_id', $request->id)->first();
        $attachment = $category->pdf;
        $image = $category->image;

        if($request->hasFile('category_image')) {
            $image = $request->file('category_image');
            $currentYear = date('Y');
            $currentMonth = date('m');
            $storagePath = "category/images/{$currentYear}/{$currentMonth}/";
            $fileName = time() . '_' . $image->getClientOriginalName();
            $image->move($storagePath, $fileName);
            $image= $storagePath . $fileName; 
        }

        if($request->hasFile('category_attachment')){
            $attachment = $request->file('category_attachment');
            $currentYear = date('Y');
            $currentMonth = date('m');
            $storagePath = "category/pdf/{$currentYear}/{$currentMonth}/";
            $fileName = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move($storagePath, $fileName);
            $attachment= $storagePath . $fileName; 
        }

   
        $category->name = $request->category;
        $category->image = $image;
        $category->pdf = $attachment;
        $category->whatshapp_message = $request->whatshapp_message;
        $category->email_message = $request->email_message;
        $category->save();
        $url = url('/category/index');
        return $this->success('Updated', 'Category', $url);
    }

    public function delete($id)
    {
        $category = Category::where('category_id',$id);
        if ($category) {
            $category->delete();
            return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
        } else {
            return redirect()->route('category.index')->with('error', 'Category not found.');
        }
    }

    public function service(Request $request,$id){
        
        $validator = Validator::make($request->all(), [
            'category' => 'required|string',
            'category_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'category_attachment' => 'nullable|mimes:pdf|max:5000',
            'whatshapp_message' => 'required',
            'email_message' => 'nullable',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        // dd($request->all());
        $categoryService = CategoryService::where([['category_id',$id],['service_id',$request->service_id]])->first();
        $attachment = $categoryService ? $categoryService->pdf : null;
        $image = $categoryService ? $categoryService->image : null;
        if($request->hasFile('category_image')) {
            $image = $request->file('category_image');
            $currentYear = date('Y');
            $currentMonth = date('m');
            $storagePath = "category/service/images/{$currentYear}/{$currentMonth}/";
            $fileName = time() . '_' . $image->getClientOriginalName();
            $image->move($storagePath, $fileName);
            $image= $storagePath . $fileName; 
        }

        if($request->hasFile('category_attachment')){
            $attachment = $request->file('category_attachment');
            $currentYear = date('Y');
            $currentMonth = date('m');
            $storagePath = "category/service/pdf/{$currentYear}/{$currentMonth}/";
            $fileName = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move($storagePath, $fileName);
            $attachment= $storagePath . $fileName; 
        }
        
        CategoryService::updateOrCreate(
            // Search condition
            [
                'category_id' => $id,
                'service_id'  => $request->service_id,
            ],
            // Values to update or insert
            [
                'name'             => $request->category,
                'image'            => $image,
                'pdf'              => $attachment,
                'whatshapp_message'=> $request->whatshapp_message,
                'email_message'    => $request->email_message,
            ]
        );

        $url = url('/category/show/'.$id);
        return $this->success('Updated', 'Category', $url);
    }


    public function service_edit(Request $request){
        if($request->service_id && $request->category_id){
            $category = CategoryService::where([['category_id',$request->category_id],['service_id',$request->service_id]])->first();
            if($category){
                $category['image'] = "https://tms.adxventure.com/".$category->image;
                return response()->json([
                    'success' => true,
                    'message' => 'Data fetch successfully !',
                    'data' => $category
                ],200);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'Data not found !',
                    'data' => null
                ],200);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request',
                'data' => null
            ],503);
        }
    }
}

