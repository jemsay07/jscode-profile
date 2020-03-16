@extends('layouts.app')
@section('active_page_list','active')
@section('page-heading')
	<i class="fas fa-copy"></i> Pages <a href="/page/page-new" class="btn btn-outline-info">Add Page</a>
@endsection
@section('content')
  <div class="card mb-5">
    <div class="card-body jsc-table">
      <div class="table-heading d-flex justify-content-between align-items-center">
        <h3 class="table-title">Page List</h3>
        {{--  <div class="table-filters">
          <a href="/page/page-new" class="btn btn-outline-info">Add Page</a>
        </div>  --}}
      </div>{{--  Heading --}}
      <div class="d-block-sm d-md-flex justify-content-between align-items-center">
        <div class="jsc-list-status">
          <ul class="list-group">
            @if ( $countAll > 0 )
              <li class="list-item-group">
                <a href="/page" id="page_all" class="text-{{ (app('request')->input('post_status') == '') ? 'primary selected' : 'secondary' }}">All <span class="count">({{$countAll}})</span></a>
              </li>                
            @endif
            @if ( $countPublish > 0 )
              <li class="list-item-group">
                <a href="?post_status=publish" id="page_publish" class="text-{{ (app('request')->input('post_status') == 'publish') ? 'primary selected' : 'secondary' }}">Publish <span class="count">({{$countPublish}})</span></a>
              </li>                
            @endif
            @if ( $countDraft > 0 )
              <li class="list-item-group">
                <a href="?post_status=draft" id="page_draft" class="text-{{ (app('request')->input('post_status') == 'draft') ? 'primary selected' : 'secondary' }}">Draft <span class="count">({{$countDraft}})</span></a>
              </li>                
            @endif
            @if ( $countTrash > 0 )
              <li class="list-item-group">
                <a href="?post_status=trash" id="page_trash" class="text-danger {{ (app('request')->input('post_status') == 'trash') ? 'selected' : '' }}">Trash <span class="count">({{$countTrash}})</span></a>
              </li>                
            @endif

          </ul>
        </div>
        <div class="has-search mb-3 d-flex justify-content-between" data-status="{{$post_status}}">
          <span class="fa fa-search form-control-feedback"></span>
          <input type="text" id="search_page" class="form-control mr-2" placeholder="Search">
          <button type="button" class="btn btn-outline-primary page-search">Search</button>
        </div>
      </div>
      <div id="searchInput">
        <div id="searchItem" class="d-block-sm d-md-flex justify-content-between align-items-center">
          <div class="text-muted d-flex mb-3">
            <div class="form-row">
              <div class="col-6 d-flex">
                <select name="page-action" id="pageAction" class="form-control mr-2">
                  <option value="bulk">Bulk Action</option>
                  @if ($post_status === 'trash')
                    <option value="delete">Delete Permanently</option>
                    <option value="restore">Restore</option>
                  @else
                    <option value="trash">Move to trash</option>
                  @endif
                </select>
                <button type="button" class="btn btn-outline-primary jsc-action" id="doAction">Apply</button>
              </div>
              <div class="col-6 d-flex">
                <select name="page-date" id="pageDate" class="form-control mr-2">
                  @if ( count($date) > 0 )
                    @foreach ($date as $dKey => $dVal)
                      @php
                        $month = ($dKey !== 'all') ? $dVal['month'] . ' ' . $dVal['year'] : 'All Dates';
                      @endphp
                    <option value="{{ $dKey }}">{{ $month }}</option>
                    @endforeach
                  @else
                    <option value="all">All Dates</option>
                  @endif
                </select>
                <button type="button" class="btn btn-outline-primary jsc-page-filter">Filter</button>
              </div>
            </div>
          </div>
          <div class="has-count mb-3">
            {{$count}} <span>Items</span>
            <span class="d-inline-block">{{ $pages->links() }}</span>
          </div><!--For-filters-->
        </div>
        <div class="table-responsive">
          <table id="jscPageListTable" class="table table-borderless table-striped table-hover">
            <thead class="thead-dark">
              <tr>
                <th width="3%" scope="col">
                  <div class="custom-control custom-checkbox text-center">
                    <input type="checkbox" name="page_check_all" class="custom-control-input" id="pageCheckAll" {{ ( $pages->count() > 0 ) ? '' : 'disabled' }}>
                    <label class="custom-control-label" for="pageCheckAll"></label>
                  </div>
                </th>
                <th scope="col">Title</th>
                <th scope="col">Author</th>
                <th width="10%" scope="col">Date</th>
              </tr>
            </thead>
            <tbody>
              @if ( $pages->count() > 0 )
                @foreach ($pages as $pages)
                  <tr data-trowid="{{ $pages->id }}">
                    <td>
                      <div class="custom-control custom-checkbox text-center">
                        <input type="checkbox" class="custom-control-input itemCheckPage" name="itemCheckPage" id="page_check_{{ $pages->id }}" data-check_id="{{ $pages->id }}">
                        <label class="custom-control-label" for="page_check_{{ $pages->id }}"></label>
                      </div>
                    </td>
                    <td class="title column-title">
                      <div class="has-media-icon">
                        <a href="{{ route('page.edit', ['id'=>$pages->id]) }}" aria-label="{{ $pages->post_title }}">
                          {{ $pages->post_title }}
                        </a>
                        <div class="table-action">
                          @if ( $post_status == 'trash' )
                            <span class="d-inline-block"><a href="@#" class="text-primary jcs-page-restore" data-toggle="modal" data-target="#listPageRestore" data-pageid="{{ $pages->id }}">Restore</a></span> | 
                            <span class="d-inline-block"><a href="@#" class="text-danger">Delete Permanently</a></span>
                          @else
                            <span class="jcs-page-edit"><a href="{{ route('page.edit', ['id'=>$pages->id]) }}" class="text-info">Edit</a></span> | 
                            <span><a href="javascript:void(0);" class="text-danger jcs-page-trash" data-toggle="modal" data-target="#listPageTrash" data-pageid="{{ $pages->id }}">Move to trash</a></span> 
                          @endif
                          
                        </div>
                      </div>
                    </td>
                    <td>{{$pages->role}}</td>
                    <td>{{ Carbon::parse($pages->updated_at)->diffForHumans(Carbon::now()) }}</td>
                  </tr>
                @endforeach
              @else
                <tr data-trowid="0">
                  <td colspan="4">
                    <p class="no-post text-center mb-0">No Page Available</p>
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>        
      </div>

    </div>
  </div>
