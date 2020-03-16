<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Carbon\Carbon;
use App\JscPosts;
use App\JscPostsMeta;
use App\JscMedia;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Database\Migrations\Migration;


class JscPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function page_index()
    {
        $post_status = app('request')->input('post_status');
        
        if($post_status === 'publish'){
            $count = JscPosts::where('post_status', 'publish')->count();
            $post_status = 'publish';
        }elseif($post_status === 'draft'){
            $count = JscPosts::where('post_status', 'draft')->count();
            $post_status = 'draft';
        }elseif($post_status === 'trash'){
            $count = JscPosts::where('post_status', 'trash')->count();
            $post_status = 'trash';
        }else{
            $count = JscPosts::where('post_status', 'publish')->orWhere('post_status', 'draft')->count();
            $post_status = '';
        }

        $countAll = JscPosts::where('post_status', 'publish')->orWhere('post_status', 'draft')->count();
        $countPublish = JscPosts::where('post_status', 'publish')->count();
        $countDraft = JscPosts::where('post_status', 'draft')->count();
        $countTrash = JscPosts::where('post_status', 'trash')->count();
        
        if($post_status){
            $pages = DB::table('user_roles')
                ->join('jsc_posts', function($join) use ($post_status){
                    $join->on('user_roles.id', '=', 'jsc_posts.post_author')
                    ->where('jsc_posts.post_status', $post_status);
                })->orderBy('jsc_posts.id','desc')->paginate(10);
            $pages->withPath('?post_status=' . $post_status);
        }else{
            $pages = DB::table('user_roles')
                ->join('jsc_posts', function($join){
                    $join->on('user_roles.id', '=', 'jsc_posts.post_author')
                    ->where('jsc_posts.post_status', 'publish')->orWhere('jsc_posts.post_status', 'draft');
                })->orderBy('jsc_posts.id','desc')->paginate(10);
        }

        $date = self::date();       
        // dd($date);

        return view('admin.posts.page-list', compact('pages', 'count', 'countAll', 'countPublish', 'countDraft', 'countTrash', 'post_status', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function page_create()
    {
        if (Gate::check('isAdmin')) {
            $ids = Auth::id();
            $media = JscMedia::get();


            $posts = new JscPosts;

            $posts->post_parent = 0;
            $posts->post_media_id = 0;
            $posts->post_author = $ids;
            $posts->post_title = 'Auto Draft';
            $posts->post_content = '';
            $posts->post_excerpt = '';
            $posts->post_status = 'auto-draft';
            $posts->post_type = 'page';
            $posts->post_name = '';
            $posts->guid = '';
            $posts->save();

            $last_id = $posts->id;
            $timestamp = Carbon::now()->timestamp;
            

            if($last_id){
                $posts_meta = new JscPostsMeta;
                $posts_meta->post_id = $last_id;
                $posts_meta->meta_key = '_edit_lock';
                $posts_meta->meta_value = $timestamp . ':' . $ids;
                $posts_meta->save();
            }
            // $last_id = 1;
            return view('admin.posts.page-add-new', compact('media', 'last_id'));
        }else{
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::check('isAdmin') && $request->ajax()) {
        /**
            * DATA Status
            * - Auto Draft
            * - Draft
            * - Publish
            * - Inherit
            * - Trash
            */
            $ids = Auth::id();
            $post_status = $request->post_status;
            $redirect = 0;

            if( $post_status === 'publish' ){
                $last_id = $request->id;
                $slug = str_replace(' ', '-', strtolower($request->post_title));

                $up_args = array(
                    'post_media_id' => $request->post_media_id,
                    'post_author' => $ids,
                    'post_title' => $request->post_title,
                    'post_content' => $request->post_content,
                    'post_status' => $request->post_status,
                    'post_type' => $request->post_type,
                    'post_name' => $slug,
                    'guid' => '/' . $slug,
                );
                $update = JscPosts::where('id', $last_id)->update($up_args);

                $update_at = JscPosts::findOrFail($last_id)->first();

                $date = date('/Y/m/d/', strtotime($update_at['updated_at']));
                
                if ($update) {
                    $post = new JscPosts;
                    $post->post_parent = $last_id;
                    $post->post_media_id = $request->post_media_id;
                    $post->post_author = Auth::id();
                    $post->post_title = $request->post_title;
                    $post->post_content = $request->post_content;
                    $post->post_excerpt = '';
                    $post->post_status = 'inherit';
                    $post->post_type = 'revision';
                    $post->post_name = $last_id . '-revision-v' . $ids;
                    $post->guid = $date . $last_id . '-revision-v' . $ids;
                    $post->save();
                }
                $redirect = 1;

                return response()->json([ 'message' => 'success', 'status' => 'publish', 'redirect' => $redirect, 'id' => $last_id ]);
            }

        }else{
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JscPosts  $jscPosts
     * @return \Illuminate\Http\Response
     */
    public function show(JscPosts $jscPosts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JscPosts  $jscPosts
     * @return \Illuminate\Http\Response
     */
    public function edit(JscPosts $jscPosts, $id)
    {
        if (Gate::check('isAdmin')) {
            $last_id = $id;
            $media = JscMedia::get();
            $page = JscPosts::where('id', $last_id)->firstOrFail();
            $image = 0;
            if ($page->post_media_id != 0) {
                $image = JscMedia::where('id', $page->post_media_id)->firstOrFail();
            }
            // $image = JscMedia::find($page->post_media_id)->post()->get();
            return view('admin.posts.page-edit', compact('page', 'media', 'last_id', 'image'));
        }else{
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JscPosts  $jscPosts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JscPosts $jscPosts, $id)
    {
        if (Gate::check('isAdmin') && $request->ajax()) {
            $last_id = $id;
            $ids = Auth::id();
            $update_args = array(
                'post_media_id' => $request->post_media_id,
                'post_title' => $request->post_title,
                'post_content' => $request->post_content,
                'post_type' => $request->post_type
            );
            $post = $jscPosts::where('id', $last_id);
            $post_up = $post->update($update_args);

            if($post_up){
                $update_at = $post->firstOrFail();
                $date = date('/Y/m/d/', strtotime($update_at['updated_at']));

                $post = new JscPosts;
                $post->post_parent = $last_id;
                $post->post_media_id = $request->post_media_id;
                $post->post_author = Auth::id();
                $post->post_title = $request->post_title;
                $post->post_content = $request->post_content;
                $post->post_excerpt = '';
                $post->post_status = 'inherit';
                $post->post_type = 'revision';
                $post->post_name = $last_id . '-revision-v' . $ids;
                $post->guid = $date . $last_id . '-revision-v' . $ids;
                $post->save();
            }
        }else{
            return back();
        }
    }

    /**
     * Move the specific page/post to trash.
     */
    public function trash( Request $request, $actionstats ){
        if( Gate::check('isAdmin') && $request->ajax()){

            $trash_id = $request->o_id;
            $trash_action = $request->o_action;
            $trash_method = $request->o_method;
            $trash_status = $actionstats;
            $redirect_status = '0';
            $trash_time = Carbon::now()->timestamp;
            $ids = Auth::id();

            if( $trash_status == 'single' ){

                $trash_post = JscPosts::where( 'id', $trash_id );
                $tpp = $trash_post->firstOrFail(); 
                $trsh_pstatus = $tpp->post_status;
                $trsh_pslug = $tpp->post_name;
                $trsh_pid = $tpp->id;

                $pp_update = $trash_post->update([
                    'post_status' => 'trash', 'post_name' => $trsh_pslug . '__trashed' 
                ]);

                if($pp_update){

                    /**admin*/
                    if ( ! empty( $trsh_pstatus ) ) {
                        $ppst_meta = new JscPostsMeta;
                        $ppst_meta->post_id = $trsh_pid;
                        $ppst_meta->meta_key = '_jsc_edit_last';
                        $ppst_meta->meta_value = $ids;
                        $ppst_meta->save();
                    }

                    /**status*/
                    if ( ! empty( $trsh_pstatus ) ) {
                        $ppst_meta = new JscPostsMeta;
                        $ppst_meta->post_id = $trsh_pid;
                        $ppst_meta->meta_key = '_jsc_trash_meta_status';
                        $ppst_meta->meta_value = $trsh_pstatus;
                        $ppst_meta->save();
                    }

                    /**timestamp*/
                    if (! empty( $trash_time ) ) {
                        $ppst_meta = new JscPostsMeta;
                        $ppst_meta->post_id = $trsh_pid;
                        $ppst_meta->meta_key = '_jsc_trash_meta_time';
                        $ppst_meta->meta_value = $trash_time;
                        $ppst_meta->save();
                    }

                    /**slug*/
                    if (! empty( $trsh_pslug ) ) {
                        $ppst_meta = new JscPostsMeta;
                        $ppst_meta->post_id = $trsh_pid;
                        $ppst_meta->meta_key = '_jsc_trash_meta_slug';
                        $ppst_meta->meta_value = $trsh_pslug;
                        $ppst_meta->save();
                    }

                    /**Redirect the page*/
                    $redirect_url = url('/' . $request->o_redirected);

                    // if ($trash_type === 'posts') {

                        /**Redirect the page*/
                        /* if ($trash_view === 'editor') {
                            $redirect_url = url('/' . $request->o_redirected);
                        }else{
                            $redirect_url = '';
                        } */
                    // } 
                }

                $message = 'Successfully moved to trash';
                $redirect_status = '1';

            }else{
                $reqID = $request->o_id;
                $trash_id = explode(",", $reqID);
                $post = JscPosts::whereIn('id', $trash_id);
                $getPost = $post->get();

                foreach ($getPost as $value) {
                    $id = $value->id;
                    $post_name = $value->post_name;
                    $post_status = $value->post_status;
                    $updatedPost = JscPosts::where('id', $id)->update(['post_status' => 'trash','post_name' => $post_name . '__trashed']);

                    if( $updatedPost ){
                        /**admin*/
                        if ( ! empty( $post_status ) ) {
                            $ppst_meta = new JscPostsMeta;
                            $ppst_meta->post_id = $id;
                            $ppst_meta->meta_key = '_jsc_edit_last';
                            $ppst_meta->meta_value = $ids;
                            $ppst_meta->save();
                        }

                        /**status*/
                        if ( ! empty( $post_status ) ) {
                            $ppst_meta = new JscPostsMeta;
                            $ppst_meta->post_id = $id;
                            $ppst_meta->meta_key = '_jsc_trash_meta_status';
                            $ppst_meta->meta_value = $post_status;
                            $ppst_meta->save();
                        }

                        /**timestamp*/
                        if (! empty( $trash_time ) ) {
                            $ppst_meta = new JscPostsMeta;
                            $ppst_meta->post_id = $id;
                            $ppst_meta->meta_key = '_jsc_trash_meta_time';
                            $ppst_meta->meta_value = $trash_time;
                            $ppst_meta->save();
                        }

                        /**slug*/
                        if (! empty( $post_name ) ) {
                            $ppst_meta = new JscPostsMeta;
                            $ppst_meta->post_id = $id;
                            $ppst_meta->meta_key = '_jsc_trash_meta_slug';
                            $ppst_meta->meta_value = $post_name;
                            $ppst_meta->save();
                        }
                    }
                }
                $redirect_url = '';
                
            }

            $countAll = JscPosts::where('post_status', 'publish')->orWhere('post_status', 'draft')->count();
            $countPublish = JscPosts::where('post_status', 'publish')->count();
            $countDraft = JscPosts::where('post_status', 'draft')->count();
            $countTrash = JscPosts::where('post_status', 'trash')->count();

            return response()->json([
                'id' => $trash_id,
                'trash_status' => $trash_status,
                'redirect' => $redirect_status,
                'redirect_url' => $redirect_url,
                'countAll' => $countAll,
                'countPublish' => $countPublish,
                'countDraft' => $countDraft,
                'countTrash' => $countTrash,
                'message' => 'Successfully moved to trash'
            ]);
        }else{ return back(); }
    }

    public function restore( Request $request, $actionstats ){
        if( Gate::check('isAdmin') && $request->ajax()){

            $restore_action = $request->r_action;
            $restore_method = $request->r_method;
            $restore_status = $actionstats;
            $redirect_status = '0';
            $restore_time = Carbon::now()->timestamp;
            $ids = Auth::id();

            if( $actionstats === 'single' ){
                $restore_id = $request->r_id;
                $post = JscPosts::where('id', $restore_id);
                $post_item = $post->first();

                if($post_item){
                    $new_post_name = str_replace('__trashed', '', $post_item['post_name']);
                    $restore_slug = JscPostsMeta::where('post_id', $restore_id)->where('meta_key', '_jsc_trash_meta_slug')->first();
                    $restore_status = JscPostsMeta::where('post_id', $restore_id)->where('meta_key', '_jsc_trash_meta_status')->first();

                    $slug = $restore_slug->meta_value;
                    $status = $restore_status->meta_value;

                    if($slug == $new_post_name){
                        $update = $post->update(['post_name' => $slug, 'post_status' => $status]);
                        if($update){
                            $post_meta = new JscPostsMeta;
                            $post_meta->post_id = $restore_id;
                            $post_meta->meta_key = '_jsc_old_slug';
                            $post_meta->meta_value = $new_post_name;
                            $post_meta->save();
                        }
                    }
                }

                /**Redirect the page*/
                $redirect_url = url('/' . $request->r_redirected);

                $redirect_status = '1';
            }else{
                $message = 'multiple';
            }

            $countAll = JscPosts::where('post_status', 'publish')->orWhere('post_status', 'draft')->count();
            $countPublish = JscPosts::where('post_status', 'publish')->count();
            $countDraft = JscPosts::where('post_status', 'draft')->count();
            $countTrash = JscPosts::where('post_status', 'trash')->count();

            return response()->json([
                'id' => $restore_id,
                'restore_status' => $restore_status,
                'redirect' => $redirect_status,
                'redirect_url' => $redirect_url,
                'countAll' => $countAll,
                'countPublish' => $countPublish,
                'countDraft' => $countDraft,
                'countTrash' => $countTrash,
                'message' => 'Successfully Restore'
            ]);
        }else{ return back(); }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JscPosts  $jscPosts
     * @return \Illuminate\Http\Response
     */
    public function destroy(JscPosts $jscPosts, Request $request, $id)
    {
        if( Gate::check('isAdmin') && $request->ajax()){
            if( $request->menu_action == 'listMenuPage' ){

                JscMenus::where('id', $id)->delete();

                $redirect = ( $request->menu_method == 'deleteMenuPage' ) ? 1 : 0; 
                return response()->json(['status' => 'deleted','message' => 'Successfully Deleted', 'redirect' => $redirect, 'return_id' => $id]);
            }else{
                /**Delete the li nested item. */
                JscMenuItems::where('id', $id)->where('menu_id', $request->menu_parentID)->update(['menu_item_status' => 'trash']);
                return response()->json(['status' => 'success', 'message' => 'Successfully Deleted', 'menu_itemID' => $id, 'menu_parentID' => $request->menu_parentID]);
            }
            
        }else{ return back(); }
    }


    public function load_details(Request $request){
        if (Gate::check('isAdmin') && $request->ajax()) {

            $id = $request->id;

            $mediaDetails = JscMedia::findOrFail($id);
            $id = $mediaDetails->id;

            $views = view( 'admin.posts.pp-details', compact('mediaDetails'))->render();
            return response()->json([ 'data' => $views, 'data_id' => $id ]);
        }else{
            return back();
        }
    }

    public function searchPage(Request $request ){
        if (Gate::check('isAdmin') && $request->ajax()) {

            $query = $request->get('search');
            $filters = 'search';
            $request_date = '0';
            $post_status = $request->mode_status;

            $data = self::data($query, $post_status);

            $countAll = JscPosts::where('post_status', 'publish')->orWhere('post_status', 'draft')->count();
            $countPublish = JscPosts::where('post_status', 'publish')->count();
            $countDraft = JscPosts::where('post_status', 'draft')->count();
            $countTrash = JscPosts::where('post_status', 'trash')->count();

            $page = $data['data'];
            $count = $data['count'];

            $date = self::date();

            // $view = view('admin.posts.page_search_result', compact('pages', 'count', 'countAll', 'countPublish', 'countDraft', 'countTrash', 'post_status'))->render();
            
            $view = view('admin.posts.page_search_result', compact('page','count', 'query', 'filters', 'date', 'request_date'))->render();

            $return = array(
                'view' => $view,
                'post_status' => $post_status,
            );

            return response()->json($return);
            
        }else{
            return back();
        }
    }


    public function date(){
        if(Gate::check('isAdmin')){

            $args = collect(['all' => 'All Dates']);

            $date = JscPosts::selectRaw('year(created_at) year, monthName(created_at) month, count(9) data')->groupBy('year','month')->where('post_type', 'page')->orderBy('year','desc')->get();

            $concatenated = $args->concat($date);

            return $concatenated->all();

        }else{
            return back();
        }
    }

    public function filter(Request $request){
        if( Gate::check('isAdmin') && $request->ajax() ){
            $post_status = $request->status;
            $nReq = $request->date;
            $filters = 'filter';
            $query = '';
            $data = self::filterData($nReq, $post_status);
            $page = $data['data'];
            $count = $data['count'];

            $countAll = JscPosts::where('post_status', 'publish')->orWhere('post_status', 'draft')->count();
            $countPublish = JscPosts::where('post_status', 'publish')->count();
            $countDraft = JscPosts::where('post_status', 'draft')->count();
            $countTrash = JscPosts::where('post_status', 'trash')->count();
            
            $date = self::date();
            $request_date = $nReq;
            // dd($request_date);

            $view = view('admin.posts.page_search_result', compact('page','count', 'query', 'filters', 'date', 'request_date'))->render();

            $return = array(
                'view' => $view,
                'post_status' => $post_status,
            );

            return response()->json($return);
        }else{
            return back();
        }
    }

    public function filterData( $date, $status ){
        if( Gate::check('isAdmin') ){
            
            $post_status = $status;
            $newDate = '';
            $count = '';

            $queryDate = self::date();
            foreach ($queryDate as $qkey => $qval) {
                if( $date !== 'all' && $qkey == $date ){
                    $newDate = date("Y-m", strtotime($qval['month'] . $qval['year']));
                }
            }
            // dd($newDate);

            if ( $date != 'all' ) {
                if( $post_status == 'all'){
                    $datas = DB::table('user_roles')
                            ->join('jsc_posts', function($join) use ($post_status){
                                $join->on('user_roles.id', '=', 'jsc_posts.post_author')
                                ->where('jsc_posts.post_status', 'publish')
                                ->orWhere('jsc_posts.post_status', 'draft');
                            })->where('jsc_posts.created_at', 'like', '%' . $newDate . '%');
                }else{
                    $datas = DB::table('user_roles')
                            ->join('jsc_posts', function($join) use ($post_status){
                                $join->on('user_roles.id', '=', 'jsc_posts.post_author')
                                ->where('jsc_posts.post_status', $post_status);
                            })->where('jsc_posts.created_at', 'like', '%' . $newDate . '%')->orderBy('jsc_posts.id','desc');
                }
                // $datas = JscPosts::where('created_at', 'like', '%' . $newDate . '%')->orderBy('id','desc');
            }else{
                if( $post_status == 'all'){
                    $datas = DB::table('user_roles')
                    ->join('jsc_posts', function($join){
                        $join->on('user_roles.id', '=', 'jsc_posts.post_author')
                        ->where('jsc_posts.post_status', 'publish')->orWhere('jsc_posts.post_status', 'draft');
                    })->orderBy('jsc_posts.id','desc');
                }else{
                    $datas = DB::table('user_roles')
                        ->join('jsc_posts', function($join) use ($post_status){
                            $join->on('user_roles.id', '=', 'jsc_posts.post_author')
                            ->where('jsc_posts.post_status', $post_status);
                        })->orderBy('jsc_posts.id','desc');
                }
                // $datas = JscPosts::orderBy('id','desc');
            }

            $count = $datas->count();
            $data = $datas->paginate(10)->appends(['post_status' => $post_status, 'date' => $date]);

            return array('data' => $data, 'count' => $count);

        }else{
            return back();
        }
    }

    public function data($search, $status){
        if (Gate::check('isAdmin')) {
            $post_status = $status;

            if( $search != ''){
                if( $status == 'all'){
                    $datas = DB::table('user_roles')
                            ->join('jsc_posts', function($join) use ($post_status){
                                $join->on('user_roles.id', '=', 'jsc_posts.post_author')
                                ->where('jsc_posts.post_status', 'publish')
                                ->orWhere('jsc_posts.post_status', 'draft');
                            })->where('jsc_posts.post_title', 'like', '%' . $search . '%');
                }else{
                    $datas = DB::table('user_roles')
                            ->join('jsc_posts', function($join) use ($post_status){
                                $join->on('user_roles.id', '=', 'jsc_posts.post_author')
                                ->where('jsc_posts.post_status', $post_status);
                            })->where('jsc_posts.post_title', 'like', '%' . $search . '%');
                }
                // $count = $datas->count();
                // $data = $datas->paginate(10)->appends(['post_status' => $post_status, 'search' => $search]);
                // $data =  ( $post_status !== 'all') ? $datas->paginate(10)->appends(['post_status' => $post_status, 'search' => $search]) : $datas->get();
            }else{
                if( $status == 'all'){
                    $datas = DB::table('user_roles')
                    ->join('jsc_posts', function($join){
                        $join->on('user_roles.id', '=', 'jsc_posts.post_author')
                        ->where('post_status', 'publish')->orWhere('post_status', 'draft')->orderBy('id','desc');
                    });
                }else{
                    $datas = DB::table('user_roles')
                        ->join('jsc_posts', function($join) use ($post_status){
                            $join->on('user_roles.id', '=', 'jsc_posts.post_author')
                            ->where('post_status', $post_status)->orderBy('id','desc');
                        });
                }
                // $data =  ( $post_status !== 'all') ? $datas->paginate(10)->appends(['post_status' => $post_status, 'search' => $search]) : $datas->get();
            }
            
            $count = $datas->count();
            $data = $datas->paginate(10)->appends(['post_status' => $post_status, 'search' => $search]);

            // return $data;
            return array( 'data' => $data, 'count' => $count );
        }else{
            return back();
        }
    }

 
}
