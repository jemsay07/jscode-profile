<?php

namespace App\Http\Controllers;

use App\JscMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

use App\User;
use Auth;
use URL;
use Validator;
use Helper;

class JscMediaController extends Controller
{
    /**
     * Authentication
     */
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        if (Gate::check('isAdmin')) {
            $count = '';
            $mode = app('request')->input('mode');
            $media = JscMedia::orderBy('id','desc')->get();

            /**Mime Type */
            $mime = self::mime();

            /**Date */
            $date = self::date();

            if( $mode === 'list' ){
                $count = JscMedia::count();
                $media = JscMedia::orderBy('id','desc')->paginate(10);
                $media->withPath('?mode=list');
            }

            return view('admin.media.index', compact('media', 'count', 'mime', 'date'));
        }else{
            return back();
        }
    }

    public function upload(){
        return view('admin.media.upload');
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
            //checking if it's a file.
            if ($request->ajax() && $request->hasFile('file')) {

                $validation = Validator::make( $request->all(),self::media_rules(),self::media_message());

                if ( $validation->fails() ) {
                    return response()->json(['message'=> 'Check your file.','errors' => $validation->getMessageBag()->toArray()]);
                }else{

                    $fileImg = new JscMedia();

                    //Set Variables
                    $fileArgs = array();
                    $fileUrlArgs = array();
                    $fileExtensionArgs = array('jpg', 'jpeg', 'png', 'gif');
                    $year = date('Y');
                    $month = date('m');
                    $filePath = 'upload' . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . $month;
                    // $filePath = 'public/upload/2020/01';

                    foreach( $request->files as $file ){
                        
                        $fileNameWithExtension = $file->getClientOriginalName();
                        $fileName = pathinfo( $fileNameWithExtension, PATHINFO_FILENAME );
                        $fileSize = $file->getClientSize();
                        $fileExtension = $file->getClientOriginalExtension();
                        $fileMimiType = $file->getClientMimeType();
                        $file->getClientOriginalExtension();
                        $newFileName = $fileName . '.' . $fileExtension;
                        $fileCheckExtensions = in_array($fileExtension, $fileExtensionArgs);

                        //Check mo muna ung extensions
                        if ( $fileCheckExtensions ) {

                            //Remove the whitespace and replace with dash.
                            $fileImgToStore = str_replace( ' ', '-', $newFileName);

                            $path = public_path('storage' . DIRECTORY_SEPARATOR . $filePath . DIRECTORY_SEPARATOR . $fileImgToStore);

                            // Check directory if folder exists then make one if not.
                            if(!Storage::exists( 'public' . DIRECTORY_SEPARATOR . $filePath)){
                                Storage::makeDirectory( 'public' . DIRECTORY_SEPARATOR . $filePath);
                            }

                            //Check file if exist.
                            if(File::exists($path)){

                                //split filename info parts
                                $pathInfo = pathinfo($path);
                                $extension = isset($pathInfo['extension']) ? ('.' . $pathInfo['extension']) : '';
                                $pathInfoFilename = $pathInfo['filename'].$extension;

                                //Call the helper function to add the number and react a new name.
                                $newFileName = Helper::file_newname( $pathInfo['dirname'] , $pathInfoFilename);

                                //Remove the whitespace and replace with dash.
                                $fileImgToStore = str_replace( ' ', '-', $newFileName);

                                //Storage path
                                $path = public_path('storage' . DIRECTORY_SEPARATOR . $filePath . DIRECTORY_SEPARATOR . $fileImgToStore);
                            }

                            // $attachURL = 'storage' . DIRECTORY_SEPARATOR . $filePath . DIRECTORY_SEPARATOR . $fileImgToStore;

                            $attachURL = 'storage/upload/' . $year . '/' . $month . '/' . $fileImgToStore;

                            $mediaAll = JscMedia::where('attach_filename', $newFileName)->first();

                            //Store the file in DB
                            if( $mediaAll == null ){
                                $fileImg->attach_org_filename = $fileName;
                                $fileImg->attach_filename = $fileImgToStore;
                                $fileImg->attach_content = '';
                                $fileImg->attach_excerpt = '';
                                $fileImg->attach_extension = $fileExtension;
                                $fileImg->attach_mime_type = $fileMimiType;
                                $fileImg->attach_image_size = $fileSize;
                                $fileImg->attach_image_alt = '';
                                $fileImg->attach_url = $attachURL;
                                $fileImg->attachment_metadata = '';
                                $fileImg->save();
                            }

                            //Store the file in public folder.
                            $request->file('file')->storeAs( 'public' . DIRECTORY_SEPARATOR . $filePath, $fileImgToStore);   
                        }
                    }
                    $fileUrl = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $filePath . DIRECTORY_SEPARATOR . $fileImgToStore;
                    $fileArgs[] = [$fileImgToStore, $fileUrl];
                }
                return response()->json([ 'status'=> 'success', 'files' => $fileArgs], 200);
            }else{
                return response()->json(['status' => 'error', 'message' => 'Kindly check the file type before you upload it again. ']);
            }
        }else{
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JscMedia  $jscMedia
     * @return \Illuminate\Http\Response
     */
    public function show(JscMedia $jscMedia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JscMedia  $jscMedia
     * @return \Illuminate\Http\Response
     */
    public function edit(JscMedia $jscMedia, $id)
    {
        if (Gate::check('isAdmin')) {
            $media = JscMedia::where('id', $id)->firstOrFail();
            return view('admin.media.media_edit', compact('media'));
        }else{
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JscMedia  $jscMedia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JscMedia $jscMedia)
    {
        if (Gate::check('isAdmin')) {
            if ($request->ajax()) {

                $message = 'saved.';

                //Check what method used.
                if( $request->imgMethod === 'page-edit'){

                    $validation = Validator::make($request->all(),[ 'attach_org_filename' => 'required'],['attach_org_filename.required' => 'This field is cannot be empty.']);

                    if ($validation->fails()) {
                        return response()->json(['message'=> '','errors' => $validation->getMessageBag()->toArray()]);
                    }
                    $media_update = [
                        'attach_org_filename' => $request->attach_org_filename,
                        'attach_content' => $request->attach_content,
                        'attach_excerpt' => $request->attach_excerpt,
                        'attach_image_alt' => $request->attach_image_alt,
                    ];

                    $message = 'page edit saved.';
                }

                $media_update = [
                    'attach_org_filename' => $request->attach_org_filename,
                    'attach_content' => $request->attach_content,
                    'attach_excerpt' => $request->attach_excerpt,
                    'attach_image_alt' => $request->attach_image_alt,
                ];
                jscMedia::where('id', $request->attach_id)->update($media_update);

                return response()->json([ 'status' => 'success', 'message' => $message ]);
            }
        }else{
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JscMedia  $jscMedia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (Gate::check('isAdmin')) {
            if ( $request->ajax() ) {
                
                $imgCheck = JscMedia::findOrFail($request->id);
                
                $path = str_replace('storage/upload', '', $imgCheck->attach_url);

                // $mode = 'list';
                // $message = 'Delete successfully';
                // $status = 'success';
                
                if ( Storage::disk('public')->exists('upload' . $path) ) {
                    
                    Storage::disk('public')->delete('upload' . $path);

                    $imgCheck->delete();

                    $mode = $request->mode;

                    $message = 'Delete successfully.';
                    $status = 'success';
                }else{
                    $mode = '';
                    $message = 'Check the file path';
                    $status = 'danger';
                }

                return response()->json([ 'status' => $status, 'message' => $message, 'id' => $request->id, 'mode' => $mode ]);
            }
        }else{
            return back();
        }
    }

    /**
     * For Multiple Delete.
     */
    public function multipleDelete(Request $request){
        if ( Gate::check('isAdmin') && $request->ajax() ) {
            $itemId = $request->id;
            $explodeId = explode(',',$itemId);

            $media = JscMedia::whereIn('id', $explodeId);
            $getMedia = $media->get();

            foreach ($getMedia as $mediaItem) {
                $path = str_replace('storage/upload', '', $mediaItem->attach_url);
                if ( Storage::disk('public')->exists('upload' . $path) ) {
                    Storage::disk('public')->delete('upload' . $path);
                }
            }

            $media->delete();
            
            $id = $explodeId;
            $message = 'Delete successfully';
            $status = 'success';

            return response()->json(['message' => $message, 'status' => $status, 'id' => $id]);

        }else{
            return back();
        }
    }

    public function searchMedia( Request $request ){
         if ( Gate::check('isAdmin') && $request->ajax() ) {
             
            $query = $request->get('search');
            $mode = $request->mode;
            $filters = 'search';
            $counts = '';
            if ($mode === 'list') {
                $counts = JscMedia::where('attach_org_filename', 'like', '%' . $query . '%')
                        ->orWhere('attach_filename', 'like', '%' . $query . '%')
                        ->orWhere('attach_extension', 'like', '%' . $query . '%')->count();
            }

            $data = self::data($query, $mode);

            $view = view('admin.media.media_result_list', compact('data','mode','counts', 'query', 'filters'))->render();
            
            $return = array(
                'view' => $view,
                'mode' => $mode,
            );

            return response()->json($return);
            
        }else{
            return back();
        }
    }

    public function filter( Request $request ){
        if ( Gate::check('isAdmin') && $request->ajax() ) {

            $mode = $request->mode;
            $ext = $request->extension;
            $date = $request->date;
            $filters = 'filters';
            $query = '';

            $datas = self::filterData( $date, $ext, $mode );
            // $datas['data']->withPath('&search=filter');
            $data = $datas['data'];
            $counts = $datas['counts'];
            
            $view = view('admin.media.media_result_list', compact('data','mode','counts', 'query', 'filters'))->render();
            
            $return = array( 'view' => $view, 'mode' => $request->mode );
            
            return response()->json($return);
            
        }else {
            return back();
        }
    }

    public function paginations(Request $request){

        if ( Gate::check('isAdmin') && $request->ajax() ) {

            $mode = $request->mode;
            $counts = '';
            
            if( $request->queu === 'filter' ){
                $filters = 'filters';
                $date = $request->date;
                $ext = $request->ext;
                $query = '';

                $datas = self::filterData($request->date, $request->ext, $mode);
                $counts = $datas['counts'];
                $data = $datas['data'];

            }else{
                $filters = '';
                $query = $request->search;
                if ($mode === 'list') {
                    $counts = JscMedia::where('attach_org_filename', 'like', '%' . $query . '%')
                            ->orWhere('attach_filename', 'like', '%' . $query . '%')
                            ->orWhere('attach_extension', 'like', '%' . $query . '%')->count();
                }

                $data = self::data($request->search, $request->mode);                
            }

            $view = view('admin.media.media_result_list', compact('data','mode','counts', 'query', 'filters'))->render();
            
            $return = array( 'view' => $view, 'mode' => $request->mode );
            
            return response()->json($return);
        }

    }

    /**
     * Query for getting the date
     * 
     * @return array
     */
    public function date(){
        if ( Gate::check('isAdmin') ) {
            /**Date */
            $date = JscMedia::selectRaw('year(created_at) year, monthName(created_at) month, count(9) data')->groupBy('year','month')->orderBy('year','desc')->get();

            return $date;
        }else {
            return back();
        }
    }

    /**
     * Query for getting the file extension
     * 
     * @return array
     */
    public function mime(){
        if ( Gate::check('isAdmin') ) {
            /**Mime Type */
            $mime = JscMedia::select('attach_extension')->whereIn('attach_extension', ['jpg','jpeg', 'png', 'gif'])->whereNotNull('attach_extension')->groupBy('attach_extension')->get();

            return $mime;
        }else {
            return back();
        }
    }

    public function data($search, $mode){

        if ( Gate::check('isAdmin') ) {

            if ( $search != '' ) {
                $datas = JscMedia::where('attach_org_filename', 'like', '%' . $search . '%')
                        ->orWhere('attach_filename', 'like', '%' . $search . '%')
                        ->orWhere('attach_extension', 'like', '%' . $search . '%')
                        ->orderBy('id','desc');
                
                $data = ($mode === 'list') ? $datas->paginate(10)->appends(['mode' => $mode, 'search' => $search]) : $datas->get();

            }else{
                $datas = JscMedia::orderBy('id','desc');
                $data = ($mode === 'list') ? $datas->paginate(10)->appends(['mode' => $mode, 'search' => $search]) : $datas->get();
            }

            return $data;

        }else {
            return back();
        }
    }

    public function filterData($date, $ext, $mode){
        if ( Gate::check('isAdmin') ) {
            $newDate = '';
            $counts = '';

            $queryDate = self::date();
            foreach ($queryDate as $key => $value) {
                if($key == $date){
                    $newDate = date("Y-m", strtotime($value['month'] . $value['year']));
                }
            }

            if($date != 'all' && $ext == 'all'){
                $datas = JscMedia::where('created_at', 'like', '%' . $newDate . '%')->orderBy('id','desc');
            }else if($date == 'all' && $ext != 'all'){
                $datas = JscMedia::where('attach_extension', 'like', '%' . $ext . '%')->orderBy('id','desc');
            }else if($date !== 'all' && $ext !== 'all'){
                $datas = JscMedia::where('attach_extension', 'like', '%' . $ext . '%')
                ->where('created_at', 'like', '%' . $newDate . '%')
                ->orderBy('id','desc');
            }else{
                $datas = JscMedia::orderBy('id','desc');
            }

            if( $mode === 'list' ){
                $counts = $datas->count();
            }

            $data = ($mode === 'list') ? $datas->paginate(10)->appends(['mode' => $mode, 'date' => $date, 'extension' => $ext]) : $datas->get();
            

            return array('data' => $data, 'counts' => $counts);
        }else {
            return back();
        }
    }

    public function media_rules(){

        return [
            'file' => 'required|image|mimes:jpeg,jpg,gif,png|max:51200'
        ];

        // $photos = count($this->input('file'));

        // foreach(range(0, $photos) as $index) {
        //     $rules['file.' . $index] = 'image|mimes:jpeg,jpg,gif,png|max:51200';
        // }

        // return $rules;
    }

    public function media_message(){
        return [
            'file.required' => 'You must Select your image before upload.',
            'file.image' => 'The file must be an image.',
            'file.mimes' => 'The files that allowed are jpeg,png,jpg & gif.',
            'file.max' => 'Check the max size of your image or file.',
        ];
    }
}
