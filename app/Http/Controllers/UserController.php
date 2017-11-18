<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller{
    public function getHome(){
        if(Auth::user()){
            return redirect()->route('dashboard');
        }else{
            return view('welcome');
        }
    }
    public function postSignUp(Request $request){
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'first_name' => 'required|max:120',
            'password' => 'required|min:4'
        ]);
        $email = $request['email'];
        $first_name = $request['first_name'];
        $password = bcrypt($request['password']);

        $user = new User();
        $user->email = $email;
        $user->first_name = $first_name;
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
        return redirect()->route('home');
    }

    public function getAccount(){
        return view('account', ['user' => Auth::user()]);
    }

    public function postSaveAccount(Request $request){
        $user = Auth::user();
        if ($user->first_name != $request['first_name']) {
            Storage::copy($user->first_name . '-' . $user->id . '.jpg', $request['first_name'] . '-' . $user->id . '.jpg');
            Storage::delete($user->first_name . '-' . $user->id . '.jpg');
        }
        $this->validate($request, [
            'email' => 'required|email|unique:users,id,' . $user->id,
            'first_name' => 'required|max:120',
            'old_password' => 'min:4',
            'new_password' => 'min:4'
        ]);
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
        $user->email = $request['email'];
        $user->first_name = $request['first_name'];
        $user->password = $password;
        $user->update();
        $file = $request->file('image');
        $filename = $request['first_name'] . '-' . $user->id . '.jpg';
        if ($file) {
            Storage::disk('local')->put($filename, File::get($file));
        }
        return redirect()->route('account')->with(['message' => 'Successfully updated!']);;
    }

    public function getUserImage($filename){
        $file = Storage::disk('local')->get($filename);
        return new Response($file, 200);
    }
}