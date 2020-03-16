<?php

namespace App\Http\Controllers;

use App\JscPage;
use Illuminate\Http\Request;

class JscPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.posts.page-list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function page_create ()
    {
        return view('admin.posts.page-add-new');
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
     * @param  \App\JscPage  $jscPage
     * @return \Illuminate\Http\Response
     */
    public function show(JscPage $jscPage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JscPage  $jscPage
     * @return \Illuminate\Http\Response
     */
    public function edit(JscPage $jscPage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JscPage  $jscPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JscPage $jscPage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JscPage  $jscPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(JscPage $jscPage)
    {
        //
    }
}