@endsection

@section('script')
  <script type="application/javascript">
    /**Check-all*/
    $(document).on('click', '#pageCheckAll',function(e){
      $('.itemCheckPage').attr('checked', this.checked);
    });

    /**single-all*/
    $(document).on('click', '.itemCheckPage', function(){
      if($(".itemCheckPage").length == $(".itemCheckPage:checked").length) {
        $("#pageCheckAll").attr("checked", "checked");
      } else {
        $("#pageCheckAll").removeAttr("checked");
      }
    });

    $(window).on('load', function(e){
      e.preventDefault();
        /**
          * ID -> number
          * Target
          * Title
          * Messages
          * Button ID
          * Button Messages
          * Type (multiple,single)
          * Status (delete,success,info)
          * method 
          * Action (front list, page, post)
          */
      $('.jcs-page-trash').on('click', function(e){
        e.preventDefault();
        let $id = $(this).data('pageid');
        let $target = $(this).data('target').replace('#', '');
        let $title = 'Page List Delete';
        let $messages = 'Are you sure, do you want to move it to trash.';
        customModalMsg($id,$target, $title, $messages, 'listMoveToTrashs', 'Yes', 'single', 'delete', 'listMoveToTrash', 'listPage' );
      });

      /**Single-Trash*/
      $(document).on('click', '#listMoveToTrashs', function(e){
        e.preventDefault();
        let $id = $(this).data('modalstatusid');
        let $method = $(this).data('modalmethod');
        let $action = $(this).data('modalaction');
        let $method_url = '/page/movetotrash/single';

        moveToTrash($id, $action, $method, $method_url, 'listPageTrash', 'page');
      });

      /**Single-Restore*/
      $('.jcs-page-restore').on('click', function(e){
        e.preventDefault();
        let $id = $(this).data('pageid');
        let $target = $(this).data('target').replace('#', '');
        let $title = 'Restore Item';
        let $messages = 'Are you sure, do you want to restore it.';
        customModalMsg($id,$target, $title, $messages, 'listRestores', 'Yes', 'single', 'info', 'listRestore', 'listPage' );
      });

      $(document).on('click', '#listRestores', function(e){
        e.preventDefault();
        let $id = $(this).data('modalstatusid');
        let $method = $(this).data('modalmethod');
        let $action = $(this).data('modalaction');
        let $method_url = '/page/restore/single'
        restorePage($id, $action, $method, $method_url, 'listPageRestore', 'page');
      });


      /**Multiple-Action*/
      $(document).on('click', '#doAction', function(e){
        e.preventDefault();
        let $page_action = $('#pageAction').val();
        let $args = [];
        if( $page_action === 'trash' ){
          $('.itemCheckPage:checked').each(function(){
            let $pItemID = $(this).data('check_id');
            $args.push($pItemID);
          });
          let $method_url = '/page/movetotrash/multiple';
          if($args.length > 0){
            let $id = $args.join(",");
            moveToTrash($id, '', '', $method_url, 'listCheckTrash', 'page');
          }
        }else if( $page_action === 'restore' ){
          console.log('restore here');
        }else{
          console.log('default this.');
        }
      });

      /**filter*/
      $(document).on('click','.jsc-page-filter',  function(e){
        e.preventDefault();
        let $date = $('select#pageDate').val();
        let $status = 'all';
        if( $('.has-search').data('status') )
          $status = $('.has-search').data('status');

        let $data = {
          '_token': $('input[name="_token"]').val(),
          'status' : $status,
          'date' : $date
        }

        $.ajax({
          type: 'POST',
          url: '/page/getFilter',
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
          success: function(res){
            $('#searchInput').html(res.view);

            $('table.table-hover tr').hover(function(e){
              $(this).find('td').find('.table-action').addClass('visible');
            },function(e){
              $(this).find('td').find('.table-action').removeClass('visible');
            });
          }
        });
      });
    });

    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                getSearch(page);
            }
        }
    });

    $(document).ready(function(){
      let $search = $('.page-search');
      let $searchKeypress = $('#search_page');

      /**Click Search*/
      $search.on('click', function(e){
        e.preventDefault();
        let $query = $searchKeypress.val();

        getSearch($query);
      });

      /**Enter Search*/
      $searchKeypress.on('keyup', function(e){
        e.preventDefault();
        let $query = $searchKeypress.val();

        if( e.which === 13 )
          getSearch($query);
      });

    });
    const getSearch = ($query) => {

      let $mode_status = 'all';

      if($('.has-search').data('status'))
        $mode_status = $('.has-search').data('status');
      
      let data = {
        'search' : $query,
        'mode_status': $mode_status
      }

      $.ajax({
        method: 'GET',
        url: '/page/search',
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
        success:function(res){
          $('#searchInput').html(res.view);

          $('table.table-hover tr').hover(function(e){
            $(this).find('td').find('.table-action').addClass('visible');
          },function(e){
            $(this).find('td').find('.table-action').removeClass('visible');
          });
        }
      });
    }


  </script>
@endsection