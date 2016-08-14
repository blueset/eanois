<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index() {
//        $users = User::all()->with(["meta"]);
        $users = User::all();
        return view("users.index", ["users" => $users]);
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        $meta = $user->getMeta();
        return view("users.edit", ["user" => $user, "meta" => $meta]);
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'name' => 'required|unique:users,name,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => "required|password_hash:users,password,".$id,
            'new_password' => "confirmed"
        ]);

        $user = \App\User::where("id", $id)->firstOrFail();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->setMeta($request->meta);
        if (!empty($request->new_password)){
            $user->password = \Hash::make($request->new_password);
        }
        $user->save();

        $request->session()->flash("message_success", "User edited.");
        return redirect()->action('Admin\UserController@edit', ['id' => $user->id]);
    }

    public function create() {
        return view("users.new");
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|unique:users,name,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => "required|confirmed"
        ]);

        $user = new \App\User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->setMeta($request->meta);
        $user->password = \Hash::make($request->password);
        $user->save();

        $request->session()->flash("message_success", "User created.");
        return redirect()->action('Admin\UserController@edit', ['id' => $user->id]);
    }
}
