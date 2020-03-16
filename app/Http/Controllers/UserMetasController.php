<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Support\Facades\Validator;

use App\UserMetas;
use App\User;
use Auth;

use Validator;


class UserMetasController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        // return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        // return view('admin.user.profile-view');
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
     * @param  \App\UserMetas  $userMetas
     * @return \Illuminate\Http\Response
     */
    public function show(UserMetas $userMetas)
    {
        if (Gate::check('isAdmin')) {
            $user_id = Auth::id();
            $email = User::findOrfail($user_id)->first();
            $prof = user::find($user_id)->user_meta;
            return view('admin.user.profile-show', compact('user_id','prof','email'));
        } else {
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserMetas  $userMetas
     * @return \Illuminate\Http\Response
     */
    public function edit(UserMetas $userMetas)
    {
        if (Gate::check('isAdmin')) {
            $user_id = Auth::id();
            $email = User::findOrfail($user_id)->first();
            $user = user::find($user_id)->user_meta;
            return view('admin.user.edit', compact('user_id','user', 'email' ));
        } else {
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserMetas  $userMetas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // check the user id
       if (Gate::check('isAdmin')) {
           if ($request->ajax()) {

                // validate the data
                $validator = Validator::make(request()->all(), self::opt_rules(), self::opt_messages());

                if ($validator->fails()) {
                     // return response()->json(['errors'=>$validator->errors()->all(), 'success' => false]);
                    return response()->json(['message'=> 'Provide what needed.','errors' => $validator->getMessageBag()->toArray()]);
                } else {

                    /**skills-meta*/
                    $skills = request('skills');

                    for ($skills_count=0; $skills_count < count($skills) ; $skills_count++) {
                        $skills_args = array(
                            'icon'  => $skills[$skills_count]['icon'],
                            'skill_name'  => $skills[$skills_count]['skill_name'],
                            'percent'  => $skills[$skills_count]['percent']
                        );
                        $insertSkills[] = $skills_args;
                    }
                                    
                    /**work-meta*/
                    $work_exp = request('work_exp');

                    for ($work_exp_count=0; $work_exp_count < count($work_exp) ; $work_exp_count++) {

                        $work_exp_args = array(
                            'position'  => $work_exp[$work_exp_count]['position'],
                            'company_name'  => $work_exp[$work_exp_count]['company_name'],
                            'role'  => $work_exp[$work_exp_count]['role'],
                            'start_joined'  => ( isset($work_exp[$work_exp_count]['start_joined']) ) ? $work_exp[$work_exp_count]['start_joined'] : '' ,
                            'to_joined'  => ( isset($work_exp[$work_exp_count]['to_joined']) ) ? $work_exp[$work_exp_count]['to_joined'] : '' ,
                            'present'  => $work_exp[$work_exp_count]['present']
                        );
                        $insertWork[] = $work_exp_args;
                    }

                    /**social-media*/
                    $sm = request('sm');

                    for ($sm_count=0; $sm_count < count($sm) ; $sm_count++) { 
                        $sm_args = array(
                            'icon'  => $sm[$sm_count]['icon'],
                            'link'  => $sm[$sm_count]['link'],
                            'tab'  => $sm[$sm_count]['tab'],
                        );
                        $insertSm[] = $sm_args;                        
                    }
                    // dd($sm_args);
                    
                    /**schools*/
                    $schools = request('schools');

                    for ($schools_count=0; $schools_count < count($schools) ; $schools_count++) {
                        $schools_args = array(
                            'attainment'  => $schools[$schools_count]['attainment'],
                            'university'  => $schools[$schools_count]['university'],
                            'qualify'  => $schools[$schools_count]['qualify'],
                            'course'  => $schools[$schools_count]['course'],
                            'major'  => $schools[$schools_count]['major'],
                            'date'  => $schools[$schools_count]['date'],
                        );
                        $insertSchools[] = $schools_args;                        
                    }

                    $update_args = array(
                        'user_id' => Auth::id(),
                        'first_name' => request('first_name'),
                        'middle_name' => request('middle_name'),
                        'last_name' => request('last_name'),
                        'gender' => request('gender'),
                        'birthday' => 'Dec 7, 1991',
                        'age' => request('age'),
                        'address' => request('address'),
                        'contact_number' => request('contact'),
                        'social_meta' => serialize($insertSm),
                        'bio' => request('bio'),
                        'skill_type' => request('skills_type'),
                        'schools_meta' => serialize($insertSchools),
                        'skills_meta' => serialize($insertSkills),
                        'work_exp_meta' => serialize($insertWork),
                    );

                    // dd( $update_args );
                    // update the user info
                    $last_inserted = UserMetas::where('id', $id)->update($update_args);

                    // dd(serialize($insertSkills));

                    // get the last id & send it to options

                    //Update the user
                    if ($last_inserted == $id) {
                        User::where('id', $last_inserted)->update(['email' => $request->email]);
                    }
                    return response()->json(['message'=> 'Successfully updated.','data' => $request->all(),'success' => true]);
                }

            }
       } else {
           return back();
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserMetas  $userMetas
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserMetas $userMetas)
    {
        //
    }

    /**
     * Validation rules
     */
    public function opt_rules(){
        $opt_rules = [
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'bio' => 'required',
            'address' => 'required',
            'age' => 'required|integer',
            'gender' => 'required',
            'contact' => 'required',
            'skills.*.skill_name' => 'required',
            'schools.*.university' => 'required',
            'work_exp.*.position' => 'required',
            'work_exp.*.company_name' => 'required',
            'work_exp.*.role' => 'required',
            'work_exp.*.start_joined' => 'required',
            'sm.*.link' => 'required|url',
        ];
        return $opt_rules;
    }

    public function opt_messages(){
        $opt_messages = [
            'email.required' => 'Email is required.',
            'email.email' => 'Kindly check your email.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'bio.required' => 'Bio is required.',
            'address.required' => 'The address is required.',
            'age.required' => 'The age is required.',
            'age.integer' => 'String and special characters are not allowed.',
            'gender.required' => 'The gender is required.',
            'contact.required' => 'The contact info is required.',
            'skills.*.skill_name.required' => 'Your skill is required.',
            'schools.*.university.required' => 'Your school is required.',
            'work_exp.*.position.required' => 'Your position is required.',
            'work_exp.*.company_name.required' => 'Your company name is required.',
            'work_exp.*.role.required' => 'Your role is required.',
            'work_exp.*.start_joined.required' => 'Your start is required.',
            'sm.*.link.required' => 'Your link is required.',
            'sm.*.link.url' => 'Must be a link.',
        ];

        return $opt_messages;
    }

}
