<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Executive;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Role_has_permission;
use App\Models\Project;
use App\Models\Document;
use App\Models\ImageUpload;
use DB;
use Image;
use Session;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ProjetController;

class ExecutiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //   dd("ok");
        return view('executive.index');
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
     * @param  \App\Models\Executive  $executive
     * @return \Illuminate\Http\Response
     */
    public function show(Executive $executive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Executive  $executive
     * @return \Illuminate\Http\Response
     */
    public function edit(Executive $executive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Executive  $executive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Executive $executive)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Executive  $executive
     * @return \Illuminate\Http\Response
     */
    public function destroy(Executive $executive)
    {
        //
    }
 // ================== User=============
    public function user(){       
        $user  = new UserController;      
        $data = $user->index();
        return view('executive.users.index', compact('data'));
    } 
     public function usercreate(){
        return view('executive.users.create');
    }
    public function userstore(Request $request){   
        $user  = new UserController;  
        $user->store($request);
        return redirect()->route('executive.users')
            ->with('success', 'User created successfully.');
    }
    public function usershow($id){
        $user  = new UserController;  
        $urs = $user->show($id);
        $users = $urs[0];
        return view('executive.users.show', compact('users'));
    }
    public function useredit($id){
    
        $users  = new UserController;  
        $urs = $users->edit($id);
        $user = $urs[0];
        return view('executive.users.edit', compact('user'));
    } 
    public function userpublish($id){
        $publish =  User::find($id);
        $publish->status_id = 0;
        $publish->save();
        return redirect('executive/users');
    } 
    public function userunpublish($id){
        $publish =  User::find($id);
        $publish->status_id = 1;
        $publish->save();
        return redirect('executive/users');
    }
    public function userupdate(Request $request, $id) {   
        $user  = new UserController;  
        $user->update($request, $id);
        return redirect()->route('executive.users')
            ->with('success', 'User updated successfully.');
    }
    public function userdestroy($id)
    {
        $user  = new UserController;  
        $urs = $user->destroy($id);
        return redirect()->route('executive.users')
            ->with('success', 'User deleted successfully.');
    }
    // ================== Role=============
    public function role(){       
        $role  = new RoleController;      
        $data = $role->index();
        return view('executive.roles.index', compact('data'));
    } 
     public function rolecreate(){
        $role  = new RoleController;  
        $roles = $role->create();
        $permission = $roles[0];
        $users = $roles[1];
        return view('executive.roles.create', compact(['permission','users']));
    }

    public function rolestore(Request $request){   
        $role  = new RoleController;  
        $role->store($request);
        return redirect()->route('executive.roles')
            ->with('success', 'Role created successfully.');
    }
    public function roleshow($id){
        $role  = new RoleController;  
        $roles = $role->show($id);
        $role = $roles[0];
        $rolePermissions = $roles[1];
        return view('executive.roles.show', compact('role', 'rolePermissions'));
    }
    public function roleedit($id){
        $role  = new RoleController;  
        $roles = $role->edit($id);
        $role = $roles[0];
        $permission = $roles[1];
        $rolePermissions = $roles[2];
        return view('executive.roles.edit', compact('role', 'permission', 'rolePermissions'));
    }
    public function roleupdate(Request $request, $id) {   
        $role  = new RoleController;  
        $role->update($request, $id);
        return redirect()->route('executive.roles')
            ->with('success', 'Role updated successfully.');
    } 
    // ================== permission=============
    public function permission(){       
        $permission  = new PermissionController;      
        $data = $permission->index();
        return view('executive.permissions.index', compact('data'));
    } 
     public function permissioncreate(){
        return view('executive.permissions.create');
    }

    public function permissionstore(Request $request){   
        $permissions  = new PermissionController;  
        $permissions->store($request);
        return redirect()->route('executive.permissions')
            ->with('success', 'Permission created successfully.');
    }
    public function permissionshow($id){
        $permission  = new PermissionController;  
        $prms = $permission->show($id);
        $permissions = $prms[0];
        return view('executive.permissions.show', compact('permissions'));
    }
    public function permissionedit($id){
        $permission  = new PermissionController;  
        $permission = $permission->edit($id);
        $permissions = $permission[0];
        return view('executive.permissions.edit', compact('permissions'));
    }
    public function permissionupdate(Request $request, $id) {   
        $role  = new PermissionController;  
        $role->update($request, $id);
        return redirect()->route('executive.permissions')
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
        return redirect()->route('executive.permissions')
            ->with('success', 'Pemission deleted successfully.');
    }  
    public function deletepermission($id){
        $permission  = new PermissionController;  
        $permission->destroy($id);
        return redirect()->route('executive.permissions')
            ->with('success', 'Pemission deleted successfully.');
    }
     //============================ Media ===============
    public function media(){
        $data = ImageUpload::orderBy('id', 'desc')->paginate(16);   
        return view('executive.media.index', compact('data'));
        // return view('executive.media.index');        
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
        return view('executive.project.index', compact('data'));
        // return view('executive.project.index');        
    }    
    public function projectscreate(){
        $images =\File::allFiles(public_path('upload'));     
        $checks =  DB::table('image_uploads')->get();  
        return view('executive.project.create', compact(['images','checks']));
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
          
        }
    }    
 
    public function projectsstore(Request $request) {      
        $storproject = new Project;
        $storproject->project_name = $request->input('project_name');
        $storproject->project_logo = $request->input('image_name');  
        $storproject->image_id = $request->input('image_id');  
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
        return redirect()->intended(route('executive.projects'))->with('success', 'Project add successfully.'); 
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
        return view('executive.project.edit', compact('editproject'));
            
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
        return redirect()->intended(route('executive.projects'))->with('success', 'Project updated successfully.'); 
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
      return redirect()->intended(route('executive.projects'))->with('success', 'Project updated successfully.'); 
    }      
    public function projectsimagedelete(Request $request, $id) {
      Project::where('id', $id)->delete();
      return redirect()->intended(route('executive.projects'))->with('success', 'Project updated successfully.'); 
    }  
     //============================ Documents ===============
    public function documents(){
        $data = Document::orderBy('id', 'desc')->paginate(5);      
        return view('executive.document.index', compact('data'));
             
    }    
    public function documentscreate(){
    
        $projects =  DB::table('projects')->get();  
        return view('executive.document.create', compact(['projects']));
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
            return redirect()->intended(route('executive.documents'))->with('success', 'Docoment add successfully.'); 
        }else{
            return redirect()->intended(route('executive.documents'))->with('worning', 'Please select document.'); 
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
        return view('executive.document.edit', compact('editdocument','projects','files'));
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
            return redirect()->intended(route('executive.documents'))->with('success', 'Document updated successfully.'); 
        }else{
                 return redirect()->intended(route('executive.documents'))->with('worning', 'Please select document.'); 
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
      return redirect()->intended(route('executive.documents'))->with('success', 'Document updated successfully.'); 
    }  
    public function documentsfiledelete(Request $request, $id) {
      Document::where('id', $id)->delete();
      return redirect()->intended(route('executive.documents'))->with('success', 'Document updated successfully.'); 
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
}
