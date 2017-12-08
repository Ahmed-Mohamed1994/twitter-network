<?php

namespace App\Http\Controllers;

use App\Follow;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function getHome(){
        if(Auth::user()){
            return redirect()->route('dashboard');
        }else{
            return view('welcome');
        }
    }
    public function postSignUp(Request $request){
        $this->validate($request, [
            'username' => 'required|max:150|alpha_dash|unique:users',
            'name' => 'required|max:150',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4'
        ],[
            'username.alpha_dash' => 'The username may only contain letters, numbers, underscore, and dashes.',
        ]);
        $username = $request['username'];
        $email = $request['email'];
        $name = $request['name'];
        $password = bcrypt($request['password']);

        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->name = $name;
        $user->password = $password;
        $user->save();
        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function postSignIn(Request $request){
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            return redirect()->route('dashboard');
        }
        return redirect()->back();
    }

    public function getLogout(){
        Auth::logout();
        return redirect()->route('login');
    }

    public function getAccount(){
        return view('account', ['user' => Auth::user()]);
    }

    public function postSaveAccount(Request $request){
        $user = Auth::user();
        $this->validate($request, [
            'username' => 'required|max:150|alpha_dash|unique:users,username,' . $user->id,
            'name' => 'required|max:150',
            'email' => 'required|email|unique:users,email,' . $user->id
        ],[
            'username.alpha_dash' => 'The username may only contain letters, numbers, underscore, and dashes.',
        ]);
        $file_exists = Storage::disk('local')->exists($user->username . '-' . $user->id . '.jpg');
        if ($user->username != $request['username'] && $file_exists) {
            Storage::copy($user->username . '-' . $user->id . '.jpg', $request['username'] . '-' . $user->id . '.jpg');
            Storage::delete($user->username . '-' . $user->id . '.jpg');
        }
        $password = $user->password;
        if ($request['old_password'] != "" || $request['new_password'] != "") {
            if (Hash::check($request['old_password'], $user->password)) {
                $password = bcrypt($request['new_password']);
            } else {
                return redirect()->route('account')->with(['message_err' => 'Invalid Old Password']);
            }
        }
        if ($request['old_password'] == "" && $request['new_password'] != "") {
            return redirect()->route('account')->with(['message_err' => 'Old Password Required To Update']);
        } elseif ($request['old_password'] != "" && $request['new_password'] == "") {
            return redirect()->route('account')->with(['message_err' => 'New Password Required To Update']);
        }
        $user->username = $request['username'];
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = $password;
        $user->update();
        $file = $request->file('image');
        $filename = $request['username'] . '-' . $user->id . '.jpg';
        if ($file) {
            Storage::disk('local')->put($filename, File::get($file));
        }
        return redirect()->route('account')->with(['message' => 'Successfully updated!']);;
    }

    public function getUserImage($filename){
        $file = Storage::disk('local')->get($filename);
        return new Response($file, 200);
    }

    public function getDashboard(){
        return view('dashboard');
    }

    public function getNewsFeed(){
        return view('news_feed');
    }

    public function getActivityFeed(){
        return view('activity_feed');
    }

    public function postSearchUsername(Request $request) {
        $this->validate($request, [
            'username' => 'required|max:150|alpha_dash'
        ]);
        $username = $request['username'];
        $search_user = User::where('username', $username)->first();
        if($search_user){
            return view('account_user_search' , ['user_search' => $search_user]);
        }else{
            return redirect()->route('dashboard')->with(['message_err' => 'Username Not Found!']);
        }
    }

    public function getUserAccount(User $userId){
        $followed_this_user = Follow::where(['userId' => Auth::user()->id , 'Follow_userId' => $userId->id])->first();
        return view('user_account' , ['user_account' => $userId, 'followed_this_user' => $followed_this_user]);
    }

    public function getUserFollow($userId){
        $user = Auth::user();
        if($user->id == $userId) {
            return redirect()->back()->with(['message_err' => 'Oh You can\'t follow yourself']);
        }
        $follow = new Follow();
        $follow->userId = $user->id;
        $follow->Follow_userId = $userId;
        if($follow->save()){
            return redirect()->route('user.account',['userId' => $userId])->with(['message' => 'Your are following this user']);
        }
    }

    public function getUserUnFollow($userId){
        $followed_this_user = Follow::where(['userId' => Auth::user()->id , 'Follow_userId' => $userId])->first();
        $followed_this_user->delete();
        return redirect()->route('user.account',['userId' => $userId])->with(['message' => 'Your are un follow this user']);
    }
}
