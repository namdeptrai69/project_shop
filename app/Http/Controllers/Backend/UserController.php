<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $users = User::orderBy('updated_at','desc')->paginate(5);
        return view('backend.users.index')->with([
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $user = new User();

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->status = 1;
        $user->postion = $request->get('postion');
        $user->phone = $request->get('phone');
        $user->address = $request->get('address');
        $user->password = bcrypt($request->get('password'));

        $file = $request->file('avatar');
        if($file){
            $path = Storage::disk('public')->putFileAs('avatars', $file,$file->getClientOriginalName());
            $user->avatar = $file->getClientOriginalName();
        }

        $user->save();

        return redirect()->route('backend.user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::get()->find($id);
        return view('backend.users.edit')->with([
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::get()->find($id);

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->status = $request->get('status');
        $user->postion = $request->get('postion');
        $user->phone = $request->get('phone');
        $user->address = $request->get('address');
        $user->password = bcrypt($request->get('password'));

        $file = $request->file('avatar');
        if($file){
            $path = Storage::disk('public')->putFileAs('avatars', $file,$file->getClientOriginalName());
            $user->avatar = $file->getClientOriginalName();
        }
        $user->avatar = $user->avatar;

        $user->save();

        return redirect()->route('backend.user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::get()->find($id);
        $user->delete();
        Storage::disk('public')->delete('avatars/'.$user->avatar = $user->avatar);

        return redirect()->route('backend.user.index');
    }
}
