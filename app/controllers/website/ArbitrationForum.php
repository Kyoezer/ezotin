<?php

/**
 * Created by PhpStorm.
 * User: SWM
 * Date: 2/27/2017
 * Time: 1:37 PM
 */
class ArbitrationForum extends WebsiteController
{
    public function getIndex(){
        if(Session::has('ArbitratorLoggedIn')){
            return Redirect::to("web/forumforarbitrators");
        }
        return View::make("website.arbitrationforumlogin");
    }
    public function createUser($id = null){
        $users = array(new ArbitratorUserModel());
        if((bool)$id){
            $users = DB::table("webarbitratoruser")->where("Id",$id)->get(array('Id','username','WebArbitratorId','Status'));
        }
        $arbitrators = DB::table("webarbitrators")
                            ->whereRaw("coalesce(IsDeleted,0)=0")
                            ->orderBy('RegNo')
                            ->get(array('Id','RegNo','Email','Name'));
        $arbitratorUsers = DB::table('webarbitratoruser as T1')
                                ->leftJoin('webarbitrators as T2','T2.Id','=','T1.WebArbitratorId')
                                ->orderBy(DB::raw("case when coalesce(IsAdmin,0) = 1 then 1 else 2 end"))
                                ->select('T1.Id','T2.Name as Arbitrator','T1.IsAdmin','T1.username','T1.Status')
                                ->get();
        return View::make('sys.arbitratoruser')
                ->with('users',$users)
                ->with('arbitratorUsers',$arbitratorUsers)
                ->with("arbitrators",$arbitrators);
    }
    public function postSave(){
        $update = false;
        $inputs = Input::except("password_confirmation",'_token','IsInsert');
        if(Input::has('password')){
            $password = $inputs['password'];
            if($password != Input::get('password_confirmation')){
                return Redirect::to('sys/userforarbitrationforum')->with("customerrormessage","<strong>ERROR! </strong>Passwords do not match!");
            }
            $inputs['password'] = sha1($inputs['password']);
        }
        $userAlreadyExists = DB::table('webarbitratoruser')->where('WebArbitratorId',$inputs['WebArbitratorId'])->count();
        if($userAlreadyExists>0){
            return Redirect::to('sys/userforarbitrationforum')->with("customerrormessage","<strong>ERROR! </strong>There is already a user account for this arbitrator!");
        }
        $originalPassword = Input::get('password');
        $arbitratorName = DB::table('webarbitratoruser')->where('WebArbitratorId',$inputs['WebArbitratorId'])->pluck('FullName');
        DB::beginTransaction();
        try{
            if((bool)$inputs['Id']){
                $update = true;
                $inputs['EditedBy'] = Auth::user()->Id;
                $inputs['EditedOn'] = date('Y-m-d G:i:s');
                DB::table('webarbitratoruser')->where('Id',$inputs['Id'])->update($inputs);
            }else{
                $inputs['Id'] = $this->UUID();
                $inputs['CreatedBy'] = Auth::user()->Id;
                $inputs['CreatedOn'] = date('Y-m-d G:i:s');
                DB::table('webarbitratoruser')->insert($inputs);
            }
        }catch(Exception $e){
            DB::rollBack();
            return Redirect::to('sys/userforarbitrationforum')->with("customerrormessage","<strong>ERROR! </strong>".$e->getMessage());
        }
        if(!$update){
            $arbitratorDetails = DB::table('webarbitrators')->where('Id',$inputs['WebArbitratorId'])->get(array('Email','Name','ContactNo'));
            $email = $inputs['username'];
            $contactNo = $arbitratorDetails[0]->ContactNo;
            $name = $arbitratorDetails[0]->Name;
            $data['mailMessage'] = "Your user account for the Arbitration Forum has been created. You can login using the following credentials: <br/>Username: ".$inputs['username']."<br/>Password: ".Input::get('password');
            $this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$data,'User account for Arbitration Forum',$email,$name);
        }

