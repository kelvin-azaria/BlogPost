<?php

namespace App\Http\Controllers;

use App\Contracts\CounterContract;
use App\Facades\CounterFacades;
use App\Http\Requests\UpdateUser;
use App\Image;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $counter;

    public function __construct(CounterContract $counter)
    {
        $this->middleware('auth');
        $this->authorizeResource(User::class, 'user');

        $this->counter = $counter;
    }

    public function index()
    {
        //
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show',[
            'user' => $user,
            'counter' => CounterFacades::Increment("user-($user->id)")
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit',['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars','public');

            if ($user->image) {
                $user->image->path = $path;
                $user->image->save();
            } else {
                // $image = new Image();
                // $image->path = $path;
                $user->Image()->save(
                    Image::make(['path' => $path])
                );
            }
        }

        $user->locale = $request->get('locale');
        $user->save();

        return redirect()
            ->back()
            ->withStatus('Profile was updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
