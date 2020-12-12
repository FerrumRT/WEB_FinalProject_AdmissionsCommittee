<?php

namespace App\Http\Controllers;

use App\AdmissionMember;
use App\EducationDegree;
use App\Http\Controllers\Auth\RegisterController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdmissionMemberController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        if (Auth::user() == null)
            return redirect('/login');
        if (!Auth::user()->is_adm_member)
            return redirect('/403');
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'is_adm_member' => true
        ]);

        $user_id = User::where('email', $request['email'])->first()->id;

        AdmissionMember::create([
            'user_id' => $user_id
        ]);

        return redirect('/admin/admission_members')->with('success', 'Admission member created');
    }

    public function show(int $id)
    {
        if (Auth::user() == null)
            return redirect('/login');
        if (!Auth::user()->is_adm_member)
            return redirect('/403');
        $user = User::find($id);
        $admission_member = AdmissionMember::where('user_id',$user->id)->first();
        $ad_mem_img = \Storage::disk('public')->url($admission_member->image_url);
        $edu_deg = EducationDegree::all();
        return view("pages/ad_mem/profile")->with('title', "Profile - Admission")
            ->with("admission_member", $admission_member)
            ->with("edu_deg", $edu_deg)
            ->with("ad_mem_img", $ad_mem_img);
    }

    public function edit($id)
    {
        if (Auth::user() == null)
            return redirect('/login');
        if (!Auth::user()->is_adm_member)
            return redirect('/403');
        $admission_member = AdmissionMember::find($id);
        return view("pages/admin/ad_mem_edit")->with('title', "Admission Members - Admission")->with("admission_member", $admission_member);
    }


    public function update(Request $request, $id)
    {
        if (Auth::user() == null)
            return redirect('/login');
        if (!Auth::user()->is_adm_member)
            return redirect('/403');
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required'
        ]);


        $admission_member = AdmissionMember::find($id);
        $user = User::find($admission_member->user_id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->input('old_password') != ""
            && $request->input('new_password') != ""
            && Hash::check($request->input('old_password'), $user->password))
            $user->password = Hash::make($request->input('new_password'));
        else if($request->input('old_password') != ""
            && $request->input('new_password') != "")
            return redirect('/admin/'.$id.'/editAdmissionMember')->with('error', 'Passwords doesnt match');
        $user->phone_number = $request->input('phone_number');
        $user->birthdate = $request->input('birthdate');

        $img = \Image::make($request->file('image')->getRealPath());
        $img_path = 'img/ad_mem_img/'.'ad_mem_'.$admission_member->id.'_img'.'.jpg';
        $img->save(storage_path('app/public/'.$img_path));

        $admission_member->image_url = $img_path;

        $user->save();

        $admission_member->save();

        return redirect('/admin/admission_members')->with('success', 'Admission member updated');
    }

    public function destroy($id)
    {
        if (Auth::user() == null)
            return redirect('/login');
        if (!Auth::user()->is_adm_member)
            return redirect('/403');
        $admission_member = AdmissionMember::find($id);
        $admission_member->delete();
        return redirect('/admin/admission_members')->with('success', 'Admission member deleted');
    }
}
