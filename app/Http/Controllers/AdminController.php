<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Document;
use App\Models\ImageUpload;
use App\Models\Role_has_permission;
use App\Models\Task;
use App\Models\TaskMeta;
use DB;
use Image;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Jenssegers\Date\Date;
// use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjetController;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){  
 
        return view('admin.index');
    } 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }

    // ================== User=============
    public function user(){       
        $user  = new UserController;      
        $data = $user->index();
        return view('admin.users.index', compact('data'));
    } 
     public function usercreate(){
        return view('admin.users.create');
    }
    public function usersupload(Request $request){
        $userName = Auth::user()->name;
        $imageUpload = new ImageUpload;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fullImagename = $file->getClientOriginalName();
            $imagename = pathinfo($fullImagename, PATHINFO_FILENAME);
            $extension = pathinfo($fullImagename, PATHINFO_EXTENSION);
            $filePath = $imagename.'_'.time().'.'.$extension;
            $allowedfileExtension=['png','jpg','gif','bmp','jpeg'];
            $check=in_array($extension,$allowedfileExtension);
            if($check){
                $imgFile = Image::make($file->getRealPath());
                $imagepath = public_path('/images');
                $imgFile->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($imagepath.'/'.$filePath);

                $imagesPath = public_path('/thumbnail');
                $imgFile->resize(100, 80)->save($imagesPath.'/'.$filePath);

                $singleImagesPath = public_path('/single');
                $imgFile->resize(750, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($singleImagesPath.'/'.$filePath);
          
                $destinationPath = public_path('/upload');
                $path = $file->move($destinationPath, $filePath); 
            }
            $imageUpload->name = $filePath;
            $imageUpload->title = $imagename;
            $imageUpload->alt = $imagename;
            $imageUpload->slug = $filePath;
            $imageUpload->username = $userName;
            $imageUpload->path = $path;
            $imageUpload->extention = '.'.$extension;
            $imageUpload->save();
        }
    } 
    public function usersfetch(Request $request){
    //  $images =\File::allFiles(public_path('upload'));
     $images = DB::table('image_uploads')->orderBy('id', 'DESC')->get();
     $output = '<div class="file-manager-content">';        
        $output .= '<div id="image_file_upload_response">';
        foreach ($images as $image){
                $output .= '<div class="col-file-manager" id="img_col_id_'. $image->id .'">';
                    $output .= '<div class="file-box"  data-file-caption="'. $image->caption .'" data-file-description="'. $image->description .'" data-file-alt="'. $image->alt .'" data-file-title="'. $image->title .'" data-file-name="'. $image->name .'"  data-file-id="'. $image->id .'" data-file-path="'.asset('upload/' . $image->name).'" data-file-path-editor="'.asset('upload/' . $image->name).'">';
                    $fileextention = ['.jpg','.png','.bmp','.gif','.jpeg'];
                    for($i=0; $i<count($fileextention); $i++){
                        if($image->extention == $fileextention[$i]){
                          $output .= '<div class="image-container">';  
                                $output .= '<img src="'.asset('images/' . $image->name).'" alt="'. $image->alt .'" title="'. $image->title .'" loading="lazy" class="img-responsive">';
                                    $name = substr($image->name, 0, 20).'...';
                                    $output .= '<span class="file-name">'.$name.'</span>';
                            $output .= '</div>';
                        }
                    }
                    $output .= '</div>';
                $output .= '</div>';			        
            }
           $output .= '</div>';
        $output .= '</div>';
        echo $output;
    }
    public function usersuploaddelete(Request $request) {
        $val = $request->name;
        $userNames =  User::where('profile_image', $val)->get();
        if(!empty($userNames[0]->profile_image)){
            if(($val == $userNames[0]->profile_image)){
                $msg = '<div class="alert alert-success text-center">This image is already used.</div>';
                $action = "image";
                return response()->json(array('msg'=> $msg, 'action'=>$action), 200);
            }
            }else{
                ImageUpload::where('name', $val)->delete();
                $lines = ['upload/','images/','single/','thumbnail/'];
                for($i = 0; $i < count($lines); $i++) {
                    $value =  $lines[$i];
                    $path = public_path($value).$val;
                    if (file_exists($path)) {
                        unlink($path);
                        }
                    }    
                return response()->json(['data'=>$val],200);
            }        
    } 
 

    
    public function userstore(Request $request){   
        $user  = new UserController;  
        $user->store($request);
        return redirect()->route('admin.users')
            ->with('success', 'User created successfully.');
    }
    public function usershow($id){
        $users  = new UserController;  
        $urs = $users->show($id);
        $user = $urs[0];
        return view('admin.users.show', compact('user'));
    }
    public function useredit($id){
        
        $users  = new UserController;  
        $urs = $users->edit($id);
        // print_r($urs[0]);
        // die();
        $user = $urs[0];
        return view('admin.users.edit', compact('user'));
    } 
    public function userpublish($id){
        $publish =  User::find($id);
        $publish->status_id = 0;
        $publish->save();
        return redirect('admin/users');
    } 
    public function userunpublish($id){
        $publish =  User::find($id);
        $publish->status_id = 1;
        $publish->save();
        return redirect('admin/users');
    }
    public function userupdate(Request $request, $id) {   
        $user  = new UserController;  
        $user->update($request, $id);
        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully.');
    }
    public function userdestroy($id)
    {
        $user  = new UserController;  
        $urs = $user->destroy($id);
        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully.');
    }
    // ================== Role=============
    public function role(){       
        $role  = new RoleController;      
        $data = $role->index();
        return view('admin.roles.index', compact('data'));
    } 
     public function rolecreate(){
        $role  = new RoleController;  
        $roles = $role->create();
        $permission = $roles[0];
        $users = $roles[1];
        return view('admin.roles.create', compact(['permission','users']));
    }

    public function rolestore(Request $request){   
        $role  = new RoleController;  
        $role->store($request);
        return redirect()->route('admin.roles')
            ->with('success', 'Role created successfully.');
    }
    public function roleshow($id){
        $role  = new RoleController;  
        $roles = $role->show($id);
        $role = $roles[0];
        $rolePermissions = $roles[1];
        return view('admin.roles.show', compact('role', 'rolePermissions'));
    }
    public function roleedit($id){
        $role  = new RoleController;  
        $roles = $role->edit($id);
        $role = $roles[0];
        $permission = $roles[1];
        $rolePermissions = $roles[2];
        return view('admin.roles.edit', compact('role', 'permission', 'rolePermissions'));
    }
    public function roleupdate(Request $request, $id) {   
        $role  = new RoleController;  
        $role->update($request, $id);
        return redirect()->route('admin.roles')
            ->with('success', 'Role updated successfully.');
    } 
    // ================== permission=============
    public function permission(){       
        $permission  = new PermissionController;      
        $data = $permission->index();
        return view('admin.permissions.index', compact('data'));
    } 
     public function permissioncreate(){
        return view('admin.permissions.create');
    }

    public function permissionstore(Request $request){   
        $permissions  = new PermissionController;  
        $permissions->store($request);
        return redirect()->route('admin.permissions')
            ->with('success', 'Permission created successfully.');
    }
    public function permissionshow($id){
        $permission  = new PermissionController;  
        $prms = $permission->show($id);
        $permissions = $prms[0];
        return view('admin.permissions.show', compact('permissions'));
    }
    public function permissionedit($id){
        $permission  = new PermissionController;  
        $permission = $permission->edit($id);
        $permissions = $permission[0];
        return view('admin.permissions.edit', compact('permissions'));
    }
    public function permissionupdate(Request $request, $id) {   
        $role  = new PermissionController;  
        $role->update($request, $id);
        return redirect()->route('admin.permissions')
            ->with('success', 'Permission updated successfully.');
    }
    
    public function permissionsearch(Request $request){
         $rhps = DB::table('role_has_permissions')->get();
            $permissions = DB::table('permissions')->get();
            $roles = DB::table('roles')->get();
            $projects=DB::table('permissions')
                ->where('name','LIKE','%'.$request->search."%")
                ->get(); 
           
              if (!empty($projects[0]->name)){
                echo '<table class="table table-hover">
                          <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th width="200px">Action</th>
                            </tr>
                        </thead>
                        <tbody>';
                    }else{
                        echo '<h2 class="text-center">No data found</h2>';
                    }
                        foreach ($projects as $project):
                                echo '<tr>';
                                    echo '<td class="align-middle">';
                                        echo $project->id;
                                    echo '</td>';    
                                    echo '<td class="align-middle">';
                                        echo $project->name;
                                    echo '</td>';  
                                    echo '<td class="align-middle">';
                                            foreach ($rhps as $rhp){
                                                    foreach ($roles as $role){
                                                        if ($role->id == Auth::user()->role_id && $role->id == $rhp->role_id){
                                                            if ($rhp->permission_id == $project->id){
                                                                    $name = $project->name;
                                                                  if(stristr($name, 'user') || stristr($name, 'role') || stristr($name, 'permission') || stristr($name, 'project') || stristr($name, 'document') || stristr($name, 'menu') || stristr($name, 'media')){
                                                                        $value = substr(strstr($name, '-'), 1);
                                                                        // echo $value;
                                                                        if ($value == 'list' || $value == 'create' || $value == 'edit' || $value == 'delete'){
                                                                        echo '<a class="btn btn-primary btn-sm"
                                                                                            href="permissions.show.'.$project->id.'"><i
                                                                            class="fas fa-eye"></i></a>';
                                                                        echo '<a class="btn btn-primary btn-sm"
                                                                                        href="permissions.edit.'.$project->id.'"><i
                                                                                            class="fas fa-edit"></i></a>'; 
                                                                        echo '<a class="btn btn-danger btn-sm"
                                                                                        href="permissions.permissiondelete.'.$project->id.'"><i class="fa fa-trash"></i></a>';
                                                                        } 
                                                                        else{
                                                                        if (Auth::user()->role_id == '1'){
                                                                                 echo '<a class="btn btn-danger btn-sm"
                                                                                        href="permissions.permissiondelete.'.$project->id.'"><i class="fa fa-trash"></i></a>';
                                                                                 }
                                                                        } 
                                                                }
                                                            }
                                                        }
                                                    }
                                            }
                                    echo '</td>';
                                echo '</tr>';
                            endforeach;
                     
            echo '</tbody></table>';
    }
    public function permissiondelete($id)
    {
        $permission  = new PermissionController;  
        $permission->destroy($id);
        return redirect()->route('admin.permissions')
            ->with('success', 'Pemission deleted successfully.');
    }  
    public function deletepermission($id){
        $permission  = new PermissionController;  
        $permission->destroy($id);
        return redirect()->route('admin.permissions')
            ->with('success', 'Pemission deleted successfully.');
    }
     //============================ Media ===============
    public function media(){
        $data = ImageUpload::orderBy('id', 'desc')->paginate(16);   
        return view('admin.media.index', compact('data'));
        // return view('admin.media.index');        
    }    
    public function mediaupload(Request $request){
        $userName = Auth::user()->name;
        $imageUpload = new ImageUpload;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fullImagename = $file->getClientOriginalName();
            $imagename = pathinfo($fullImagename, PATHINFO_FILENAME);
            $extension = pathinfo($fullImagename, PATHINFO_EXTENSION);
            $filePath = $imagename.'_'.time().'.'.$extension;
            $allowedfileExtension=['png','jpg','gif','bmp','jpeg'];
            $check=in_array($extension,$allowedfileExtension);
            if($check){
                $imgFile = Image::make($file->getRealPath());
                $imagepath = public_path('/images');
                $imgFile->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($imagepath.'/'.$filePath);

                $imagesPath = public_path('/thumbnail');
                $imgFile->resize(100, 80)->save($imagesPath.'/'.$filePath);

                $singleImagesPath = public_path('/single');
                $imgFile->resize(750, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($singleImagesPath.'/'.$filePath);
          
                $destinationPath = public_path('/upload');
                $path = $file->move($destinationPath, $filePath); 
            }else{
                $path =  $file->move(public_path('/files'), $filePath);
            }
            $imageUpload->name = $filePath;
            $imageUpload->title = $imagename;
            $imageUpload->alt = $imagename;
            $imageUpload->slug = $filePath;
            $imageUpload->username = $userName;
            $imageUpload->path = $path;
            $imageUpload->extention = '.'.$extension;
            $imageUpload->save();
    //=========================  For Image Information  ===============================
        // $imagename = pathinfo($fileName, PATHINFO_FILENAME);
        // $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        // $imagename = preg_replace('/\..+$/', '',  $fileName);
        // $path = $file->getRealPath();
        // $size = $file->getSize();
        // $mimtype = $file->getMimeType();            
        }
    } 
    public function mediauploaddelete(Request $request) {
        $val = $request->name;
        $imageId = $request->id;   
        $projecntNames =  Project::where('project_logo', $val)->get();
        if(!empty($projecntNames[0]->project_logo)){
            if(($val == $projecntNames[0]->project_logo)){
                $msg = '<div class="alert alert-success text-center">This image is already used.</div>';
                $action = "image";
                return response()->json(array('msg'=> $msg, 'action'=>$action), 200);
            }
        } 
        $documents =  Document::where('document_image_id', $imageId)->get();
            if(!empty($documents[0]->document_image_id)){
                if(($imageId == $documents[0]->document_image_id)){
                    $msg = '<div class="alert alert-success text-center">This file is already used.</div>';
                    $action = "file";
                    return response()->json(array('msg'=> $msg, 'action'=>$action), 200);     
                }
             } else{
                ImageUpload::where('name', $val)->delete();
                $lines = ['upload/','images/','single/','thumbnail/','files/'];
                for($i = 0; $i < count($lines); $i++) {
                    $value =  $lines[$i];
                    $path = public_path($value).$val;
                    if (file_exists($path)) {
                        unlink($path);
                        }
                    }    
                return response()->json(['data'=>$val],200);
              
            } 
    }
    public function mediafetch(Request $request){
    //  $images =\File::allFiles(public_path('upload'));
     $images = DB::table('image_uploads')->orderBy('id', 'DESC')->get();
     $output = '<div class="file-manager-content">';        
        $output .= '<div id="image_file_upload_response">';
        foreach ($images as $image){
                $output .= '<div class="col-file-manager" id="img_col_id_'. $image->id .'">';
                    $output .= '<div class="file-box"  data-file-caption="'. $image->caption .'" data-file-description="'. $image->description .'" data-file-alt="'. $image->alt .'" data-file-title="'. $image->title .'" data-file-name="'. $image->name .'"  data-file-id="'. $image->id .'" data-file-path="'.asset('upload/' . $image->name).'" data-file-path-editor="'.asset('upload/' . $image->name).'">';
                       if($image->extention == '.pdf'){
                        $output .= '<div class="image-container">';  
                            $output .= '<img src="'.asset('img/' . 'pdf.png').'" id="'. $image->id .'" loading="lazy" class="img-responsive">';
                            $name = substr($image->name, 0, 15).'...';
                            $output .= '<span class="file-name">'.$name.'</span>';
                            $output .= '</div>';
                        }     
                        elseif($image->extention == '.docx'){
                            $output .= '<div class="image-container">';  
                                $output .= '<img src="'.asset('img/' . 'docx.png').'"  loading="lazy" class="img-responsive">';
                                $name = substr($image->name, 0, 15).'...';
                                $output .= '<span class="file-name">'.$name.'</span>';
                            $output .= '</div>';
                        }    
                        elseif($image->extention == '.xlsx'){
                            $output .= '<div class="image-container">';  
                                $output .= '<img src="'.asset('img/' . 'xlsx.png').'"  loading="lazy" class="img-responsive">';
                                $name = substr($image->name, 0, 15).'...';
                                $output .= '<span class="file-name">'.$name.'</span>';
                            $output .= '</div>';
                        } else{
                            $output .= '<div class="image-container">';  
                                $output .= '<img src="'.asset('images/' . $image->name).'" alt="'. $image->alt .'" title="'. $image->title .'" loading="lazy" class="img-responsive">';
                                    $name = substr($image->name, 0, 20).'...';
                                    // $name = mb_strimwidth($image->image, 0, 10, "...");  // another way
                                    // $output .= '<button type="button" class="btn btn-link remove_image" id="'.$image->id.'">Remove</button>';
                                    $output .= '<span class="file-name">'.$name.'</span>';
                            $output .= '</div>';
                        }
                    $output .= '</div>';
                $output .= '</div>';			        
            }
           $output .= '</div>';
        $output .= '</div>';
        echo $output;
    }
    public function mediasearch(Request $request){
            $images=DB::table('image_uploads')
                ->where('name','LIKE','%'.$request->search."%")
                ->get(); 
           if (!empty($images[0]->name)){
            foreach ($images as $image):
                echo '<div class="col-file-manager col-sm-6 col-md-2 col-lg-2 mb-5" id="img_col_id_' . $image->id . '">';
                    echo '<div class="file-box" data-file-name="'. $image->name .'"  data-file-id="'. $image->id .'" data-file-path="'.asset('upload/' . $image->name).'" data-file-path-editor="'.asset('upload/' . $image->name).'">';
                    if($image->extention == '.pdf'){
                      
                        echo '<div class="image-container-file">';
                            echo '<input type="hidden" id="selected_img_name_'. $image->name .'" value="'. $image->name .'">';
                            echo     '<a id="btn_delete_post_main_image" onclick="myFunction_'. $image->id .'()"
                                     class="btn btn-danger btn_img_delete btn-sm">
                                     <i class="fa fa-times"></i>
                                 </a>';
                               echo '<button type="button" class="btn-field" id="file_manager_'. $image->id .'"
                                     data-toggle="modal" data-target="#image_file_manager_'. $image->id .'">
                                         <figure>
                                         <img src="'.asset('img/' . 'pdf.png').'"  loading="lazy" class="img-responsive search-img file-image" style="max-height:200px">
                                        <figcaption class="text-center">
                                            '. mb_strimwidth($image->title, 0, 20, '...') .' </figcaption>
                                     </figure>
                                 </button>';

                    echo '</div>';
                    } elseif($image->extention == '.docx'){
                          echo '<div class="image-container-file">';
                            echo '<input type="hidden" id="selected_img_name_'. $image->name .'" value="'. $image->name .'">';
                            echo     '<a id="btn_delete_post_main_image" onclick="myFunction_'. $image->id .'()"
                                     class="btn btn-danger btn_img_delete btn-sm">
                                     <i class="fa fa-times"></i>
                                 </a>';
                               echo '<button type="button" class="btn-field" id="file_manager_'. $image->id .'"
                                     data-toggle="modal" data-target="#image_file_manager_'. $image->id .'">
                                         <figure>
                                         <img src="'.asset('img/' . 'docx.png').'"  loading="lazy" class="img-responsive search-img file-image" style="max-height:200px">
                                        <figcaption class="text-center">
                                            '. mb_strimwidth($image->title, 0, 20, '...') .' </figcaption>
                                     </figure>
                                 </button>';

                    echo '</div>';
                    } elseif($image->extention == '.xlsx'){
                        echo '<div class="image-container-file">';
                            echo '<input type="hidden" id="selected_img_name_'. $image->name .'" value="'. $image->name .'">';
                            echo     '<a id="btn_delete_post_main_image" onclick="myFunction_'. $image->id .'()"
                                     class="btn btn-danger btn_img_delete btn-sm">
                                     <i class="fa fa-times"></i>
                                 </a>';
                               echo '<button type="button" class="btn-field" id="file_manager_'. $image->id .'"
                                     data-toggle="modal" data-target="#image_file_manager_'. $image->id .'">
                                         <figure>
                                         <img src="'.asset('img/' . 'xlsx.png').'"  loading="lazy" class="img-responsive search-img file-image" style="max-height:200px">
                                        <figcaption class="text-center">
                                            '. mb_strimwidth($image->title, 0, 20, '...') .' </figcaption>
                                     </figure>
                                 </button>';

                    echo '</div>';
                    }else{
                    echo '<div class="image-container-file">';
                            echo '<input type="hidden" id="selected_img_name_'. $image->name .'" value="'. $image->name .'">';
                            echo     '<a id="btn_delete_post_main_image" onclick="myFunction_'. $image->id .'()"
                                     class="btn btn-danger btn_img_delete btn-sm">
                                     <i class="fa fa-times"></i>
                                 </a>';
                               echo '<button type="button" class="btn-field" id="file_manager_'. $image->id .'"
                                     data-toggle="modal" data-target="#image_file_manager_'. $image->id .'">
                                         <figure>
                                         <img src="'.asset('images/' . $image->name).'" alt="" name="file" class="img-responsive search-img">
                                         
                                        <figcaption class="text-center">
                                            '. mb_strimwidth($image->title, 0, 20, '...') .' </figcaption>
                                     </figure>
                                 </button>';
                    echo '</div>';
                    }

                echo '</div> </div>';
                
            endforeach;
             }else{
                        echo '<h2 class="text-center">No data found</h2>';
                    }
             echo '<div id="image_file_bottom">';
            echo '</div>';

    }
  
    //============================ Projects ===============
    public function projects(){
        $data = Project::orderBy('id', 'desc')->paginate(5);      
        return view('admin.project.index', compact('data'));
        // return view('admin.project.index');        
    }    
    public function projectscreate(){
        $images =\File::allFiles(public_path('upload'));     
        $checks =  DB::table('image_uploads')->get();  
        return view('admin.project.create', compact(['images','checks']));
    } 
    public function projectsupload(Request $request){
        $userName = Auth::user()->name;
        $imageUpload = new ImageUpload;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fullImagename = $file->getClientOriginalName();
            $imagename = pathinfo($fullImagename, PATHINFO_FILENAME);
            $extension = pathinfo($fullImagename, PATHINFO_EXTENSION);
            $filePath = $imagename.'_'.time().'.'.$extension;
            $allowedfileExtension=['png','jpg','gif','bmp','jpeg'];
            $check=in_array($extension,$allowedfileExtension);
            if($check){
                $imgFile = Image::make($file->getRealPath());
                $imagepath = public_path('/images');
                $imgFile->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($imagepath.'/'.$filePath);

                $imagesPath = public_path('/thumbnail');
                $imgFile->resize(100, 80)->save($imagesPath.'/'.$filePath);

                $singleImagesPath = public_path('/single');
                $imgFile->resize(750, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($singleImagesPath.'/'.$filePath);
          
                $destinationPath = public_path('/upload');
                $path = $file->move($destinationPath, $filePath); 
            }
            $imageUpload->name = $filePath;
            $imageUpload->title = $imagename;
            $imageUpload->alt = $imagename;
            $imageUpload->slug = $filePath;
            $imageUpload->username = $userName;
            $imageUpload->path = $path;
            $imageUpload->extention = '.'.$extension;
            $imageUpload->save();
    //=========================  For Image Information  ===============================
        // $imagename = pathinfo($fileName, PATHINFO_FILENAME);
        // $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        // $imagename = preg_replace('/\..+$/', '',  $fileName);
        // $path = $file->getRealPath();
        // $size = $file->getSize();
        // $mimtype = $file->getMimeType();            
        }
    }    
 
    public function projectsstore(Request $request) {      
        $storproject = new Project;
        $storproject->project_name = $request->input('project_name');
        $storproject->project_logo = $request->input('image_name');  
        $storproject->image_id = $request->input('image_id');  
        if(!empty($request->input('image_name'))){
            $storproject->save(); 
            $id = $request->image_id;
            DB::table('image_uploads')
                ->where('id', $id)
                ->update( [
                    'alt' => $request->alt, 
                    'title' => $request->title,  
                    'caption' => $request->caption, 
                    'description' => $request->description,
                    ]);
             return redirect()->intended(route('admin.projects'))->with('success', 'Project add successfully.'); 
            }else{
              return redirect()->intended(route('admin.projects'))->with('worning', 'Please select image.'); 
            }
    }
    public function projectsedit($id){    
        $projectId = Project::find($id);
        $imageId = $projectId->image_id;
        $hollproject = DB::table('image_uploads')
        ->join('projects', 'projects.image_id', '=', 'image_uploads.id')
        ->select('*')
        ->where('projects.id', $id)
        ->get();
        $editproject = $hollproject[0];
        return view('admin.project.edit', compact('editproject'));
            
    }
    public function projectsupdate(Request $request, $id) { 
        print_r($request->upload_id);
        $input['project_name'] = $request->project_name;
        $input['project_logo'] =  $request->image_name;   
        if($request->image_id == null){
            $input['image_id']=$request->upload_id;
        }else{
            $input['image_id'] =$request->image_id;
        }
        if(!empty($request->input('image_name'))){
            $updateId = Project::find($id);
            $updateId->update($input);
            DB::table('image_uploads')
                ->where('id', $request->upload_id)
                ->update([
                    'alt' => $request->alt, 
                    'title' => $request->title,  
                    'caption' => $request->caption, 
                    'description' => $request->description,
                    ]);
            return redirect()->intended(route('admin.projects'))->with('success', 'Project updated successfully.'); 
        }else{
            return redirect()->intended(route('admin.projects'))->with('worning', 'Please select document.'); 
             
            }
        
    }
    public function uploaddelete(Request $request) {
        $val = $request->name;
        $projecntNames =  Project::where('project_logo', $val)->get();
        if(!empty($projecntNames[0]->project_logo)){
            if(($val == $projecntNames[0]->project_logo)){
                $msg = '<div class="alert alert-success text-center">This image is already used.</div>';
                $action = "image";
                return response()->json(array('msg'=> $msg, 'action'=>$action), 200);
            }
            }else{
                ImageUpload::where('name', $val)->delete();
                $lines = ['upload/','images/','single/','thumbnail/'];
                for($i = 0; $i < count($lines); $i++) {
                    $value =  $lines[$i];
                    $path = public_path($value).$val;
                    if (file_exists($path)) {
                        unlink($path);
                        }
                    }    
                return response()->json(['data'=>$val],200);
            }        
    } 
    public function projectsfetch(Request $request){
    //  $images =\File::allFiles(public_path('upload'));
     $images = DB::table('image_uploads')->orderBy('id', 'DESC')->get();
     $output = '<div class="file-manager-content">';        
        $output .= '<div id="image_file_upload_response">';
        foreach ($images as $image){
                $output .= '<div class="col-file-manager" id="img_col_id_'. $image->id .'">';
                    $output .= '<div class="file-box"  data-file-caption="'. $image->caption .'" data-file-description="'. $image->description .'" data-file-alt="'. $image->alt .'" data-file-title="'. $image->title .'" data-file-name="'. $image->name .'"  data-file-id="'. $image->id .'" data-file-path="'.asset('upload/' . $image->name).'" data-file-path-editor="'.asset('upload/' . $image->name).'">';
                    $fileextention = ['.jpg','.png','.bmp','.gif','.jpeg'];
                    for($i=0; $i<count($fileextention); $i++){
                        if($image->extention == $fileextention[$i]){
                          $output .= '<div class="image-container">';  
                                $output .= '<img src="'.asset('images/' . $image->name).'" alt="'. $image->alt .'" title="'. $image->title .'" loading="lazy" class="img-responsive">';
                                    $name = substr($image->name, 0, 20).'...';
                                    $output .= '<span class="file-name">'.$name.'</span>';
                            $output .= '</div>';
                        }
                    }
                    $output .= '</div>';
                $output .= '</div>';			        
            }
           $output .= '</div>';
        $output .= '</div>';
        echo $output;
    }
    /*
    Image search 
    */
    public function projectssearch(Request $request){
            $images=DB::table('image_uploads')
                ->where('name','LIKE','%'.$request->search."%")
                ->get(); 
            foreach ($images as $image):
                echo '<div class="col-file-manager" id="img_col_id_' . $image->id . '">';
                    echo '<div class="file-box" data-file-name="'. $image->name .'"  data-file-id="'. $image->id .'" data-file-path="'.asset('upload/' . $image->name).'" data-file-path-editor="'.asset('upload/' . $image->name).'">';
                   echo '<div class="image-container">';
                            echo '<img src="'.asset('images/' . $image->name).'" alt="" name="file" class="img-responsive">';
                                $name = substr($image->name, 0, 20).'...';
                            echo '<span class="file-name">'.$name.'</span>';
                    echo '</div>';
                echo '</div> </div>';
            endforeach;

    } 
     /*
    Image search 
    */
    public function projectsimagesearch(Request $request){
            $rhps = DB::table('role_has_permissions')->get();
            $permissions = DB::table('permissions')->get();
            $roles = DB::table('roles')->get();
            $projects=DB::table('projects')
                ->where('project_name','LIKE','%'.$request->search."%")
                ->get(); 
           
              if (!empty($projects[0]->project_name)){
                echo '<table class="table table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Logo</th>
                                    <th>Create Date</th>
                                    <th width="200px" class="text-center">Action</th>
                                </tr>
                            </thead>
                        <tbody>';
                    }else{
                        echo '<h2 class="text-center">No data found</h2>';
                    }
                        foreach ($projects as $project):
                                echo '<tr>';
                                    echo '<td class="align-middle">';
                                        echo $project->id;
                                    echo '</td>';    
                                    echo '<td class="align-middle">';
                                        echo $project->project_name;
                                    echo '</td>';  
                                    echo '<td class="align-middle">';
                                        echo '<img src="'.asset('thumbnail/' . $project->project_logo).'" alt="" name="file" class="img-responsive">';
                                    echo '</td>';
                                    echo '<td class="align-middle">';
                                        echo date('d-m-Y', strtotime($project->created_at));
                                    echo '</td>';                    
                                    
                                    echo '<td class="align-middle">';
                                            foreach ($rhps as $rhp){
                                                foreach ($permissions as $permission){
                                                    foreach ($roles as $role){
                                                        if ($role->id == Auth::user()->role_id && $role->id == $rhp->role_id){
                                                            if ($rhp->permission_id == $permission->id){
                                                                    $name = $permission->name;
                                                                    if (stristr($name, 'project')){
                                                                        $value = substr(strstr($name, '-'), 1);
                                                                        // echo $value;
                                                                        if ($value == 'edit'){
                                                                        echo '<a class="btn btn-primary btn-sm"
                                                                                        href="projects.edit.'.$project->id.'"><i
                                                                                            class="fas fa-edit"></i></a>';
                                                                        }
                                                                        elseif ($value == 'delete'){
                                                                            echo '<a class="btn btn-danger btn-sm"
                                                                                        href="projects.imagedestroy.'.$project->id.'"><i class="fa fa-trash"></i></a>';
                                                                            
                                                                        }
                                                                    }
                                                            }
                                                        }
                                                    }

                                                }
                                            }
                                    echo '</td>';
                                echo '</tr>';
                            endforeach;
                     
            echo '</tbody></table>';

    }
    
    public function projectsdelete(Request $request, $id) {
      Project::where('id', $id)->delete();
      return redirect()->intended(route('admin.projects'))->with('success', 'Project updated successfully.'); 
    }      
    public function projectsimagedelete(Request $request, $id) {
      Project::where('id', $id)->delete();
      return redirect()->intended(route('admin.projects'))->with('success', 'Project updated successfully.'); 
    }  
     //============================ Documents ===============
    public function documents(){
        $data = Document::orderBy('id', 'desc')->paginate(5);      
        return view('admin.document.index', compact('data'));
             
    }    
    public function documentscreate(){
    
        $projects =  DB::table('projects')->get();  
        return view('admin.document.create', compact(['projects']));
    } 
    public function documentsupload(Request $request){
        $caption = $request->input('caption');
        $userName = Auth::user()->name;
        $imageUpload = new ImageUpload;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fullImagename = $file->getClientOriginalName();
            $imagename = pathinfo($fullImagename, PATHINFO_FILENAME);
            $extension = pathinfo($fullImagename, PATHINFO_EXTENSION);
            // $extension = $file->getClientOriginalExtension();
            $filePath = $imagename.'_'.time().'.'.$extension;
            $allowedfileExtension=['xlsx','pdf','docx'];
            $check=in_array($extension,$allowedfileExtension);
            if($check){
               $path =  $file->move(public_path('files'), $filePath);
            }
            $imageUpload->name = $filePath;
            $imageUpload->title = $imagename;
            $imageUpload->alt = $imagename;
            $imageUpload->slug = $filePath;
            $imageUpload->caption = $caption;
            $imageUpload->path = $path;
            $imageUpload->username = $userName;
            $imageUpload->extention = '.'.$extension;
            $imageUpload->save();
         
        }
    }    
 
    public function documentsstore(Request $request) {  
      
        $storproject = new Document;
        $storproject->document_title = $request->input('document_title');
        $storproject->document_image_id = $request->input('image_id');  
        $storproject->project_id = $request->input('project_id');  
        if(!empty($request->input('image_id'))){
            $storproject->save();
            $id = $request->image_id;
            DB::table('image_uploads')
                  ->where('id', $id)
                  ->update( [
                      'alt' => $request->alt, 
                      'title' => $request->title,  
                      'caption' => $request->caption, 
                      'description' => $request->description,
                    ]);
            return redirect()->intended(route('admin.documents'))->with('success', 'Docoment add successfully.'); 
        }else{
            return redirect()->intended(route('admin.documents'))->with('worning', 'Please select document.'); 
        }
       
    }
    public function documentsedit($id){    
        $document = Document::find($id);
        $projectId = $document->project_id;
        $imageId = $document->document_image_id;
        $hollproject = DB::table('documents')
        ->join('projects', 'documents.project_id', '=', 'projects.id')
        ->join('image_uploads', 'image_uploads.id', '=', 'documents.document_image_id')
        ->where('documents.id', $id)
        ->select('documents.*','projects.project_name','projects.project_logo',
        'image_uploads.name', 'image_uploads.alt', 'image_uploads.title', 'image_uploads.caption','image_uploads.description','image_uploads.slug', 'image_uploads.path','image_uploads.extention' )
        ->get();
        $editdocument = $hollproject[0];
        $projects =  DB::table('projects')->get();  
        $files =  DB::table('image_uploads')->get();          
        return view('admin.document.edit', compact('editdocument','projects','files'));
    }
    public function documentsupdate(Request $request, $id) { 
        $input['document_title'] = $request->document_title;
        $input['document_image_id'] =$request->image_id;
        $input['project_id'] =  $request->project_id;  
        $updateId = Document::find($id);
        if(!empty($request->input('image_id'))){
            $updateId->update($input);
            DB::table('image_uploads')
                ->where('id', $request->upload_id)
                ->update([
                    'alt' => $request->alt, 
                    'title' => $request->title,  
                    'caption' => $request->caption, 
                    'description' => $request->description,
                    ]);
            return redirect()->intended(route('admin.documents'))->with('success', 'Document updated successfully.'); 
        }else{
                 return redirect()->intended(route('admin.documents'))->with('worning', 'Please select document.'); 
            }
    }
    public function documentsuploaddelete(Request $request) {
        $val = $request->name;
        $imageId = $request->imageId;
        $documents =  Document::where('document_image_id', $imageId)->get();
        if(!empty($documents[0]->document_image_id)){
            if(($imageId == $documents[0]->document_image_id)){
                $msg = '<div class="alert alert-success text-center">This file is already used.</div>';
                $action = "file";
                return response()->json(array('msg'=> $msg, 'action'=>$action), 200);     
            }
            }else{
                ImageUpload::where('name', $val)->delete();
                $lines = ['files/'];
                for($i = 0; $i < count($lines); $i++) {
                    $value =  $lines[$i];
                    $path = public_path($value).$val;
                    if (file_exists($path)) {
                        unlink($path);
                        }
                    }    
                return response()->json(['data'=>$val],200);
            } 
        
    } 
    public function documentsfetch(Request $request)
    {
    //  $images =\File::allFiles(public_path('upload'));
    $images = DB::table('image_uploads')->orderBy('id', 'DESC')->get();
     $output = '<div class="file-manager-content">';        
        $output .= '<div id="image_file_upload_response">';
        foreach ($images as $image){
            $output .= '<div class="col-file-manager" id="img_col_id_'. $image->id .'">';
                $output .= '<div class="file-box"  data-file-caption="'. $image->caption .'" data-file-description="'. $image->description .'" data-file-alt="'. $image->alt .'" data-file-title="'. $image->title .'" data-file-name="'. $image->name .'"  data-file-id="'. $image->id .'" data-file-path="'.asset('files/' . $image->name).'" data-file-path-editor="'.asset('files/' . $image->name).'">';
                    if($image->extention == '.pdf'){
                        $output .= '<div class="image-container">';  
                            $output .= '<img src="'.asset('img/' . 'pdf.png').'"  loading="lazy" class="img-responsive">';
                            $name = substr($image->name, 0, 15).'...';
                            $output .= '<span class="file-name">'.$name.'</span>';
                        $output .= '</div>';
                    }     
                    if($image->extention == '.docx'){
                        $output .= '<div class="image-container">';  
                            $output .= '<img src="'.asset('img/' . 'docx.png').'"  loading="lazy" class="img-responsive">';
                            $name = substr($image->name, 0, 15).'...';
                            $output .= '<span class="file-name">'.$name.'</span>';
                        $output .= '</div>';
                    }     
                    if($image->extention == '.xlsx'){
                        $output .= '<div class="image-container">';  
                            $output .= '<img src="'.asset('img/' . 'xlsx.png').'"  loading="lazy" class="img-responsive">';
                            $name = substr($image->name, 0, 15).'...';
                            $output .= '<span class="file-name">'.$name.'</span>';
                        $output .= '</div>';
                    } 
                $output .= '</div>';
            $output .= '</div>';			        
            }
           $output .= '</div>';
        $output .= '</div>';
        echo $output;
    }
    public function documentssearch(Request $request){
            $images=DB::table('image_uploads')
                ->where('name','LIKE','%'.$request->search."%")
                ->get(); 
            foreach ($images as $image):
                echo '<div class="col-file-manager" id="img_col_id_' . $image->id . '">';
                    echo '<div class="file-box" data-file-name="'. $image->name .'"  data-file-id="'. $image->id .'" data-file-path="'.asset('files/' . $image->name).'" data-file-path-editor="'.asset('files/' . $image->name).'">';
                        if($image->extention == '.pdf'){
                                echo '<div class="image-container">';
                                    echo '<img src="'.asset('img/' . 'docx.png').'" alt="" name="file" class="img-responsive">';
                                        $name = substr($image->name, 0, 20).'...';
                                    echo '<span class="file-name">'.$name.'</span>';
                                echo '</div>';
                        }
                        if($image->extention == '.docx'){
                            echo '<div class="image-container">';
                                echo '<img src="'.asset('img/' . 'docx.png').'" alt="" name="file" class="img-responsive">';
                                    $name = substr($image->name, 0, 20).'...';
                                echo '<span class="file-name">'.$name.'</span>';
                            echo '</div>';
                        }
                        if($image->extention == '.xlsx'){
                            echo '<div class="image-container">';
                                echo '<img src="'.asset('img/' . 'xlsx.png').'" alt="" name="file" class="img-responsive">';
                                    $name = substr($image->name, 0, 20).'...';
                                echo '<span class="file-name">'.$name.'</span>';
                            echo '</div>';
                        }
                echo '</div> </div>';
            endforeach;

    }
    public function documentfilessearch(Request $request){
            $rhps = DB::table('role_has_permissions')->get();
            $permissions = DB::table('permissions')->get();
            $roles = DB::table('roles')->get();
            $documents=DB::table('documents')
                ->where('document_title','LIKE','%'.$request->search."%")
                ->get(); 
                 if (!empty($documents[0]->document_title)){
                 echo ' <table class="table table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Document Name</th>
                                <th>Project Name</th>
                                <th>File</th>
                                <th>Create Date</th>
                                <th width="200px">Action</th>
                            </tr>
                        </thead><tbody>';
                    }else{
                        echo '<h2 class="text-center">No data found</h2>';
                    }
               
            
                    foreach ($documents as $document):
                               echo '<tr>';
                            echo '<td>';
                                echo $document->id;
                            echo '</td>';    
                            echo '<td>';
                                echo $document->document_title;
                            echo '</td>';  
                            echo '<td>';
                                $projects = DB::table('projects')
                                    ->where('id',  $document->project_id)
                                    ->get();
                                $projectName = $projects[0]->project_name;
                                echo  $projectName ;   
                            echo '</td>';   
                            echo '<td>';
                                 if ($document->document_image_id){
                                     $files = DB::table('image_uploads')
                                         ->where('id', $document->document_image_id)
                                         ->get();
                                     $fileName = $files[0]->name;
                                     echo '<a href="'.asset('files/' . $fileName).'" target="_blank">Download <i class="fas fa-download"></i></a>';
                                }
                                elseif($document->document_image_id == null){
                                         echo '<p>No File here</p>';
                                        }
                            echo '</td>';  
                        
                            echo '<td>';
                                echo date('d-m-Y', strtotime($document->created_at));
                            echo '</td>'; 
                               echo '<td>';
                                    foreach ($rhps as $rhp){
                                        foreach ($permissions as $permission){
                                            foreach ($roles as $role){
                                                if ($role->id == Auth::user()->role_id && $role->id == $rhp->role_id){
                                                    if ($rhp->permission_id == $permission->id){
                                                            $name = $permission->name;
                                                            if (stristr($name, 'document')){
                                                                $value = substr(strstr($name, '-'), 1);
                                                                // echo $value;
                                                                if ($value == 'edit'){
                                                                echo '<a class="btn btn-primary btn-sm"
                                                                                href="documents.edit.'.$document->id.'"><i
                                                                                    class="fas fa-edit"></i></a>';
                                                                }
                                                                elseif ($value == 'delete'){
                                                                    echo '<a class="btn btn-danger btn-sm"
                                                                                href="documents.filedestroy.'.$document->id.'"><i class="fa fa-trash"></i></a>';
                                                                    
                                                                }
                                                            }
                                                    }
                                                }
                                            }

                                        }
                                    }
                            echo '</td>';
                        
                    endforeach;
                        echo '</td>';
                        echo '</tr>';
        

    }
    public function documentsdelete(Request $request, $id) {
      Document::where('id', $id)->delete();
      return redirect()->intended(route('admin.documents'))->with('success', 'Document updated successfully.'); 
    }  
    public function documentsfiledelete(Request $request, $id) {
      Document::where('id', $id)->delete();
      return redirect()->intended(route('admin.documents'))->with('success', 'Document updated successfully.'); 
    } 

    public function projectsfeatur(Request $request){
     
        $file = $request->all();
        // $token = $request->_token;
        // $file = $request->file('image'); 
        print_r($file);
        die();
        $img = new Imagetype;
        $img->image = $file;
        $img->type = $token;
        $img->save();

        //=========================   ===============================
        // $imagename = pathinfo($fileName, PATHINFO_FILENAME);
        // $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        // $imagename = preg_replace('/\..+$/', '',  $fileName);
        // $path = $file->getRealPath();
        // $size = $file->getSize();
        // $mimtype = $file->getMimeType();
    } 
    
    //============================ Task ===============
    public function tasks(){
        $data = Task::orderBy('id', 'desc')->paginate(10);      
        return view('admin.task.index', compact('data'));
             
    }    
    public function taskscreate(){    
        $projects =  DB::table('projects')->get();  
        $tasks =  DB::table('tasks')->get();  
        $users =  DB::table('users')->get();  
        $taskId = DB::table('task_metas')->latest('id')->first();
        if($taskId == ''){
            $task = New Task();
            $task->task_title = "demo";  
            $task->project_id = $projects[0]->id;  
            $task->save();
        } 
        sleep(2);
        $tasks =  DB::table('tasks')->get();  
        if($taskId == ''){
            $taskmeta = New TaskMeta();
            $taskmeta->task_id = $tasks[0]->id;  
            $taskmeta->save();       
        }
        return view('admin.task.create', compact(['projects','users']));

    } 
    public function tasksupload(Request $request){
        $caption = $request->input('caption');
        $userName = Auth::user()->name;
     
    }    
 
    public function tasksstore(Request $request) {  
        $checkBox = $request->input('assign_by');   
        $taskId = DB::table('tasks')->latest('id')->first();
        $tid = ++$taskId->id;    
        foreach($checkBox as  $check){
            $users = DB::table('users')->where('name', $check)->get();
            foreach($users as $use){
                $stortaskmeta = new TaskMeta;
                $stortaskmeta->user_id = $use->id;
                $stortaskmeta->role_id = $use->role_id;
                $stortaskmeta->task_id = $tid;
            }
            $stortaskmeta->save();
        }
        //  $date = date('m-d-Y');
        // $value = $request->input('start_date'); // $value = 03-31-2022 // 'm-d-Y'
        // $newDate = \Carbon\Carbon::createFromFormat('m-d-Y',$request->input('start_date'))->format('Y-m-d');
        $userId = Auth::user()->id;
        $stortask = new Task;
        $stortask->task_title = $request->input('task_title');
        $stortask->task_description = $request->input('task_description');  
        // $checkBox = $request->input('assign_by');        
        /* Another way array data insert*/
        /*$dataSet = [];
        $checkBox = $request->input('assign_by');  
        foreach ($checkBox as $key => $safety) {
            $dataSet[] = [
                'assign_by'  =>  $checkBox[$key],
            ];
        }
        $assinName = array_column($dataSet, 'assign_by');
        $value = implode(',', $assinName);
        $stortask->assign_by = $value;  */

    
        $dataSet = implode(',', $checkBox);
        $stortask->assign_by = $dataSet;
        $stortask->task_progress = $request->input('task_progress');
        $stortask->task_status = $request->input('task_status');  
        $stortask->start_date = \Carbon\Carbon::createFromFormat('m-d-Y', $request->input('start_date'))->format('Y-m-d');  
        $stortask->end_date = \Carbon\Carbon::createFromFormat('m-d-Y', $request->input('end_date'))->format('Y-m-d');
        $stortask->user_id = $userId;  
        $stortask->project_id = $request->input('project_id');  
        $stortask->save();
        return redirect()->intended(route('admin.tasks'))->with('success', 'Add successfully.'); 
    }
    public function tasksedit($id){    
        $task = Task::find($id);
        $projects =  DB::table('projects')->get();  
        return view('admin.task.edit', compact('task','projects'));
    } 
     public function tasksshow($id){    
        $task = Task::find($id);
        $projects =  DB::table('projects')->get();  
        return view('admin.task.show', compact('task','projects'));
    }
    public function tasksupdate(Request $request, $id) { 
        // print_r($id);
        // $metaIds =$metaID =  DB::table('task_metas')->where('task_id',$id)->get();
        // foreach($metaIds as $mid){
        // }
        // die();
        $checkBox = $request->input('assign_by');   
        $hiddencheck = $request->input('assign_id');   
        $results=array_diff($hiddencheck,$checkBox);
        foreach($checkBox as $check){
             $users = DB::table('users')->where('name',$check)->get();
                foreach($users as $use){
                   $metaID =  DB::table('task_metas')->where('user_id',$use->id)->where('task_id',$id)->updateOrInsert([
                                'user_id'=> $use->id,
                                'role_id' => $use->role_id,
                                'task_id' => $id
                            ],
                            [
                                'user_id'=> $use->id,
                                'role_id' => $use->role_id,
                                'updated_at'=>date('Y-m-d H:i:s')       
                            ]);
                        }
                foreach($results as $result){
                    $user = DB::table('users')->where('name',$result)->get();
                    foreach($user as $usr){                   
                        TaskMeta::where('user_id', $usr->id)->where('task_id', $id)->delete();    
                                      
                    }
                } 
        }
        $userId = Auth::user()->id;
        $updateId = Task::find($id);
        $input['task_title'] = $request->task_title;
        $input['task_description'] =$request->task_description;
        $checkBox = $request->input('assign_by');  
        $dataSet = [];
        foreach ($checkBox as $key => $safety) {
            $dataSet[] = [
                'assign_by'  =>  $checkBox[$key],
            ];
        }
        $assinName = array_column($dataSet,'assign_by');
        $value = implode(',' , $assinName);
        $input['assign_by'] = $value;    
        $input['task_progress'] = $request->task_progress;
        $input['task_status'] =$request->task_status;
        $input['start_date'] = $request->start_date; //  \Carbon\Carbon::createFromFormat('m-d-Y', $request->start_date)->format('Y-m-d');  
        $input['end_date'] = $request->end_date;  // \Carbon\Carbon::createFromFormat('m-d-Y', $request->end_date)->format('Y-m-d');
        $input['user_id'] = $userId; 
        $input['project_id'] =  $request->project_id;  
        $updateId->update($input);
        return redirect()->intended(route('admin.tasks'))->with('success', 'Task updated successfully.'); 
    }
    public function taskssearch(Request $request){
               $rhps = DB::table('role_has_permissions')->get();
            $permissions = DB::table('permissions')->get();
            $roles = DB::table('roles')->get();
            $tasks=DB::table('tasks')
                ->where('task_title','LIKE','%'.$request->search."%")
                // ->paginate(2); 
                ->get();
            if (!empty($tasks[0]->task_title)){
                echo '<table class="table table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Assign Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th width="150px" class="text-center">Action</th>
                                </tr>
                            </thead>
                        <tbody>';
                    }else{
                        echo '<h2 class="text-center">No data found</h2>';
                    }
                        foreach ($tasks as $task):
                                echo '<tr>';
                                    echo '<td class="align-middle">';
                                        echo $task->id;
                                    echo '</td>';    
                                    echo '<td class="align-middle">';
                                        echo $task->task_title;
                                    echo '</td>';  
                                     echo '<td class="align-middle">';
                                        echo $task->task_description;
                                    echo '</td>';    
                                    echo '<td class="align-middle">';
                                        echo $task->assign_by;
                                    echo '</td>';  
                                    echo '<td class="align-middle">';
                                        echo date('d-m-Y', strtotime($task->start_date));
                                    echo '</td>';  
                                    echo '<td class="align-middle">';
                                        echo  date('d-m-Y', strtotime($task->end_date));
                                    echo '</td>'; 

                                        echo ' <style>
                                                :root {
                                                    --bg: #04AA6D;
                                                    --padding: 1;
                                                }
                                                #myBar.progress-line {
                                                    width: calc(var(--number)*1%);
                                                    height: 30px;
                                                    background-color: var(--bg);
                                                    text-align: center;
                                                    line-height: 30px;
                                                    color: white;
                                                }
                                            </style>';
                                            echo '<td>';
                                            echo '<div id="myProgress">
                                                <div id="myBar" class="progress-line"
                                                    style="--number:'.$task->task_progress.'">';

                                                    echo  $task->task_progress. '%';
                                                    echo '</div>
                                            </div>';
                                            echo '</td>';
                                               echo '<td class="align-middle">';
                                            if ($task->task_status == 1){
                                                echo '<p>Done</p>';
                                            }
                                            elseif($task->task_status == 2){
                                                echo '<p>Running</p>';
                                            }                                                
                                            elseif($task->task_status == 3){
                                                echo '  <p>Panding</p>';
                                            }
                                              
                                    echo '</td>'; 

                                            echo '<td class="align-middle">';
                                            foreach ($rhps as $rhp){
                                                foreach ($permissions as $permission){
                                                    foreach ($roles as $role){
                                                        if ($role->id == Auth::user()->role_id && $role->id == $rhp->role_id){
                                                            if ($rhp->permission_id == $permission->id){
                                                                    $name = $permission->name;
                                                                    if (stristr($name, 'task')){
                                                                        $value = substr(strstr($name, '-'), 1);
                                                                        // echo $value;
                                                                        if ($value == 'edit'){
                                                                        echo '<a class="btn btn-primary btn-sm"
                                                                                        href="admin.tasks.edit.'.$task->id.'"><i
                                                                                            class="fas fa-edit"></i></a>';
                                                                        }
                                                                        elseif ($value == 'show'){
                                                                            echo '   <a class="btn btn-primary btn-sm"
                                                                            href="tasks.show.'.$task->id.'"><i
                                                                                class="fas fa-eye"></i></a>';
                                                                            
                                                                        }  
                                                                        elseif ($value == 'delete'){
                                                                            echo '<a class="btn btn-danger btn-sm"
                                                                                        href="tasks.searchdestroy.'.$task->id.'"><i class="fa fa-trash"></i></a>';
                                                                            
                                                                        }
                                                                    }
                                                            }
                                                        }
                                                    }

                                                }
                                            }
                                    echo '</td>';
                                echo '</tr>';
                            endforeach;
            echo '</tbody></table>';
            // echo  '"'.$tasks->appends($_GET)->links().'"';
// 
    }
    public function tasksprofile(Request $request, $id){
        $user = DB::table('users')->where('name', $id)->get();
        return view('admin.task.profile',  compact('user'));
    }
  
    public function tasksdelete(Request $request, $id) {
        $tasks = Task::where('id',$id)->get();
        Task::where('id', $id)->delete();
        foreach($tasks as $task){
            TaskMeta::where('task_id', $task->id)->delete();
        }
   
      return redirect()->intended(route('admin.tasks'))->with('success', 'task Delete successfully.'); 
    }  
     public function searchdestroy(Request $request, $id) {
       $tasks = Task::where('id',$id)->get();
        Task::where('id', $id)->delete();
        foreach($tasks as $task){
            TaskMeta::where('task_id', $task->id)->delete();
        }
      return redirect()->intended(route('admin.tasks'))->with('success', 'task Delete successfully.'); 
    }  
 

 
}
