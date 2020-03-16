@extends('layouts.app')
@section('active_page_new','active')
@section('page-heading')
	<i class="fas fa-copy"></i> New Pages
@endsection
@section('content')
  <div class="card mb-5">
    <div class="card-body">
      <div class="row">
        <div class="col-12 col-lg-9 mb-4 mb-lg-0">
          {{--  <div class="page-heading d-flex justify-content-between align-items-center">
            <h3 class="page-title">Page</h3>
          </div>  --}}
          <div class="page-content">
            <div class="form-group">
              <input type="text" name="postTitle" id="postTitle" class="form-control" placeholder="Title" value="@yield('title')">
            </div>
            <textarea class="jscEditor" name="jscEditor">@yield('page-content')</textarea>
          </div>
        </div>
        <div class="col-12 col-lg-3">
          <div class="card">
            <div class="card-header">
              <div class="page-heading d-flex justify-content-between align-items-center mb-0">
                <h3 class="page-title">Page Settings</h3>
                @if (Route::currentRouteName() == 'page.edit')
                  @yield('btn-publish')
                @else
                  <button class="btn btn-outline-info btn-sm jsc-post-publish-button" data-last_id="{{ $last_id }}">Publish</button>
                @endif
              </div>
            </div>
            <div class="card-body p-0">
              <div class="accordion" id="accordionExample">
                <div class="jsc-page-settings">
                  <div id="headingStatus" class="jsc-page-heading-wrap">
                    <h2 class="m-0">
                      <button class="btn btn-link"data-toggle="collapse" data-target="#status" aria-expanded="true" aria-controls="status">
                        Status
                      </button>
                    </h2>
                  </div>
                  <div id="status" class="collapse show" aria-labelledby="headingStatus" data-parent="#accordionExample">
                    <div class="jsc-page-content-wrap">
                      <div class="form-group d-flex justify-content-between">
                        <span>Publish</span>
                        <span><i class="far fa-calendar-alt"></i>
                          @if (Route::currentRouteName() == 'page.edit')
                            @yield('date')
                          @else
                            {{ date('m/d/Y') }}
                          @endif
                        </span>
                      </div>
                      <div class="form-group d-flex justify-content-between">
                        <span>Status</span>
                        @if (Route::currentRouteName() == 'page.edit')
                          @yield('status')
                        @else
                          <span class="text-warning">Public</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                <div class="jsc-page-settings">
                  <div id="headingFeaturedImg" class="jsc-page-heading-wrap">
                    <h2 class="m-0">
                      <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#featuredImg" aria-expanded="false" aria-controls="featuredImg">
                        Featured Image
                      </button>
                    </h2>
                  </div>
                  <div id="featuredImg" class="collapse" aria-labelledby="headingFeaturedImg" data-parent="#accordionExample">
                    <div class="jsc-page-content-wrap">
                      <div class="jsc-editor-post-featured-img">
                        @if (Route::currentRouteName() == 'page.edit')
                          @yield('featured-image')
                        @else
                          <button type="button" class="btn btn-block text-center btn-editor-post-featured_img btn-edit-post-img"  data-toggle="modal" data-target="#featImgModal" data-img="0">Add Featured Image here.</button>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                <div class="jsc-page-settings">
                  <div id="headingPageAttr" class="jsc-page-heading-wrap">
                    <h2 class="m-0">
                      <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#pageAttr" aria-expanded="false" aria-controls="pageAttr">
                        Page Attribute
                      </button>
                    </h2>
                  </div>
                  <div id="pageAttr" class="collapse" aria-labelledby="headingPageAttr" data-parent="#accordionExample">
                    <div class="jsc-page-content-wrap">
                      <div class="form-row">
                        <label for="" class="col-sm-3">template</label>
                        <div class="col-sm-9">
                          @if (Route::currentRouteName() == 'page.edit')
                            @yield('page-type')
                          @else
                            <select name="page-type" id="pageType" class="form-control">
                              <option value="none">Default Template</option>
                              <option value="front-page">Front Page</option>
                            </select>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <a href="@#$" class="btn btn-outline-danger btn-sm">Move to trash</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('admin.posts.pp-modal');
@endsection

