<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Department,Roles,Role, User,Permission,CustomRole};
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller{

    
    public function create(){
        $designations = Roles::get(['id','name']);
        $permissions = Permission::get(['id','name']);
        return view('admin.permission.create',compact('designations','permissions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name.*' => 'required|string|unique:permissions,name', 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $names = $request->input('name');
        foreach ($names as $name) {
            if (!empty($name)) {
                if (!Permission::where('name', $name)->exists()) {
                    Permission::create(['name' => $name]);
                }
            }
        }
        $url = route('permissions');
        return $this->success('created','Permission Created successfully.',$url);
    }

    public function index(){
        $permissions = Permission::select('id', 'name')->paginate(20);
        return view('admin.permission.index',compact('permissions'));
    }

    public function edit(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $permission = Permission::findOrFail($id);
        $permission->update(['name' => $request->input('name')]);

        $url = route('permissions');
        return $this->success('update','Permission updated successfully.',$url);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('permissions')->with('success', 'Permission deleted successfully.');
    }

    // Role  functions


    public function RoleIndex(){
        $roles = Role::select('id','name')->paginate(20);
        return view('admin.permission.role',compact('roles'));
    }

    public function RoleStore(Request $request){

        $validator = Validator::make($request->all(), [
            'designation' => 'required|unique:roles,name',
            'permission' => 'nullable|array',
            'permission.*' => 'exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Role::create([
            'name' => $request->input('designation'),
        ]);
        
        // Sync permissions or pass empty array if none selected
        $permissions = $request->input('permission', []);
        $role->syncPermissions($permissions);

        $url = route('role');

        return $this->success('created', 'Role create successfully.', $url);
    }

    public function assign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'designation' => 'required|exists:roles,id',
            'permission' => 'nullable|array',
            'permission.*' => 'exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Role::findOrFail($request->input('designation'));

        // Sync permissions or pass empty array if none selected
        $permissions = $request->input('permission', []);
        $role->syncPermissions($permissions);

        $url = route('role');

        return $this->success('created', 'Permissions assigned successfully.', $url);
    }

    public function RoleEdit($id){
        $role = Role::select('id','name')->findorFail($id);
        $permissions = Permission::get(['id','name']);
        return view('admin.permission.role-edit',compact('role','permissions'));
    }

    public function RoleCreate(){
        $permissions = Permission::get(['id','name']);
        return view('admin.permission.role-create',compact('permissions'));
    }
    
}