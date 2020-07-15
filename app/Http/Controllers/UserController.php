<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Favorite;
use Validator;
use App\BackAccount;
use App\Recommendation;
use View;
use Redirect;

class UserController extends Controller
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
            $user = User::find($request->id);

            //return redirect()->route('/update-profile/{$id}')->with('user', $user);
            return View::make('update-profilepage')->with('user', $user);
    }      

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'email'      => 'required|email',
            'phone'      => 'required'
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return redirect()->route('update-profile')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $user = User::find('id');
            $user->phone       = $request->input('phone');
            $user->email      = $request->input('email');
            $user->address    = $request->input('address');
            $user->save();

            // redirect
            return back()->with('success','Profile Updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    // user login auth
    public function login(Request $request)
    {
      $credentials = $request->only('email', 'password');
      $rules = array(
            'email' => 'required|exists:users',
            'password' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
           {
             return Redirect::back()->withInput()->withErrors($validator);
           } else {
               if(Auth::attempt($credentials) && Auth::user()->email_verified_at !== NULL){
                    return redirect('investor-dashboard')->with('status', 'Login Successful!');
            }
        else {
            if(Auth::attempt($credentials) && Auth::user()->email_verified_at == NULL){
                return Redirect::back()
                ->withErrors([
                    'credentials' => 'Email is not verified yet, please check your mail or spam folder!'
                ]); 
                }
              return Redirect::back()
                ->withErrors([
                    'credentials' => 'We were unable to sign you in.'
                ]);
            }
         }
    }
    
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }
        return redirect(url('login'));
        
    }
}
