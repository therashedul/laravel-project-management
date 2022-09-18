<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Role_has_permission;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class RoleController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Role::orderBy('id','ASC')->paginate(5);
        return $data;
        // return View::first(['admin.roles.index', 'executive.roles.index'], compact('data'));
        // return view('admin.roles.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        $users = DB::table('roles')
                ->select('*')
                ->get();
       return array($permission, $users);
        // return view('roles.create', compact(['permission','users']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $role_id = $request->input('role_id');  
        $permissions = $request->input('permission');  
        foreach ($permissions as $value) {
               $postmeta = New Role_has_permission();
               $postmeta->role_id =  $role_id;  
               $postmeta->permission_id =  $value;  
               $postmeta->save();
           }
           return $request->all();
           
        // return redirect()->route('roles.index')
        //     ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join('role_has_permissions', 'role_has_permissions.permission_id', 'permissions.id')
            ->where('role_has_permissions.role_id',$id)
            ->get();
        return array($role, $rolePermissions);
    
        // return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $id)
            ->get();

        return array($role, $permission, $rolePermissions);
    
        // return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $role_id = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $id)
            ->first();
        $dbprmsID='';
        $permissions = $request->input('permission');  
        $unpermission = $request->input('unpermission');  
        if(isset($permissions) && isset($unpermission)){       
                $results=array_diff($unpermission,$permissions);
                foreach($unpermission as $uncheck) {    
                    foreach ($permissions as $value) { 
                           $dbprmsID = $value; 
                                    DB::table('role_has_permissions')->where('permission_id', $value)->updateOrInsert([
                                        'role_id'=> $role_id->role_id,
                                        'permission_id' => $value, 
                                    ],
                                    [   'role_id'=> $role_id->role_id,
                                        'permission_id' => $value,
                                        'updated_at'=>date('Y-m-d H:i:s')   
                                    ]);
                                }
                            }
                    foreach($results as $result){                       
                            Role_has_permission::where('permission_id',  $result)->where('role_id', $role_id->role_id)->delete();                      
                    }  
                }

                 return $request->all();
                 
        // return redirect()->route('roles.index')
        //     ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::find($id)->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
