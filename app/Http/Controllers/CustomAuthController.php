<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;
use Auth;
use Session;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Routing\Route;


class CustomAuthController extends Controller
{
    public function index()
    { 
        return view('auth.login');

               // if(($userID == $value->user_id) && ($clIp==$dbip) ){
                // $LoginData =  new Login_history ([ 
                //                 'ip' => $value->ip,
                //                 'user_id'=> $value->user_id,
                //             ]);
                //             $LoginData->save(); 
                // }
    }
    public function customLogin(Request $request)
    {  
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]); 
        $users = DB::table('users')
            ->select('*')
            ->where('email',$request->input('email'))
            ->get();
        foreach($users as $user){
            $status =  $user->status_id;
            $credentials = $request->only('email', 'password');
            if($status==1){
                if($user->role_id == 1){
                    if (Auth::attempt($credentials)) {
                        return redirect()->intended('admin')
                        ->withSuccess('Signed in');
                    }else{
                         return redirect('login');
                    }

                }elseif($user->role_id == 2){
                       if (Auth::attempt($credentials)) {
                            return redirect()->intended('executive')
                            ->withSuccess('Signed in');
                        }else{
                            return redirect('login');
                        }
                }else{
                       if (Auth::attempt($credentials)) {
                        return redirect()->intended('developer')
                        ->withSuccess('Signed in');
                    }else{
                         return redirect('login')->withSuccess('Signedup Again');;
                    }
                }
            }else{
                return redirect("login")->withSuccess('Login details are not valid');
            }
        }
    }

    public function dashboard()
    {    
        if(Auth::check()){
            return view('login');       
        }  
        return redirect("login")->withSuccess('You are not allowed to access');
    }
    public function registration()
    {
        return view('auth.registration');
    }
    public function customRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);           
        $data = $request->all();
        $check = $this->create($data);         
        return redirect("dashboard")->withSuccess('You have signed-in');
    }
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }    
    public function home() {
            return view('welcome');            
    }
    public function signOut() {
        Session::flush();
        Auth::logout();  
        return Redirect('home');
    }
}