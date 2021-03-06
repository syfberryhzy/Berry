<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;

use Auth;
use Mail;

class UsersController extends Controller
{

    public function __contruct(){
        $this->middleware('auth',[
            'only'=>['eidt','update','destroy','following','followers']
        ]);

        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }

    public function index(){
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }
    public function create(){
        return view('users.create');
    }

    // public function show($id){
    //     $user = User::findOrFail($id);
    //     return view('users.show',compact('user'));
    // }

    public function store(Request $request){
        $this->validate($request,[
            'name'=> 'required|max:50',
            'email'=>'required|email|unique:users|max:255',
            'password'=> 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name'=> $request->name,
            'email'=>$request->email,
            'password'=> bcrypt($request->password),
        ]);
        //注册后自动登录
        // Auth::login($user);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success','欢迎，您将在这里开启一段新的旅程～');
        // return redirect()->route('users.show', [$user]);
        return redirect('/');
    }

    public function sendEmailConfirmationTo ($user) {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = '1913549290gg@qq.com';
        $name = "Berry_email";
        $to =
        $user->email;
        $subject = '感谢注册 Berry 应用！请确认你的邮箱。';

        Mail::send($view, $data , function ($message) use ($from, $name, $to, $subject){
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token) {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user ->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你，激活成功');
        return redirect()->route('users.show', [$user]);
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    public function update($id, Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'required|confirmed|min:6',
        ]);
        $user = User::findOrFail($id);
        $this->authorize('update',$user);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success','个人资料更新成功！');
        return redirect()->route('users.show', $id );
    }

    public function destroy($id){
        $user = User::findOrFail($id);
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','成功删除用户！');
        return back();
    }

    public function show($id){
        $user = User::findOrFail($id);
        $statuses = $user->statuses()->orderby('created_at','desc')->paginate(20);

        return view('users.show',compact('user','statuses'));
    }

    public function followings($id){
        $user = User::findOrFail($id);
        $users = $user->followings()->paginate(20);
        $title = "关注的人";
        return view('users.show_follow',compact('users','title'));
    }

    public function followers($id) {
        $user = user::findOrFail($id);
        $users = $user->followers()->paginate(20);
        $title = "粉丝";
        return view('users.show_follow',compact('users','title'));
    }
}
