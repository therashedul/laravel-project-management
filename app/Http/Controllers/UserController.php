<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\role_has_permissions;
use Illuminate\Http\Request;
use Arr;


class UserController extends Controller
{
   
    public function index()
    {
        $data = User::orderBy('id', 'asc')->paginate(5);        
        return $data;
        // return view('users.index', compact('data'));
    } 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);
    
        $input = $request->all();
        $input['profile_image'] = $request->image_name;
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);

        return $request->all();
    
        // return redirect()->route('users.index')
        //     ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return array($user);
        // return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return array($user);
    
        // return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'confirmed',
        ]);
        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['profile_image'] = $request->image_name;
        if(!empty($input['password'])) { 
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));    
        }
        $input['role_id'] = $request->role_id;
        $input['status_id'] = $request->status_id;
    
        $user = User::find($id);
        $user->update($input);
        // die();
        return $request->all();
       
        // return redirect()->route('users.index')
        //     ->with('success', 'User updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function destroy($id)
    {
        $user =  User::find($id)->delete();
        return array($user);

        // return redirect()->route('users.index')
        //     ->with('success', 'User deleted successfully.');
    }
}