@section('script')
  <script src="{{ asset('node_modules/tinymce/tinymce.js') }}"></script>
  <script type="application/javascript">
    $(document).ready(function($){
      tinymce.init({
          selector: 'textarea.jscEditor',
          height: 550,
          menubar: false,
          branding: false,
          plugins: [
              'advlist autolink lists link image charmap print preview anchor textcolor',
              'searchreplace visualblocks code fullscreen',
              'insertdatetime media table contextmenu paste code imagetools help wordcount'
          ],
          toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image| removeformat | help',
      });

      /**for selecting the image*/
      $('.thumbnail').on('click', function(e){
        e.preventDefault();
        $('.thumbnail').parent().parent().removeClass('selected').attr('aria-checked', 'false');

        $(this).parent().parent().addClass('selected').attr('aria-checked', 'true');
        
        if( $('.attachment-list').hasClass('selected') ){
          let id = $(this).parent().parent().data('id');
          let url = '/page/load-details/' + id;
          let data = {
            '_token' : $('input[name="_token"]').val(),
            'id' : id,
          }
          $.ajax({
            type: 'POST',
            url: url,
            data: data,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: 'JSON',
            success:function(res){
              if( res.data.length > 0 ){

                $('.media-sidebar').html(res.data);
                
                //Calling the input/textarea onchange
                mediaModal(res.data_id, '#featImgModal');
                
                //Button
                $('.jsc-media-select').prop('disabled', false);
                
              }
            }
          });
        }
      });

      $(document).on('click','.btn-jsc-minus', function(e){
        e.preventDefault();
        $('.attachment-list').removeClass('selected').attr('aria-checked', 'false');
        $('.media-sidebar').html('');
        $('.jsc-media-select').prop('disabled', true);
      });

      /**button insert image*/
      $(document).on('click', '.jsc-media-select',function(e){
        e.preventDefault();
        let featImg = '#featuredImg .jsc-editor-post-featured-img';
        let img = $('.attachment-list.selected').find('img').attr('src');
        let id = $('.attachment-list.selected').data('id');

        let newApImg = '<button type="button" class="btn btn-block text-center btn-editor-post-featured_img_preview btn-edit-post-img" data-toggle="modal" data-target="#featImgModal" data-img="' + id + '">';
          newApImg += '<span><img src="' + img + '" class="components-responsive-wrapper__content"></span>';
        newApImg += '</button>';
        newApImg += '<button type="button" class="btn btn-link text-danger is-destructive">Remove featured image</button>';
        $(featImg).html(newApImg);
        
        $('#featImgModal').modal('hide');
        $('.attachment-list').removeClass('selected').attr('aria-checked', 'false');
        $('.media-sidebar').html('');
        $('.jsc-media-select').prop('disabled', true);
      });

      /**Replace featured Img to Text*/
      $(document).on('click', '.is-destructive', function(e){
        e.preventDefault();
        $('#featuredImg .jsc-editor-post-featured-img').html('<button type="button" class="btn btn-block text-center btn-editor-post-featured_img btn-edit-post-img"  data-toggle="modal" data-target="#featImgModal" data-img="0">Add Featured Image here.</button>');
      });
      
    });
    const mediaModal = (id, thumb) => {
      /**Edit Image through modal*/

      $(thumb + ' input[type="text"], textarea').on('change', function(){
        let _token = $('input[name="_token"]').val();
        let alt = $('input#attachment-details-alt-text-' + id ).val();
        let title = $('input#attachment-details-title-' + id ).val();
        let caption = $('textarea#attachment-details-caption-' + id ).val();
        let desc = $('textarea#attachment-details-description-' + id ).val();
        let url = '/media/' + id;
        
        let data = {
          '_token': _token,
          '_method': 'PUT',          
          'imgMethod': 'modal-edit',
          'attach_id': id,
          'attach_image_alt': alt,
          'attach_org_filename': title,
          'attach_excerpt': caption,
          'attach_content': desc,
        }
        $.ajax({
          type: 'POST',
          url: url,
          data: data,
          dataType: 'JSON',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          beforeSend: function(){
            //Show loader
            $('.media-sidebar .attachment-details h2 .settings-save-status').html('<i class="fas fa-spinner fa-pulse"></i>');
          },
          complete: function(){
            //Hide Loader
            $('.media-sidebar .attachment-details h2 .settings-save-status .fa-spinner').remove();
          },
          success: function(res){
            if(res.status === 'success'){
              console.log(res.message);
              $('.media-sidebar .attachment-details h2 .settings-save-status').addClass('text-success').append(res.message);
              setTimeout(function(){
                $('.media-sidebar .attachment-details h2 .settings-save-status').fadeOut();
              }, 2000);
            }
          }
        });
      });
    }

    const pageProcess = (id, url, status) => {
      $(document).on('click', id, function(e){
        e.preventDefault();
        let $_token = $('input[name="_token"]').val();
        let $title = $('#postTitle').val();
        let $content = tinymce.activeEditor.getContent();
        let $id = $(this).data('last_id');
        let $imgID = $('.btn-edit-post-img').data('img');
        let pageType = ( $('#pageType').val() === 'none' ) ?  'page': $('#pageType').val();
        let $url = url;
        let $method = 'POST';
        if(status == 'edit'){
          $method = 'PUT';
        }
        let $data = {
          '_token': $_token,
          '_method': $method,
          'id': $id,
          'post_title': $title,
          'post_content': $content,
          'post_media_id': $imgID,
          'post_type': pageType,
          'post_status': 'publish',
        }        
        $.ajax({
          type: 'POST',
          url: $url,
          data: $data,
          dataType: 'JSON',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
          success:function(res){
            if( res.redirect === 1 ){
              window.location.href = '/page/' + res.id + '/edit';
            }
          }
        });
      });
    }

    let route = '{{Route::currentRouteName()}}';
    if( route !== 'page.edit'){
      /**Save*/
      pageProcess('.jsc-post-publish-button', '/page/store', 'publish');
    }else{
      @yield('edit-script')
    }
    
  </script>

@endsection
