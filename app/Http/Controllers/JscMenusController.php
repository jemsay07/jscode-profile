<?php

namespace App\Http\Controllers;

use App\JscMenus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\JscMenuItems;
use App\User;
use Auth;
use Validator;

class JscMenusController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu = JscMenus::orderby('id','desc')->paginate(5);
        // $paginate = JscMenus::orderby('id','desc')->get();

        return view('admin.menu.list', compact('menu'));
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
        if (Gate::check('isAdmin')) {
            if ( $request->ajax() ) {
                $method = $request->menu_method;
                $menu_item = new JscMenuItems();
                
                if ( $method == 'create_menu' ) {

                    $menu = new JscMenus();

                    $validator = Validator::make( $request->all(), 
                        ['menu_title'   => 'required|string|min:5'],
                        ['menu_title.required'       => 'Menu title field is required.'] );

                    if ( $validator->fails() ) {
                        // return response()->json(['error'=>$validator->errors()->all()]);
                        return response()->json(['message'=> 'Provide what needed.','errors' => $validator->getMessageBag()->toArray()]);
                    }else{
                        if ( JscMenus::where('menu_title', $request->menu_title)->exists() ) {
                            return response()->json([ 'status'=> 'taken', 'message' => 'This title is already takin.'], 200);
                        }else{

                            /**Add Location default value */
                            $location = array( 'automatic' => 'on', 'primary' =>'off', 'social' => 'off', 'footer' => 'off' );
                            $location_serialize = serialize($location);

                            $menu->menu_title = request('menu_title');
                            $menu->menu_location = $location_serialize;
                            $menu->save();

                            $last_menu_id = $menu->id;

                            /**Add default data value for JscMenuItems*/
                            if ( $last_menu_id ) {
                                $menu_item->menu_id = $last_menu_id;
                                $menu_item->parent_id = 0;
                                $menu_item->menu_item_order = 0;
                                $menu_item->menu_item_type = 'custom';
                                $menu_item->menu_item_status = 'draft';
                                $menu_item->menu_item_title = 'Unrecognized';
                                $menu_item->menu_item_desc = '';
                                $menu_item->menu_item_object = 'custom';
                                $menu_item->menu_item_url = 'http://unrecognized.com';
                                $menu_item->menu_item_xfn = '';
                                $menu_item->menu_item_attr_title = 'unrecognized';
                                $menu_item->menu_item_css = '';
                                $menu_item->menu_item_id = '';
                                $menu_item->menu_item_tab = 0;
                                $menu_item->save();                                
                            }

                            /**Append data */
                            // $data = JscMenus::appends(['menu'=>'title'])->render();


                            /**jSON data */
                            $return_args = [
                                'status'=> 'success',
                                'message'=> 'Success',
                                'last_insert_id' => $menu->id,
                                'title' => $menu->menu_title,
                                'date' => $menu->created_at->diffForHumans(),
                                // 'sample' => $data
                            ];

                            return response()->json($return_args);
                        }
                    }

                }else{
                    if ( $request->menu_item_object == 'custom' ) {

                        // $menu_item = new JscMenuItems();

                        $validator = Validator::make( $request->all(), self::menu_rules(), self::menu_msg() );

                        if ($validator->fails()) {
                            // return response()->json(['error'=>$validator->errors()->all()]);
                            return response()->json(['status'=> 'error','message'=> 'Provide what needed.','errors' => $validator->getMessageBag()->toArray()]);
                        }else{
                            $menu_item->menu_id = request('menu_id');
                            $menu_item->parent_id = 0;
                            $menu_item->menu_item_order = 0;
                            $menu_item->menu_item_type = request('menu_item_type');
                            $menu_item->menu_item_status = request('menu_item_status');
                            $menu_item->menu_item_title = request('menu_item_title');
                            $menu_item->menu_item_desc = '';
                            $menu_item->menu_item_object = request('menu_item_object');
                            $menu_item->menu_item_url = request('menu_item_url');
                            $menu_item->menu_item_xfn = '';
                            // $menu_item->menu_item_attr = '';
                            $menu_item->menu_item_attr_title = '';
                            $menu_item->menu_item_css = '';
                            $menu_item->menu_item_id = '';
                            $menu_item->menu_item_tab = 0;

                            $menu_item->save();
                            $last_id = $menu_item->id;
                            $last_menu_id = $menu_item->menu_id;
                            $last_menu_title = $menu_item->menu_item_title;
                            $last_menu_url = $menu_item->menu_item_url;

                             /**Return Modal*/
                            $menu_item = JscMenuItems::where('id', $last_id)->with('children')->orderby('menu_item_order', 'asc')->get();

                            $data_modal = view( 'admin.menu.menu_item_modal', compact('menu_item') )->render();


                            $args = [
                                'status' => 'success',
                                'message'=> 'New Menu added',
                                'menu_id' => $last_menu_id,
                                'last_insert_id' => $last_id,
                                'title'=> $last_menu_title,
                                'link'=> $last_menu_url,
                                'modal'=> $data_modal,
                            ];
                            

                            return response()->json($args);
                        }
                    }

                    return response()->json([ 'status'=> 'custom', 'message'=> 'not inside of if statement' ], 200);

                }
                // return response()->json([ 'status'=> 'custom', 'message'=> 'not' ], 200);
            }
        } else{
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JscMenus  $jscMenus
     * @return \Illuminate\Http\Response
     */
    public function show(JscMenus $jscMenus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JscMenus  $jscMenus
     * @return \Illuminate\Http\Response
     */
    public function edit(JscMenus $jscMenus, $id)
    {
        if( Gate::check('isAdmin') ){
            $menu_id = $id;
            $getMenu = JscMenus::find($menu_id)->latest()->first();
            $menu = JscMenus::find($menu_id)->menuItems()->latest()->first();

            if ( $menu->count() > 0 ) {
                $menu_item = JscMenuItems::where('menu_id', $menu_id)->whereNotIn('menu_item_status', ['trash'])->with('children')->orderby('menu_item_order', 'asc')->get();
                // $menu_item = JscMenuItems::findOrfail($id)->children()->where('menu_item_status', 'publish')->orderby('menu_item_order', 'asc')->get();
            }

            return view( 'admin.menu.menu_item', compact('menu', 'getMenu', 'menu_id', 'menu_item') );
        }else{
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JscMenus  $jscMenus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JscMenus $jscMenus, $id)
    {
        if( Gate::check('isAdmin') ){
            if ($request->ajax()) {
                if ( $request->menu_action == 'modal_update_details' ) {
                    $menuItems = JscMenuItems::findOrfail($id);
                    $validator = Validator::make( $request->all(), self::menu_rules(), self::menu_msg() );

                    if ( $validator->fails() ) {
                        return response()->json(['status'=> 'error','message'=> 'Provide what needed.','errors' => $validator->getMessageBag()->toArray()]);
                    }else{
                        $update_item = [
                            'menu_item_title' => $request->menu_item_title,
                            'menu_item_desc' => $request->menu_item_desc,
                            'menu_item_url' => $request->menu_item_url,
                            'menu_item_xfn' => $request->menu_item_xfn,
                            'menu_item_attr_title' => $request->menu_item_attr_title,
                            'menu_item_css' => $request->menu_item_css,
                            'menu_item_id' => $request->menu_item_id,
                            'menu_item_tab' => $request->menu_item_tab,
                        ];
                        
                        $update = JscMenuItems::where('id', $id)->update($update_item);
                        $item_title = '';
                        $item_menu_id = '1';
                        if ($update) {
                            $item = JscMenuItems::where('id', $id)->first();
                            $item_menu_id = $item->menu_id;
                            $item_title = $item->menu_item_title;
                        }

                        /**For Nested*/
                        // if ( $request->menu_action_status == 'nested' ) {
                        //     $menu_item = JcMenuItems::where('id', $id)->first();

                        //     $up_args = [
                        //         'status' => 'success',
                        //         'message'=>'Successfully Update',
                        //         'id' => $menu_item->id,
                        //         'menu_title' => $menu_item->menu_item_title,
                        //         'menu_url' => $menu_item->menu_item_url,
                        //         'menu_attr_title' => $menu_item->menu_item_attr_title,
                        //         'menu_tab' => $menu_item->menu_item_attr_title,
                        //         'menu_css_id' => $menu_item->menu_item_css,
                        //         'menu_xfn' => $menu_item->menu_item_id,
                        //         'menu_desc' => $menu_item->menu_item_desc,
                        //     ];

                        //     return response()->json([$up_args]);
                        // }
                        
                        return response()->json(['status' => 'success','message'=>'Successfully Update', 'last_insert_id' => $id, 'last_title' => $item_title,'item_menu_id' => $item_menu_id ]);
                    }
                }else if ( $request->menu_action == 'saveUpdateMenuItem' ){
                    $menu_id = $request->_menu_id;
                    $menu_action = $request->_m_action;
                    $location = array( 'automatic' => $request->automatic, 'primary' => $request->primary, 'social' => $request->social, 'footer' => $request->footer );
                    $location_serialize = serialize($location);

                    /**Update jsc_menuses menu_locations*/
                    JscMenus::where('id', $id)->update([ 'menu_location' => $location_serialize ]);

                    /**Update jsc_menu_item menu_status*/
                    JscMenuItems::where('menu_id', $id)->where('menu_item_status', 'draft')->update([ 'menu_item_status' => 'publish' ]);

                    /**Redirect the page*/
                    $redirect = 'menu/' . $id . '/menu_edit';

                    return response()->json([ 'status'=> 'success', 'message' => 'Update Successfully.', 'redirect'=> $redirect ], 200);

                    // $opv = jc_Options::where('option_name', 'nav_menu_options')->get();

                    // if ($_maction === 'createNewMenu') {
                    //     $args = array();
                    //     foreach ($opv as $v) {
                    //         $args[] = $v->option_value;
                    //     }

                    //     if ( $id !== $args ) {
                            
                    //         //Add new nav_menu_options
                    //         $Opt = new jc_Options;
                    //         $Opt->option_name = 'nav_menu_options';
                    //         $Opt->option_value = $id;
                    //         $Opt->save();
                    //     }
                    //     $redirect = 'menu/' . $id . '/nav-item/edit';

                    //     return response()->json([ 'status'=> 'publish', 'redirect'=> $redirect ], 200);
                    // }
                }else if ( $request->menu_action == 'updateMenuTitle' ){

                    /**Editable: Update the menu_title.*/
                    $menu = JscMenus::where('id', $id)->update(['menu_title' => $request->menu_title]);

                    /**Editable: Get the menu_title and pass it to a new variable.*/
                    $menu = JscMenus::where('id', $id)->latest()->first();
                    $menu_title = $menu->menu_title;

                    return response()->json(['status' => 'success', 'message' => 'Updated', 'title' => $menu_title]);
                }else{
                    return back();
                }
            }
        }else{ return back(); }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JscMenus  $jscMenus
     * @return \Illuminate\Http\Response
     */
    public function destroy(JscMenus $jscMenus, Request $request, $id)
    {
        if( Gate::check('isAdmin') ){
            if( $request->ajax() ){
                if( $request->menu_action == 'listMenuPage' ){

                    JscMenus::where('id', $id)->delete();
                    JscMenuItems::where('menu_id', $id)->delete();

                    $redirect = ( $request->menu_method == 'deleteMenuPage' ) ? 1 : 0; 
                    return response()->json(['status' => 'deleted','message' => 'Successfully Deleted', 'redirect' => $redirect, 'return_id' => $id]);
                }else{
                    /**Delete the li nested item. */
                    JscMenuItems::where('id', $id)->where('menu_id', $request->menu_parentID)->update(['menu_item_status' => 'trash']);
                    return response()->json(['status' => 'success', 'message' => 'Successfully Deleted', 'menu_itemID' => $id, 'menu_parentID' => $request->menu_parentID]);
                }
            }
        }else{ return back(); }
    }

    public function nestable(Request $request){
        if( Gate::check('isAdmin') ){ 
            if ( $request->ajax() ) {

                $parent_id = 0;
                $item_order = 0;

                $id = [];
                foreach ($request->list as $list) {
                    
                    $item_order++;
                    $id[] = $list['id'];

                    $menu = JscMenuItems::where( 'id', $list['id'] )->update([ 'parent_id' => $parent_id, 'menu_item_order' => $item_order ]);

                    if ( array_key_exists( 'children', $list ) ) {

                        foreach($list['children'] as $menu_child_list){
                            $menu = JscMenuItems::where( 'id', $menu_child_list['id'] )->update([ 'parent_id' => $list['id'], 'menu_item_order' => $item_order ]);
                        }
                    }
                }
                return response()->json([ 'status' => 'success','message'=> 'Successfully' ]);
            }
        }else{
            return back();
        }
    }


    public function menu_rules(){
        return [
            'menu_item_url'           => 'required|url|unique:jsc_menu_items,menu_item_url',
            'menu_item_title'         => 'required|unique:jsc_menu_items,menu_item_title',
        ];
    }

    public function menu_msg(){
        return [
            'menu_item_url.required'  => 'The Menu url field is required.',
            'menu_item_url.url'       => 'The Menu url field must be url.',
            'menu_item_title.required'     => 'The Menu title is required.',
            // 'menu_item_title.unique:jsc_menu_items,menu_item_title'     => 'The Menu title has already been taken.',
        ];
    }
}