        DB::commit();
        return Redirect::to('sys/userforarbitrationforum')->with("savedsuccessmessage","<strong>SUCCESS! </strong>User has been ".($update?'updated!':'saved!'));
    }
    public function postAuth(){
        Auth::logout();
        Session::flush();
        $username = Input::get('username');
        $password = Input::get('password');
        if(!(bool)$username || !(bool)$password){
            return Redirect::to("web/arbitrationforum")->with('InvalidCredentials','<strong>ERROR! </strong>Please enter your username and password');
        }else{
            $password = sha1($password);
            $userCount = DB::table('webarbitratoruser')->where('username',$username)->where('password',$password)->whereRaw("coalesce(Status,0) = 1")->count();
            $userDetails = DB::table('webarbitrators as T1')
                                ->rightJoin('webarbitratoruser as T2','T2.WebArbitratorId','=','T1.Id')
                                ->where('T2.username',$username)
                                ->where('T2.password',$password)
                                ->take(1)
                                ->get(array('T1.Name','T2.FullName','T2.Id as ArbitratorUserId','T2.WebArbitratorId','T2.IsAdmin'));
            if($userCount){
                $name = $userDetails[0]->Name;
                $userId = $userDetails[0]->ArbitratorUserId;
                $arbitratorId = $userDetails[0]->WebArbitratorId;
                $isAdmin = $userDetails[0]->IsAdmin;
                if((bool)$isAdmin == 1){
                    $name = $userDetails[0]->FullName;
                }

                Session::put('ArbitratorLoggedIn',1);
                Session::put('ArbitratorUserName',$name);
                Session::put('ArbitratorId',$arbitratorId);
                Session::put('ArbitratorUserId',$userId);
                Session::put('IsAdmin',$isAdmin);

                return Redirect::to("web/forumforarbitrators")->with('savedsuccessmessage','You are logged in to the Arbitration Forum!');
            }else{
                $userCount = DB::table('webarbitratoruser')->where('username',$username)->where('password',$password)->whereRaw("coalesce(Status,0) = 0")->count();
                if($userCount > 0){
                    return Redirect::to("web/arbitrationforum")->with('InvalidCredentials','<strong>ERROR! </strong>Your account has been deactivated!');
                }
                return Redirect::to("web/arbitrationforum")->with('InvalidCredentials','<strong>ERROR! </strong>Wrong username and/or password!');
            }
        }
    }
    public function getLogout(){
        Session::forget('ArbitratorLoggedIn');
        Session::forget('ArbitratorUserName');
        Session::forget('ArbitratorId');
        Session::forget('ArbitratorUserId');
        $newDateTime = date('h:i A');
        return Redirect::to('/')->with('savedsuccessmessage', 'You logged out at '.$newDateTime);
    }
    public function getForum(){
        $lastTopic = array();
        $topicCount = array();
        $categories = DB::table('webarbitrationforumcategories')
                        ->orderBy('CategoryName')
                        ->get(array('Id','CategoryName','CategoryDescription'));
        foreach($categories as $category){
            $lastTopic[$category->Id] = DB::table('webarbitrationforumtopics')
                                            ->where('CategoryId',$category->Id)
                                            ->orderBy('TopicDate','desc')
                                            ->take(1)
                                            ->get(array('Subject','Id'));
            $topicCount[$category->Id] = DB::table("webarbitrationforumtopics")
                                            ->where('CategoryId',$category->Id)
                                            ->count();
        }
        return View::make('website.arbitratorforumindex')
                ->with('topicCount',$topicCount)
                ->with('categories',$categories)
                ->with('lastTopic',$lastTopic);
    }
    public function getNewCategory(){
        return View::make('website.arbitratorforumcategory');
    }
    public function saveCategory(){
        $inputs = Input::except('_token');
        $inputs['Id'] = $this->UUID();

        DB::table("webarbitrationforumcategories")->insert($inputs);
        return Redirect::to('web/forumforarbitrators')->with('successmessage','Category has been saved!');
    }
    public function getNewTopic(){
        $categories = DB::table('webarbitrationforumcategories')
            ->orderBy('CategoryName')
            ->get(array('Id','CategoryName'));
        return View::make('website.arbitratorforumtopic')
                ->with('categories',$categories);
    }
    public function saveTopic(){
        $inputs = Input::except('_token','PostContent','password','password_confirmation');
        $inputs['Id'] = $this->UUID();
        $inputs['CreatedBy'] = Session::get("ArbitratorUserId");
        $inputs['TopicDate'] = date('Y-m-d G:i:s');
        DB::table("webarbitrationforumtopics")->insert($inputs);
        $postInputs['Content'] = Input::get('PostContent');
        $postInputs['Id'] = $this->UUID();
        $postInputs['TopicId'] = $inputs['Id'];
        $postInputs["PostDate"] = date('Y-m-d G:i:s');
        $postInputs['PostBy'] = Session::get('ArbitratorUserId');
        DB::table("webarbitrationforumposts")->insert($postInputs);
        return Redirect::to('web/forumforarbitrators')->with('successmessage','Topic has been saved!');
    }
    public function viewCategory($id){
        $topics = DB::table('webarbitrationforumtopics')
                        ->where('CategoryId',$id)
                        ->orderBy('TopicDate','ASC')
                        ->get(array('Subject','Id','TopicDate'));
        return View::make('website.arbitratorforumviewcategory')
                    ->with('topics',$topics)
                    ->with('categoryName',DB::table('webarbitrationforumcategories')->where('Id',$id)->pluck('CategoryName'));
    }
    public function viewTopic($id){
        $posts = DB::table('webarbitrationforumposts as T1')
                    ->leftJoin('webarbitratoruser as T2',"T1.PostBy",'=','T2.Id')
                    ->leftJoin('webarbitrators as T3','T3.Id','=','T2.WebArbitratorId')
                    ->where('T1.TopicId',$id)
                    ->orderBy('T1.PostDate')
                    ->get(array('T1.Id','T1.Content','T1.PostDate',DB::raw('coalesce(T3.Name,T2.FullName) as User')));
        return View::make('website.arbitratorforumviewtopic')
                    ->with('posts',$posts)
                    ->with('topicName',DB::table('webarbitrationforumtopics')->where('Id',$id)->pluck('Subject'))
                    ->with('topicId',$id);
    }
    public function savePost(){
        $inputs = Input::except('_token');
        $inputs['Id'] = $this->UUID();
        $inputs["PostDate"] = date('Y-m-d G:i:s');
        $inputs['PostBy'] = Session::get('ArbitratorUserId');
        DB::table("webarbitrationforumposts")->insert($inputs);
        return Redirect::to("web/arbforumtopicview/".$inputs['TopicId'])->with('successmessage','Your reply has been submitted');
    }
    public function getAdmin(){
        $currentAdmin = DB::table('webarbitratoruser')->where(DB::raw('coalesce(IsAdmin,0)'),'=',1)->get(array('username','FullName'));
        return View::make("sys.arbitratoradmin")->with('currentAdmin',$currentAdmin);
    }
    public function postSaveAdmin(){
        $update = false;
        $inputs = Input::except("password_confirmation");
        $password = $inputs['password'];
        if($password != Input::get('password_confirmation')){
            return Redirect::to('sys/userforarbitrationforum')->with("customerrormessage","<strong>ERROR! </strong>Passwords do not match!");
        }
        DB::beginTransaction();
        try{
            $inputs['password'] = sha1($inputs['password']);
            $inputs['IsAdmin'] = 1;
            DB::table('webarbitratoruser')->where(DB::raw('coalesce(IsAdmin,0)'),1)->update(array('IsAdmin'=>0,'Status'=>0));
            if((bool)$inputs['Id']){
                $update = true;
                $object = ArbitratorUserModel::find($inputs['Id']);
                $object->fill($inputs);
                $object->update();
            }else{
                $inputs['Id'] = $this->UUID();
                ArbitratorUserModel::create($inputs);
            }
        }catch(Exception $e){
            DB::rollBack();
            return Redirect::to('sys/arbitrationforumadmin')->with("customerrormessage","<strong>ERROR! </strong>".$e->getMessage());
        }
        DB::commit();
        return Redirect::to('sys/arbitrationforumadmin')->with("savedsuccessmessage","<strong>SUCCESS! </strong>User has been ".($update?'updated!':'saved!'));
    }
    public function checkUserNameAvailability(){
        $flagEmail = true;
        $email=Input::get('username');
        $count = DB::table('webarbitratoruser')->where(DB::raw("TRIM(username)"),trim($email))->count();
        if($count > 0){
            $flagEmail = false;
        }
        return json_encode(array(
            'valid' => $flagEmail,
        ));
    }
    public function getForumAdmin(){
        return View::make('sys.forumadmin');
    }
    public function getForumCategoriesAdmin(){
        $categories = DB::table('webarbitrationforumcategories')
                            ->orderBy('CategoryName')
                            ->get(array('Id','CategoryName','CategoryDescription'));
        return View::make('sys.forumcategories')
                    ->with('categories',$categories);
    }
    public function editForumCategory(){
        $id = Input::get('pk');
        $column = Input::get('name');
        $value = Input::get('value');

        DB::table('webarbitrationforumcategories')->where('Id',$id)->update(array($column=>$value));
        return 1;
    }
    public function editForumTopic(){
        $id = Input::get('pk');
        $column = Input::get('name');
        $value = Input::get('value');

        DB::table('webarbitrationforumtopics')->where('Id',$id)->update(array($column=>$value));
        return 1;
    }
    public function deleteForumElement(){
        $id = Input::get('id');
        $type = Input::get('type');
        $redirect = '';
        switch((int)$type):
            case 1:
                $table = "webarbitrationforumcategories";
                $redirect = "sys/arbitrationforumcategories";
                break;
            case 2:
                $table = "webarbitrationforumtopics";
                $redirect = "sys/arbitrationforumtopics";
                break;
            case 3:
                $table = "webarbitrationforumposts";
                $redirect = "sys/arbitrationforumposts";
                break;
            default:
                break;
        endswitch;
        DB::table($table)->where('Id',$id)->delete();
        return Redirect::to($redirect)->with('savedsuccessmessage',"Record has been deleted!");
    }
    public function getForumTopicsAdmin(){
        $categoryId = Input::get('CategoryId');
        if((bool)$categoryId){
            $topics = DB::table('webarbitrationforumtopics as T1')
                ->join('webarbitrationforumcategories as T2','T2.Id','=','T1.CategoryId')
                ->where('T1.CategoryId',$categoryId)
                ->orderBy('T2.CategoryName','ASC')
                ->orderBy('T1.TopicDate','ASC')
                ->get(array('T2.CategoryName','T1.CategoryId','T1.Subject','T1.Id','T1.TopicDate'));
        }else{
            $topics = DB::table('webarbitrationforumtopics as T1')
                ->join('webarbitrationforumcategories as T2','T2.Id','=','T1.CategoryId')
                ->orderBy('T2.CategoryName','ASC')
                ->orderBy('T1.TopicDate','ASC')
                ->get(array('T2.CategoryName','T1.CategoryId','T1.Subject','T1.Id','T1.TopicDate'));
        }

        $categories = DB::table('webarbitrationforumcategories')
            ->orderBy('CategoryName')
            ->get(array('Id as value','CategoryName as text'));

        return View::make('sys.forumtopics')
            ->with('topics',$topics)
            ->with('categories',$categories);
    }
    public function getForumPostsAdmin(){
        $topicId = Input::get('TopicId');
        if((bool)$topicId){
            $posts = DB::table('webarbitrationforumposts as T1')
                ->leftJoin('webarbitratoruser as T2',"T1.PostBy",'=','T2.Id')
                ->leftJoin('webarbitrators as T3','T3.Id','=','T2.WebArbitratorId')
                ->where('T1.TopicId',$topicId)
                ->orderBy('T1.PostDate')
                ->get(array('T1.Id','T1.Content','T1.PostDate',DB::raw("coalesce(T3.Name, T2.FullName) as User")));
        }else{
            $posts = array();
        }

        $topics = DB::select("select T1.Id, T2.CategoryName as Category, T1.Subject as Topic from webarbitrationforumtopics T1 join webarbitrationforumcategories T2 on T2.Id = T1.CategoryId order by T2.CategoryName,T1.Subject");

        return View::make('sys.forumposts')
            ->with('topics',$topics)
            ->with('posts',$posts);
    }
    public function resetArbPassword(){
        $id = Input::get('Id');
        $password = Input::get('password');
        DB::table('webarbitratoruser')->where("Id",$id)->update(array('password'=>sha1($password)));
        return Redirect::to("sys/userforarbitrationforum")->with('savedsuccessmessage','Password has been reset successfully');
    }
    public function changeArbPassword(){
        $id = Session::get('ArbitratorUserId');
        $password = Input::get('password');
        try{
            DB::table('webarbitratoruser')->where("Id",$id)->update(array('password'=>sha1($password)));
        }catch(Exception $e){
            return Redirect::to("web/forumforarbitrators")->with('customerrormessage',$e->getMessage());
        }

        return Redirect::to("web/forumforarbitrators")->with('savedsuccessmessage','Password has been changed successfully');
    }
    public function fetchForumCategories(){
        $categories = DB::table('webarbitrationforumcategories')
            ->orderBy('CategoryName')
            ->get(array('Id as value','CategoryName as text'));
        return Response::json($categories);
    }
}