<?php

namespace App\Http\Controllers;

use App\Models\contact;
use App\Models\email;
use App\Models\phone_number;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = contact::paginate(5);
        $phones = phone_number::all();
        $emails = email::all();

        return view('contact', compact('contacts','phones','emails'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {


        //variable for checking existing email and phone
        $check_phone_email = 0;

        //validation
        $request->validate([
            'first_name' => 'required',
            'phone' => 'required',
            'phone.*' => 'required',
            'email' => 'required',
            'email.*' => 'required'
        ]);

        $contact = new contact([
            'name' => $request->get('first_name')
        ]);



        //Check if the phone number is in the database
        foreach ($request->get('phone') as $row) {
            if (phone_number::where('phone', '=', $row)->exists()) {
                $check_phone_email = 1;
            }
        }

        //Check if the email is in the database
        foreach ($request->get('email') as $row) {
            if (email::where('email', '=', $row)->exists()) {
                $check_phone_email = 2;
            }
        }

        if (contact::where('name', '=', $request->get('first_name'))->exists()) {
            $check_phone_email = 3;
        }

        if ($check_phone_email==1) {
            return redirect('/addcontact')->with('fail', 'This phone number is in the database');
        }

        else if($check_phone_email==2){
            return redirect('/addcontact')->with('fail', 'This email is in the database');
        }
        else if($check_phone_email==3){
            return redirect('/addcontact')->with('fail', 'This name is in the database');
        }

        //save contact phones and emails
        else{

            $contact->save();
            $insert_contact_id = $contact->id;
            foreach ($request->get('phone') as $row) {
                $phone = new phone_number([
                    'contact_id' => $insert_contact_id,
                    'phone' => $row
                ]);
                $phone->save();
            }
            foreach ($request->get('email') as $row) {
                $email = new email([
                    'contact_id' => $insert_contact_id,
                    'email' => $row
                ]);
                $email->save();
            }
            return redirect('/')->with('success', 'Contact saved!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\contact $contact
     * @return \Illuminate\Http\Response
     */
    public function show(contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\contact $contact
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $phones = phone_number::where('contact_id',$id)->get();
        $emails = email::where('contact_id',$id)->get();
        $contact = contact::find($id);

        return view('update', compact('contact','phones','emails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\contact $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //validation
        $request->validate([
            'first_name' => 'required',
            'phone' => 'required',
            'phone.*' => 'required',
            'email' => 'required',
            'email.*' => 'required'
        ]);

        //update phone numbers
        foreach ($request->get('phone') as $key => $row) {

            $phone = phone_number::find($key);

            $phone->phone = $row;

            $phone->save();
        }

        //update emails
        foreach ($request->get('email') as $key => $row) {

            $email = email::find($key);

            $email->email = $row;

            $email->save();
        }

        //update contact name
        $contact = contact::find($id);
        $contact->name =  $request->get('first_name');
        $contact->save();

        return redirect('/')->with('success', 'Contact updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\contact $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(contact $contact)
    {
        //
    }
    public function search(Request $request)
    {
        //validation
        $request->validate([
            'search_text' => 'required'
        ]);

        $search = $request->get('search_text');

        //variable for contact ids detected in the search
        $contact_ids = array();

        //retrieve the id of the contact found in the search
        $contact_searchs = contact::where('name', 'LIKE', '%' . $search . '%')->get();
        foreach ($contact_searchs as $contact){
            array_push($contact_ids,$contact->id);
        }

        $phones = phone_number::where('phone', 'LIKE', '%' . $search . '%')->get();
        foreach ($phones as $phone){
            array_push($contact_ids,$phone->contact_id);
        }

        $emails = email::where('email', 'LIKE', '%' . $search . '%')->get();
        foreach ($emails as $email){
            array_push($contact_ids,$email->contact_id);
        }

        $contacts = contact::whereIn('id', $contact_ids)->get();
        $phones = phone_number::all();
        $emails = email::all();

        return view('search', compact('contacts','phones','emails'));



        //print_r($contact_ids);
    }

    public function delete($id){

        phone_number::where('contact_id',$id)->delete();
        email::where('contact_id',$id)->delete();

        $contact = contact::find($id);
        $contact->delete();

        return redirect('/')->with('success', 'Contact deleted!');
    }

}
