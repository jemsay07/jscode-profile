@extends('layouts.app')
@section('active_media','active')
@section('page-heading')
	<i class="fas fa-photo-video"></i> Media <a href="/media/media-new" class="btn btn-outline-info btn-sm">Add New</a>
@endsection
@section('content')
  @if (app('request')->input('mode') == 'list')
    @if ( count( $media ) > 0 )
      <div class="card mb-5">
        <div class="card-body jsc-table">
          <div class="table-heading d-flex justify-content-between align-items-center">
            <h3 class="table-title">Media List</h3>
            {{-- <div class="table-filters">
              <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#menuModal">Add Menu</button>            
            </div>  --}}
          </div> {{--  Heading --}}
          <div class="row mb-3">
            <div class="col-12 col-md-4 text-muted d-flex">
              <div class="jsc-view d-flex align-items-center mr-2">
                <a href="?mode=grid" class="mr-2"><i class="fas fa-border-all fa-lg"></i></a>
                <a href="?mode=list" ><i class="fas fa-list fa-lg"></i></a>                
              </div>
              <div class="form-row">
                <div class="col-5">
                  <select name="mediaMime" id="mediaMime" class="form-control">
                    <option value="all">All Media Type</option>
                    @if ( count($mime) > 0 )
                      @foreach ($mime as $mimeItem )
                        <option value="{{ $mimeItem['attach_extension'] }}">{{ strtoupper($mimeItem['attach_extension'])  }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <div class="col-5">
                  <select name="mediaDate" id="mediaDate" class="form-control">
                    <option value="all">All dates</option>
                    @if ( count($date) > 0 )
                      @foreach ($date as $dateKey => $dateItem )
                        <option value="{{$dateKey}}">{{ $dateItem['month'] . ' ' . $dateItem['year'] }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <div class="col-2"><button id="mediaFilter" class="btn btn-outline-primary" data-mode="list">Filter</button></div>
              </div>
            </div>
            <div class="offset-md-5 form-group has-search mb-0 col-12 col-md-3">
              <span class="fa fa-search form-control-feedback"></span>
              <input type="text" id="search_media" class="form-control" placeholder="Search" data-mode="list">
            </div>
          </div> {{--  Filter --}}
          <div id="searchItem">
            <div class="row">
              <div class="col-12 col-lg-3">
                <div class="d-flex mb-3">
                  <select name="multi_delete" id="bulkDeleteAction" class="form-control mr-3">
                    <option value="none">Bulk Actions</option>
                    <option value="delete">Delete Permanently</option>
                  </select>
                  <button id="multipleMedia" class="btn btn-outline-primary">Apply</button>
                </div>
              </div>
              <div class="offset-lg-6 col-12 col-lg-3 text-right">
                @if ( $count > 0 )
                  <span class="mr-3" id="countItem">{{$count}} items</span>
                @endif
                <div class="d-inline-block">{{ $media->links() }}</div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table-media table table-borderless table-striped table-hover jsc-table-list">
                <thead class="thead-dark">
                  <tr>
                    <th width="3%" scope="col">
                      <div class="custom-control custom-checkbox text-center">
                        <input type="checkbox" name="media_check_all" class="custom-control-input" id="mediaCheckAll">
                        <label class="custom-control-label" for="mediaCheckAll"></label>
                      </div>
                    </th>
                    <th scope="col">File</th>
                    <th width="10%" scope="col">Date</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($media as $list)
                    <tr data-trowid="{{ $list->id }}">
                      <td>
                        <div class="custom-control custom-checkbox text-center">
                          <input type="checkbox" class="custom-control-input itemCheckMedia" name="itemCheckMedia" id="media_check_{{ $list->id }}" data-check_id="{{ $list->id }}">
                          <label class="custom-control-label" for="media_check_{{ $list->id }}"></label>
                        </div>
                      </td>
                      <td class="title column-title">
                        <strong class="has-media-icon">
                          <a href="{{ route('media.edit', ['id'=>$list->id]) }}" aria-label="{{ $list->attach_org_filename }}">
                            <span class="media-icon image-icon">
                              <img width="60" height="60" src="{{ asset($list->attach_url) }}" class="jc-img-attachment">
                            </span>
                            {{ $list->attach_org_filename }}
                          </a>
                        </strong>
                        <p class="filename">
                          <span class="sr-only">File name:</span>
                          {{ $list->attach_filename }}
                        </p>
                        <div class="table-action">
                          <span class="jc-media-edit"><a href="{{ route('media.edit', ['id'=>$list->id]) }}" class="text-info">Edit</a> | </span>
                          <span class="jc-media-delete"><a onclick="mediaDeleteSingleAttachment( {{$list->id}},'list')" class="text-danger">Delete Permanently</a></span>
                          {{-- <span class="jc-media-view"><a href="@#">View</a> | </span> --}}
                        </div>
                      </td>
                      <td class="date column-date">{{ $list->updated_at->diffForHumans() }}</td>
                    </tr>
                  @endforeach            
                </tbody>
              </table>            
            </div>            
          </div>
        </div>
      </div>
    @else
      uala
    @endif
  @else
  <div class="card">
    <div class="card-body jsc-table">
      <div class="jsc-media-frame mode-grid hide-menu">
        <div class="jsc-media-content" data-columns="11">
          <div class="attachments-browser hide-sidebar sidebar-for-errors">
            <div class="jsc-card jsc-media-filter mb-3">
              <div class="jsc-card-body p-1 jsc-table">
                <div class="table-heading d-flex justify-content-between align-items-center">
                  <h3 class="table-title">Media List</h3>
                </div><!--Heading-->
                <div class="row mb-3">
                  <div class="col-12 col-md-4 text-muted d-flex">
                    <div class="jsc-view d-flex align-items-center mr-2">
                      <a href="?mode=grid" class="mr-2"><i class="fas fa-border-all fa-lg"></i></a>
                      <a href="?mode=list" ><i class="fas fa-list fa-lg"></i></a>                
                    </div>
                    <div class="form-row">                
                      @php
                          $i = 0;
                      @endphp
                      <div class="col-5">
                        @if ( count($mime) > 0 )
                          <select name="mediaMime" id="mediaMime" class="form-control">
                            <option value="all">All Media Type</option>
                            @foreach ($mime as $mimeItem )
                              <option value="{{ $mimeItem['attach_extension'] }}">{{ strtoupper($mimeItem['attach_extension'])  }}</option>
                            @endforeach
                          </select>                      
                        @endif
                      </div>
                      <div class="col-5">
                        @if ( count($date) > 0 )
                          <select name="mediaDate" id="mediaDate" class="form-control">
                            <option value="all">All dates</option>
                            @foreach ($date as $dateItem )
                              <option value="{{$i}}">{{ $date[$i]['month'] . ' ' . $date[$i]['year'] }}</option>
                              @php $i++; @endphp
                            @endforeach
                          </select>                      
                        @endif

                      </div>
                      <div class="col-2"><button id="mediaFilter" class="btn btn-outline-primary" data-mode="grid">Filter</button></div>
                    </div>
                  </div>
                  <div class="offset-md-5 form-group has-search mb-0 col-12 col-md-3">
                    <span class="fa fa-search form-control-feedback"></span>
                    <input type="text" id="search_media" class="form-control" placeholder="Search" data-mode="grid">
                  </div>
                </div> {{--  Filter --}}
              </div>
            </div><!--Search-->
            <div id="searchItem">
              <ul class="attachments ui-sortable ui-sortable-disabled">
                @if ( count( $media ) > 0 )
                  @foreach ($media as $list)
                    <li tabindex="0" aria-label="{{ $list->attach_org_filename }}" data-id="{{ $list->id }}" class="attachment">
                      <div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait">
                        <div class="thumbnail" data-toggle="modal" data-target="#thumbnail_{{ $list->id }}">
                          <div class="centered"><img src="{{ asset($list->attach_url) }}" alt=""></div>
                        </div>
                      </div>
                    </li>
                  @endforeach
                @else
                  <li><p class="no-media">No Image Available, Start to Upload <a href="{{ route('media.upload') }}">Image Here.</a></p></li>
                @endif
              </ul>
            </div>
          </div>
        </div>
      </div> 
    </div>
  </div>
    {{-- Modal --}}
    @forelse ($media as $modalList)
      <div id="thumbnail_{{ $modalList->id }}" class="modal fade media-model jsc-core-ui jsc-modal-salmon" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel" data-imgMethod="modal-edit" data-modalMode="modal-grid"  aria-hidden="true">
        <div class="modal-dialog media-dialog jsc-modal-dialog">
          <div class="media-modal-content modal-content jsc-modal-content" role="document">
            <div class="edit-attachment-frame mode-select hide-menu hide-router">
              <div class="edit-media-header">
                <button type="button" class="close media-modal-close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" class="media-modal-icon">×</span>
                </button>
              </div>
              <div class="media-frame-title"><h1 id="myLargeModalLabel">Attachment Details</h1></div>
              <div class="media-frame-content">
                <div class="attachment-details save-ready">
                  <div class="attachment-media-view landscape">
                    <div class="thumbnail thumbnail-image">
                      <img src="{{ asset($modalList->attach_url) }}" class="details-image" itemprop="image">
                    </div>
                  </div>
                  <div class="attachment-info">
                    <div class="details">
                      <div class="filename"><strong>File name:</strong> {{ $modalList->attach_filename }}</div>
                      <div class="filetype"><strong>File type:</strong> {{ $modalList->attach_extension }}</div>
                      <div class="uploaded"><strong>Uploaded on:</strong> {{ $modalList->created_at->format('M d, Y') }}</div>
                      @php
                        $bytes = filesize($modalList->attach_url);
                        if( $bytes >= 1024 ):
                          $bytes = number_format($bytes/1024, 2) . 'KB';
                        elseif( $bytes >= 1048576 ):
                          $bytes = number_format($bytes/1048576, 2) . 'MB';
                        else:
                          $bytes = '';
                        endif;
                        list($width, $height) = getimagesize($modalList->attach_url);
                      @endphp
                      <div class="file-size"><strong>File size:</strong> {{ $bytes }}</div>
                      <div class="dimensions"><strong>Dimensions:</strong> {{ $width .' x '. $height }}</div>
                    </div>
                    <div class="settings">
                      <label data-setting="alt" class="setting">
                        <span class="name">Alternative Text</span>
                        <input type="text" name="alt" id="alt_{{ $modalList->id }}" class="form-control" value="{{ $modalList->attach_image_alt }}">
                      </label>
                      <label data-setting="title" class="setting">
                        <span class="name">Title</span>
                        <input type="text" name="title" id="title_{{ $modalList->id }}" class="form-control" value="{{ $modalList->attach_org_filename }}">
                      </label>
                      <label data-setting="caption" class="setting">
                        <span class="name">Caption</span>
                        <textarea id="caption_{{ $modalList->id }}" >{{$modalList->attach_excerpt}}</textarea>
                      </label>
                      <label data-setting="description" class="setting">
                        <span class="name">Description</span>
                        <textarea id="desc_{{ $modalList->id }}" >{{$modalList->attach_content}}</textarea>
                      </label>
                      <div class="setting">
                        <span class="name">Uploaded By</span>
                        <span class="value">{{ Auth::user()->name }}</span>
                      </div>
                      <label data-setting="url" class="setting">
                        <span class="name">Copy Link</span>
                        <input type="text" class="form-control" value="{{asset($modalList->attach_url)}}" readonly="readonly">
                      </label>
                    </div>
                    <div class="actions">
                      <a href="{{ route('media.edit', ['id'=>$modalList->id]) }}" class="btn btn-link">Edit more details </a>
                      | <button type="button" class="btn btn-link text-danger delete-attachment">Delete Permanently </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @empty
        <div class="text-center">
          <p class="no-media">No Media Found.</p>
        </div>
    @endforelse
  @endif


@endsection

@section('script')
  <script type="application/javascript">
    /**Check-all*/
    $(document).on('click', '#mediaCheckAll',function(e){
      $('.itemCheckMedia').attr('checked', this.checked);
    });

    /**single-all*/
    $(document).on('click', '.itemCheckMedia', function(){
      if($(".itemCheckMedia").length == $(".itemCheckMedia:checked").length) {
        $("#mediaCheckAll").attr("checked", "checked");
      } else {
        $("#mediaCheckAll").removeAttr("checked");
      }
    });

    $(document).on('click','.attachment', function(e){
      e.preventDefault();
      $id = $(this).data('id');
      mediaModal($id, '#thumbnail_' + $id);
      mediaDeleteAttachment($id);
    });

    const mediaModal = (id) => {
      /**Edit Image through modal*/

      $('input[type="text"], textarea').on('change', function(){
        let _token = $('input[name="_token"]').val();
        let alt = $('input#alt_' + id ).val();
        let title = $('input#title_' + id ).val();
        let caption = $('textarea#caption_' + id ).val();
        let desc = $('textarea#desc_' + id ).val();
        let url = '/media/' + id;
        
        let data = {
          '_token': _token,
          '_method': 'PUT',          
          'imgMethod': $('#thumbnail_' + id).data('imgMethod'),
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
            $('.attachment-info .details').append('<div id="modalSpinner" class="position-absolute" style="top:0;right:0;"><i class="fas fa-spinner fa-pulse"></i></div>');
          },
          complete: function(){
            //Hide Loader
            $('.attachment-info .details #modalSpinner').remove();
          },
          success: function(res){
            if(res.status === 'success'){
              $('.attachment-info .details').append('<div id="modalSaved" class="position-absolute text-success" style="top:0;right:0;">' + res.message + '</div>');
              setTimeout(function(){
                $('.attachment-info .details #modalSaved').fadeOut();
              }, 2000);
            }
          }
        });
      });
    }

    const mediaDeleteAttachment = (id) => {
      /**Delete Image through modal*/

      $(document).on('click', '.delete-attachment', function(e){
        e.preventDefault();
        let url = '/media/' + id + '/delete';
        let mode = $('.media-model').data('modalmode');
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
            if(res.status === 'success' && res.mode === 'modal-grid'){
              location.reload();
            }
          }
        });
      });
    }

    const mediaDeleteSingleAttachment = ( id, mode = '' ) =>{
      /**Delete Image through List Attachment*/

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
            /**Checking what type of mode he/she used.*/
            if(res.mode === 'list'){
              let attachmentList = $('table.jsc-table-list tbody > tr');
              let attachmentBody = $('table.jsc-table-list tbody > tr').length - 1;

              if(attachmentBody > 0){
                /**Loop to the table row*/
                $(attachmentList).each(function(){
                  /**Collect the table row data id*/
                  let tRowId =  $(this).data('trowid');

                  /**Compare the submitted data id to table row id*/
                  if( tRowId == res.id ){
                    /**Fade out if true*/
                    $(this).fadeOut(800, function(){
                      /**Remove the table row*/
                      $(this).remove();
                    });
                  }
                });
                customTooltips(res.status, res.message);           
              }else{
                let newTRowOutput = '<tr><td colspan="3"><p class="text-center text-muted h6">No Image Available, Start to Upload <a href="/media/media-new">Image Here</a>.</p></td></tr>';

                $('table.jsc-table-list tbody').html(newTRowOutput);
              }
            }
          }else{
            window.location.href = '/media';
          }
        }
      });
    }

    $(document).on('click', '#multipleMedia', function(e){
      /**Multiple Delete*/

      e.preventDefault();
      let argsItem = [];
      let selectDelete = $('select[name="multi_delete"]').val();
      $('.itemCheckMedia:checked').each(function(){
        let item = $(this).data('check_id');
        argsItem.push(item);
      });

      if( selectDelete === 'delete' && argsItem.length > 0){
        let itemJoinVal = argsItem.join(',');
        let url = '/media/multipleDelete';
        let data = {
          '_token': $('input[name="_token"]').val(),
          '_method': 'DELETE',
          'id': itemJoinVal
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
            let tb = $('table.jsc-table-list tbody');
            let attachmentBody = $('table.jsc-table-list tbody > tr').length;

            if( attachmentBody > 0 ){
              /**Loop to the table row*/
              $.each(res.id, function(key, val){
                /**Collect the table row data id*/
                let tr = tb.find('tr[data-trowid="'+val+'"]');
                let countTr = attachmentBody - key;

                /**Fade out*/
                $(tr).fadeOut(800, function(){
                  /**Remove the table row*/
                  tr.remove();
                });

                /**Count the table row*/
                if(countTr == 1){
                  /**Table row is equal to one add this.*/
                  let newTRowOutput = '<tr><td colspan="3"><p class="text-center text-muted h6">No Image Available, Start to Upload <a href="/media/media-new">Image Here</a>.</p></td></tr>';
                  tb.fadeIn(800).html(newTRowOutput);
                  
                  /**Check if the mediaCheckAll is checked.*/
                  if( $('#mediaCheckAll').is(':checked',true) ){
                    $('#mediaCheckAll').prop('checked', false);
                  }
                }

                customTooltips(res.status, res.message); 
              });
            }else{
              let newTRowOutput = '<tr><td colspan="3"><p class="text-center text-muted h6">No Image Available, Start to Upload <a href="/media/media-new">Image Here</a>.</p></td></tr>';
              tb.fadeIn(800).html(newTRowOutput);              
            }

          }
        });
      }
      
    });
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                mediaGetData(page);
            }
        }
    });
    $(document).on('keypress', '#search_media', function(e){
      let keycode = e.keyCode || e.which;
      if(e.which == 13) {
        mediaGetData();
      }
    });

    const mediaGetData = ( page = null ) =>{
        let queryItem = $('#search_media').val();
        let mode = $('#search_media').data('mode');
        let data ={
          'search': queryItem,
          'mode': mode
        }

        $.ajax({
          method: 'GET',
          url: '/media/search',
          data: data,
          dataType: 'json',
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
            $('#searchItem').html(res.view);

            $('table.table-hover tr').hover(function(e){
              $(this).find('td').find('.table-action').addClass('visible');
            },function(e){
              $(this).find('td').find('.table-action').removeClass('visible');
            });
          }
        });
    }

    /*$(window).on('hashchange', function(){
      if(window.location.hash){
        var page = window.location.hash.replace('#', '');
        if (page == Number.NaN || page <= 0) {
            return false;
        }else{
          let queryItem = $('#search_media').val();
          let mode = $('#search_media').data('mode');
          getData(page, queryItem, mode);
        }
      }
    });*/
    $(document).on('click', '.pagination a',function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        var pagination = $('.pagination').data('pagination');
        var $queu = '';
        if( typeof pagination !=='undefined' ){
          $queu = 'filter';
        }
        {{--  console.log(url);  --}}
        getData($(this).attr('href').split('page=')[1], $queu);
    });

    const getData = ( page, queu) =>{

      let url = "/media/getPagination";
      let mode = $('#search_media').data('mode');
      let queryItem = $('#search_media').val();
      let date = '';
      let ext = '';

      if( queu === 'filter' ){
        mode = $('#mediaFilter').data('mode');
        queryItem = '';
        date = $('#mediaDate').val();
        ext = $('#mediaMime').val();
      }

      let data = {
        'queu': queu,
        'mode': mode,
        'search': queryItem,
        'date': date,
        'ext': ext,
      }

     $.ajax({
        url: url + '?page=' + page,
        type: "get",
        data: data,
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
            $('#searchItem').html(res.view);
          // location.hash = page;

          $('table.table-hover tr').hover(function(e){
            $(this).find('td').find('.table-action').addClass('visible');
          },function(e){
            $(this).find('td').find('.table-action').removeClass('visible');
          });
        }
      });
    }

    $(document).on('click', '#mediaFilter',function(event){
      /**filter*/
      let mode = $(this).data('mode');
      let mime = $('#mediaMime').val();
      let date = $('#mediaDate').val();
      let url = '/media/getFilter';
      let data = {
        '_token': $('input[name="_token"]').val(),
        'mode': mode,
        'extension': mime,
        'date': date
      };

      $.ajax({
        type: 'POST',
        url: url,
        data: data,
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
        success: function(res){
          $('#searchItem').html(res.view);

          $('.pagination').attr('data-pagination', 'filter');

          $('table.table-hover tr').hover(function(e){
            $(this).find('td').find('.table-action').addClass('visible');
          },function(e){
            $(this).find('td').find('.table-action').removeClass('visible');
          });
        }
      });
    });
    
  </script>    
@endsection



{{-- @include('admin.media.media_library_modal') --}}