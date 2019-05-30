<?php

namespace App\Http\Controllers;

use App\User,
    App\Organisation,
    App\Role,
    App\UserProfile;
use App\Notifications\HelloUser,
    App\Notifications\VerifyEmail,
    App\Notifications\ResetPassword,
    App\Notifications\NewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Session;


class UserController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * @created by Jayalakshmi Ramasamy @on 04/01/2019
     * @modified
     */
    public function index() {
        //
    }
    
    /**
     * Show the form for login.
     *
     * @return \Illuminate\Http\Response
     * 
     * @created by Jayalakshmi Ramasamy @on 04/01/2019
     * @modified by Jayalakshmi Ramasamy on 27/01/2019
     */
    public function login(Request $request) {
        if ($user = Auth::user()) {
            return redirect('/choose_option')->with('info', 'You\'re already logged in');
        }
        if ($request->isMethod('post')) {
            if (Auth::attempt(array(
                        'email' => $request->get('email'),
                        'password' => $request->get('password')
                    ))) {
                $user = Auth::user();
                // Check whether the email varified
                if (is_null($user->email_verified_at)) {
                    Auth::logout();
                    $data['heading'] = "We've sent you a verification email.";
                    $data['content'] = "A verification email should be received within several minutes. Please click on the link in the email to verify your email address.";
                    $data['resend_link'] = $user->id;
                    return view('auth.verifyEmail')->with('data', $data);
                }
                session([
                    'email' => $request->get('email')
                ]);
                return redirect('/choose_option');
            } else {
                return back()->with('danger', "Invalid Credentials , Please try again.");
            }
        }
        return view('auth.login');
    }

    /**
     * Show the form to consent Prognica's T&C
     *
     * @return \Illuminate\Http\Response
     */
    public function consent(Request $request) {
        if ($user = Auth::user()) {
            return redirect('/choose_option')->with('info', 'You\'re already logged in');
        }
        if ($request->isMethod('post')) {
            $formData = $request->all();
            // Check that the required field is set
            if (isset($formData['consent'])) {
                session(['consent' => 1]);
                return redirect('/register');
            } else {
                $request->session()->flash('danger', 'Read and sign the terms and conditions');
                return redirect('/consent')->with('danger', 'Read and sign the terms and conditions');
            }
        }
        return view('auth.registertc');
    }

    /**
     * Show the form to get user details
     * 
     * @Required data: consent
     * @Optional data: none
     * 
     * @return \Illuminate\Http\Response
     * 
     * @created by Jayalakshmi Ramasamy @on 04/01/2019
     * @modified
     */
    public function register(Request $request) {
        if ($user = Auth::user()) {
            return redirect('/choose_option')->with('info', 'You\'re already logged in');
        }
        if (!session('consent')) {
            return redirect('/consent')->with('danger', 'Please read and accept the Terms & Conditions');
        }

        // Fetch default Organisation values
        $organisation = Organisation::pluck('organisation_name', 'id');
        return view('auth.register')
                        ->with('organisation', $organisation);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        // Server side validation
        $validator = Validator::make($request->all(), [
                    'email' => 'required|unique:users',
                    'password' => 'required',
                    'confirm_password' => 'required|same:password|min:8',
                    'mobile' => 'required|unique:user_profiles',
                    'name' => 'required',
                    'organisation_id' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('/register')
                            ->withErrors($validator)
                            ->withInput();
        }
        $userDetails = $request->all();
        $role_admin = Role::where('role_name', 'admin')->first();
        // Save user credentials
        $user = new User();
        $user->email = $request->get('email');
        $user->role_id = '1';
        $user->password = Hash::make($request->get('password'));
        $user->remember_token = $request->get('_token');
        $user->save();
        $user->roles()->attach($role_admin);

        // Save User's profile
        $profile = new UserProfile();
        $profile->name = $request->get('name');
        $profile->mobile = $request->get('mobile');
        $profile->organisation_id = $request->get('organisation_id');
        $profile->consent_flag = session('consent');
        $user->profile()->save($profile);

        // Send a welcome mail to user
        $user->notify(new HelloUser($user));

        // Sent new user notification mail to Admin
        \Notification::route('mail', 'labs@prognica.com')->notify(new NewUser($user));

        $data['heading'] = "We've sent you a verification email.";
        $data['content'] = "A verification email should be received within several minutes. Please click on the link in the email to verify your email address.";
        $data['resend_link'] = $user->id;
        return view('auth.verifyEmail')->with('data', $data)->with('success', 'Your account created successfully!');
    }

    /**
     * Show the form to get user's email address to sent password reset link
     * 
     * @return \Illuminate\Http\Response
     * 
     * @created by Jayalakshmi Ramasamy @on 04/01/2019
     * @modified
     */
    public function lostPassword(Request $request) {
        if ($user = Auth::user()) {
            return redirect('/choose_option')->with('info', 'You\'re already logged in');
        }
        if ($request->isMethod('post')) {
            $user = User::where('email', $request->get('email'))->first();
            if (isset($user)) {
                // Send reset password mail to user
                $user->notify(new ResetPassword($user));

                return redirect('/login')->with('success', "Please check your email for the instructions on how to reset your password.");
            } else {
                $request->session()->flash('danger', 'Invalid email address');
                return redirect()->back()->withInput();
            }
        }
        return view('auth.forget_password');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {
        if (!empty($request)) {
            $user = Auth::user();
            // Get profile details
            $userProfile = User::with('profile')->find($user->id);
            // Server side validation
            $validator = Validator::make($request->all(), [
                        'email' => 'required|unique:users,email,' . $user->id,
                        'mobile' => 'required|unique:user_profiles,mobile,' . $userProfile->profile->id,
                        'name' => 'required',
                        'organisation_id' => 'required'
            ]);
            if ($validator->fails()) {
                return redirect('/my_profile')
                                ->withErrors($validator)
                                ->withInput();
            }
            // Update user credentials
            $isNewEmail = false;
            $getDate = $request->all();
            if ($userProfile->email != trim($request->get('email'))) {
                $isNewEmail = true;
                $getDate['email_verified_at'] = null;
            }
            $userProfile->fill($getDate);
            $userProfile->save();

            // Update User's profile
            $profile = UserProfile::where('user_id', $userProfile->id)->first();
            $profile->name = $request->get('name');
            $profile->mobile = $request->get('mobile');
            $profile->phone = $request->get('phone');
            $profile->organisation_name = $request->get('organisation_name');
            $profile->country = $request->get('country');
            $profile->organisation_id = $request->get('organisation_id');
            $profile->save();

            // Verify email address if user updates new email address
            if ($isNewEmail) {
                Auth::logout();
                $user = User::where('id', $userProfile->id)->first();
                $user->notify(new VerifyEmail($user));
                $data['heading'] = "We've sent you a verification email to your new email address.";
                $data['content'] = "A verification email should be received within several minutes. Please click on the link in the email to verify your email address.";
                $data['resend_link'] = $user->id;
                return view('auth.verifyEmail')->with('data', $data)->with('success', 'Your account created successfully!');
            }
            return redirect('/my_profile')->with('success', 'Your account updated successfully!');
        }
    }

    /**
     * Show form for Admin Approval
     * 
     * @required remember_token
     * 
     * @created by Jayalakshmi Ramasamy on 06/10/2018
     * @modified
     */
    public function adminApproval($token) {
        $user = User::where('remember_token', $token)->first();
        if (isset($user)) {
            if (!$user->admin_approved) {
                $user->admin_approved = 1;
                $user->save();
                $status = "Account activated successfully!";
            } else {
                $status = "Account already activated.";
            }
        } else {
            return redirect('/login')->with('warning', "Sorry account cannot be identified.");
        }

        return redirect('/login')->with('info', $status);
    }

    /**
     * Verify the user's email address
     * 
     * @required remember_token
     * 
     * @created by Jayalakshmi Ramasamy on 06/10/2018
     * @modified
     */
    public function verifyEmail($token) {
        if ($user = Auth::user()) {
            return redirect('/choose_option')->with('info', 'You\'re already logged in');
        }
        $user = User::where('remember_token', $token)->first();
        if (isset($user)) {
            if (!$user->email_verified_at) {
                $user->email_verified_at = gmdate('Y-m-d H:i:s');
                $user->save();
                $status = "Email varified successfully!";
            } else {
                $status = "Email already verified.";
            }
            $data['heading'] = "Email Verification";
            $data['content'] = "Verification completed";
            $data['resend_link'] = null;
            return view('auth.verifyEmail')->with('data', $data);
        } else {
            return redirect('/login')->with('warning', "Sorry your email address cannot be identified.");
        }
        Auth::logout();
        return redirect('/login')->with('info', $status);
    }

    /**
     * Resend verification email
     * 
     * @required data: user_id
     * @optional data:
     * 
     * @created by Jayalakshmi Ramasamy on 27/01/2019
     * @modified 
     */
    public function resend($user_id) {
        if ($user = Auth::user()) {
            return redirect('/choose_option')->with('info', 'You\'re already logged in');
        }
        $data['heading'] = "We've sent you a verification email.";
        $data['content'] = "A verification email should be received within several minutes. Please click on the link in the email to verify your email address.";
        $data['resend_link'] = $user_id;
        if (!is_null($user_id)) {
            $user = User::where('id', $user_id)->first();
            if (isset($user)) {
                if (is_null($user->email_verified_at)) {
                    $user->notify(new VerifyEmail($user));
                    $data['resend_link'] = $user_id;
                    Session::flash('resent', "Resent");
                } else {
                    $data['heading'] = "Email Verification";
                    $data['content'] = "Verification completed";
                    $data['resend_link'] = null;
                }
            }
        }
        return view('auth.verifyEmail')->with('data', $data);
    }

    /**
     * Show the form to reset user's password and update new password details in account
     * 
     * @required data: token, password, confirm_password
     * @optional data: none
     * 
     */
    public function resetPassword($token, Request $request) {
        $user = User::where('remember_token', $token)->first();
        if (isset($user)) {
            if ($request->isMethod('post')) {
                // Server side validation
                $validator = Validator::make($request->all(), [
                            'password' => 'required',
                            'confirm_password' => 'required|same:password|min:8'
                ]);
                if ($validator->fails()) {
                    return redirect('/resetPassword/' . $token)
                                    ->withErrors($validator)
                                    ->withInput();
                }

                // Update password
                $user->password = Hash::make($request->get('password'));
                $user->remember_token = $request->get('_token');
                $user->save();

                return redirect('/login')->with('success', 'Password has been updated successfully');
            }
        } else {
            return redirect('/login')->with('danger', 'Sorry account cannot be indentified');
        }

        return view('auth.reset_password');
    }

    /**
     * Show a form to update user details
     * 
     * @return \Illuminate\Http\Response
     */
    public function myProfile(Request $request) {
        $user_id = Auth::id();
        $user = User::with('profile')->where('id', $user_id)->first();
        $organisation = Organisation::pluck('organisation_name', 'id');
        return view('pages.my_profile')
                        ->with('organisation', $organisation)
                        ->with('user', $user);
    }

    /**
     * Update new password
     * 
     * @required data: password, confirm_password
     * @optional data: none
     * 
     */
    public function changePassword(Request $request, User $user) {
        $user_id = Auth::id();
        if (isset($user_id)) {
            if ($request->isMethod('post')) {
                // Server side validation
                $validator = Validator::make($request->all(), [
                            'password' => 'required',
                            'confirm_password' => 'required|same:password|min:8'
                ]);
                if ($validator->fails()) {
                    return redirect('/my_profile/')
                                    ->withErrors($validator)
                                    ->withInput();
                }

                // Update password
                $user = User::where('id', $user_id)->first();
                $user->password = Hash::make($request->get('password'));
                $user->save();

                return redirect('/my_profile')->with('success', 'Password has been updated successfully');
            }
        } else {
            return redirect('/login')->with('danger', 'You\'re unauthorized to access. Please login');
        }

        return view('auth.reset_password');
    }

    /**
     * Add/Update User's profile picture
     * 
     * @required data: profile_pic
     * @optional data: none
     * 
     * @return plaintext picture path
     */
    public function AjaxUploadPic(Request $request, User $user) {
        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $destinationPath = 'public/images/avatar';
            $filename = time() . '.' . $file->getClientOriginalExtension();
            if ($file->move($destinationPath, $filename)) {
                // Get old profile picture
                $old_image = isset(Auth::user()->profile->profile_pic) ? Auth::user()->profile->profile_pic : '';
                if ((!empty($old_image)) && file_exists($destinationPath . "/" . $old_image)) {
                    // Delete old file
                    unlink($destinationPath . "/" . $old_image);
                }
                $user_id = Auth::id();
                // Update User's profile picture
                $profile = UserProfile::where('user_id', $user_id)->first();
                $profile->profile_pic = $filename;
                $profile->save();
                return redirect('/my_profile')->with('success', "Profile picture has been uploaded successfully!");
            } else {
                return redirect('/my_profile')->with('danger', 'Could not upload profile picture. Please try again!');
            }
        }
    }

    /**
     * Delete all sessions
     *
     * @return none
     * 
     * @created by Jayalakshmi Ramasamy @on 04/01/2019
     * @modified
     */
    public function logout() {
        Session::flush();
        Auth::logout();
        return redirect('login');
    }

    /**
     * Delete user based on email address
     */
    public function deleteUser(Request $request) {
        $user = Auth::user();
        if ($user->id == 1) {
            if ($request->isMethod('post')) {
                User::where('email', $request->get('email'))->forcedelete();
                Session::flash('success', 'User deleted successfull!');
            } else if ($request->isMethod('get')) {
                
            }
            return view('auth.forget_password');
        } else{
            return redirect('choose_option')->with('warning', "You are not authorized to access delete user functionality");
        }        
    }

}
