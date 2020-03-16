@extends('layouts.app')
@section('active_media','active')
@section('page-heading')
	<i class="fas fa-photo-video"></i> Edit Media <a href="/media/media-new" class="btn btn-outline-info btn-sm">Add New</a>
@endsection
@section('content')
<div class="media-edit" data-id="{{$media->id}}" data-imgmethod="page-edit" data-mode="page" role="form">
  {{--  @csrf  --}}
  <div class="row">
    @if ( $media->count() > 0 )
      <div class="col-12 col-lg-9">
        <div id="attach_title" class="form-group">
          <input type="text" name="attach_org_filename" id="attach_org_filename" class="form-control" value="{{ $media->attach_org_filename }}">

        </div>
        <div class="form-group">
          <label>Primary link:</label>
          <a href="{{ asset($media->attach_url) }}" class="text-primary">{{ asset($media->attach_url) }}</a>
        </div>
        <div class="form-group">
          <img src="{{ asset($media->attach_url) }}" class="img-fluid" alt="">
        </div>
        <div class="form-group">
          <button id="editImgSize" class="btn btn-outline-primary">Edit Image</button>
        </div>
        <div class="edit_section">
          <div class="form-group">
            <label for="alternative_text">Alternative Text</label>
            <input type="text" name="alternative_text" id="alternative_text" class="form-control" value="{{ $media->attach_image_alt }}">
          </div>
          <div class="form-group">
            <label for="caption">Caption</label>
            <textarea name="caption" id="caption" class="form-control">{{ $media->attach_excerpt }}</textarea>
          </div>
          <div class="form-group">
            <label for="desc">Description</label>
            <textarea name="desc" id="desc" class="form-control">{{ $media->attach_content }}</textarea>
          </div>        
        </div>
      </div>
      <div class="col-12 col-lg-3">
        <div class="card">
          <div class="card-header">
            <p class="text-secondary mb-0">Save</p>
          </div>
          <div class="card-body">
              @php
                $bytes = filesize($media->attach_url);
                if( $bytes >= 1024 ):
                  $bytes = number_format($bytes/1024, 2) . 'KB';
                elseif( $bytes >= 1048576 ):
                  $bytes = number_format($bytes/1048576, 2) . 'MB';
                else:
                  $bytes = '';
                endif;
                list($width, $height) = getimagesize($media->attach_url);
              @endphp
              <div class="misc-pub-section misc-pub-curtime">
                <i class="far fa-calendar-alt"></i> Uploaded on: <strong>{{$media->created_at->format('M d, Y @ H:i:s')}}</strong>
              </div>
              <div class="misc-pub-section misc-pub-attachment">
                <label for="attach_url">File Url:</label>
                <input type="text" name="attach_url" id="attach_url" class="form-control" value="{{ asset($media->attach_url) }}" readonly>
              </div>
              <div class="misc-pub-section misc-pub-filename">
                File name:
                <strong>{{ $media->attach_filename }}</strong>
              </div>
              <div class="misc-pub-section misc-pub-filetype">
                File type:
                <strong>{{ $media->attach_mime_type }}</strong>
              </div>
              <div class="misc-pub-section misc-pub-filesize">
                File size:
                <strong>{{ $bytes }}</strong>
              </div>
              <div class="misc-pub-section misc-pub-dimensions">
                Dimensions:
                <strong>{{ $width .' x '. $height }}</strong>
              </div>
          </div>
          <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('media.destroy', ['id'=> $media->id]) }}" onclick="return false;" class="text-danger" id="editDeleteImg">Delete Permanently</a>
            <button class="btn btn-primary" id="editImgUpdate">Update</button>
          </div>
        </div>      
      </div>
    @endif
  </div> 
</div>


@endsection

@section('script')
    <script type="application/javascript">

      /**Edit Image Size*/
      $(document).on('click', '#editImgSize', function(e){
        customTooltips('warning', '<i class="fas fa-exclamation-triangle"></i> Under Construction.');
      });

      /**Update Image*/
      $(document).on('click', '#editImgUpdate', function(e){
        e.preventDefault();
        let id =  $('.media-edit').data('id');
        let url = '/media/' + id;
        let data = {
          '_token': $('input[name="_token"]').val(),
          '_method': 'PUT',
          'attach_id': id,
          'imgMethod': $('.media-edit').data('imgmethod'),
          'attach_org_filename': $('input[name="attach_org_filename"]').val(),
          'attach_image_alt': $.trim($('input[name="alternative_text"]').val()),
          'attach_excerpt': $.trim($('#caption').val()),
          'attach_content': $.trim($('#desc').val()),
        }
        $.ajax({
          type: 'POST',
          url: url,
          data: data,
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          dataType: 'JSON',
          beforeSend: function(){
						//Show loader
						$('body').addClass('overflow-y-none');
						$('.loader-wrap').show();
          },
					complete: function(){
						//Hide loader
						$('body').removeClass('overflow-y-none');
						$('.loader-wrap').hide();
          },
          success: function(res){
            
            if(! $.isEmptyObject(res.errors) ){
              errorMessages(res.errors);
            }else{
              customTooltips('success', res.message);
              let title = $('#attach_title');
              title.find('#attach_org_filename').removeClass('is-invalid');
              title.find('.has-error').remove();
            }
          }
        });
      });

      /**Update Error Message Image*/
      const errorMessages = (error) => {
        $.each(error, function(key, value){
          let title = $('#attach_title');
          title.find('#attach_org_filename').addClass('is-invalid');
          title.append('<span class="has-error">' + value + '</span>');
        });
      }

      /**Single Delete Image*/
      $(document).on('click', '#editDeleteImg',  function(e){
        let media = $('.media-edit');
        let id = media.data('id');
        let mode = media.data('mode');
        let url = '/media/' + id + '/delete';
        let data = {
          '_token': $('input[name="_token"]').val(),
          '_method': 'DELETE',
          'id': id,
          'mode': mode
        }

        $.ajax({
          type: 'POST',
          url: url,
          data: data,
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          dataType: 'JSON',
          beforeSend: function(){
						//Show loader
						$('body').addClass('overflow-y-none');
						$('.loader-wrap').show();
          },
					complete: function(){
						//Hide loader
						$('body').removeClass('overflow-y-none');
						$('.loader-wrap').hide();
          },
          success: function(res){

            if(res.status === 'success'){
              
              customTooltips(res.status, res.message);

              setTimeout(function(){
                window.location.href = '/media';
              }, 1000);
            }

          }
        });
        
      });


    </script>
@endsection