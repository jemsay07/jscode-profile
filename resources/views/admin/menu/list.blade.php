@extends('layouts.app')
@section('active_menu_list','active')
@section('page-heading')
	<i class="fas fa-bars"></i> Menu
@endsection
@section('content')
  <div class="card mb-5">
    <div class="card-body jsc-table">
      <div class="table-heading d-flex justify-content-between align-items-center">
        <h3 class="table-title">Menu List</h3>
        <div class="table-filters">
          <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#menuModal">Add Menu</button>
        </div>
      </div>
      <div class="table-responsive">
        <table id="jscMenuListTable" class="table table-borderless table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th scope="col" class="jsc-w-5">#</th>
              <th scope="col">Menu Name</th>
              <th scope="col" class="jsc-w-10">Date</th>
            </tr>
          </thead>
          <tbody>
            @if ( $menu->count() > 0 )
              @foreach ($menu as $menuItems)
                <tr>
                  <td>{{ $menuItems->id }}</td>
                  <td>{{ $menuItems->menu_title }}
                    <div class="table-action">
                      <a href="{{ route('menu.edit', ['id'=> $menuItems->id]) }}" class="btn-link"><small>Edit</small></a> | <a href="javascript:void(0);" class="btn-link text-danger" id="delMenuTable" data-menuid="{{ $menuItems->id }}" data-toggle="modal" data-target="#delMenuTable_{{ $menuItems->id }}"><small>Delete</small></a>
                    </div>
                  </td>
                  <td>{{ $menuItems->created_at->diffForHumans() }}</td>
                </tr>
              @endforeach            
            @else
              <tr><td class="text-center text-secondary" colspan="3">Create your own menu.</td></tr> 
            @endif

          </tbody>
        </table>        
      </div>
      {{ $menu->render() }}
    </div>
  </div>
  @include('admin.menu.menu_modal_create')
@endsection

@section('script')
  <script type="application/javascript">
    jQuery(document).ready(function($){

      /**Load the Modal for delete*/
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
        $('a#delMenuTable').on('click', function(){
          let id = $(this).data('menuid');
          let target = $(this).data('target').replace('#', '');
          let title = 'Delete Menu';
          let messages = 'Are you sure, you want to delete this menu.';
          customModalMsg(id,target, title, messages, 'deletePageMenus', 'Yes', 'single', 'delete', 'deleteMenuPage', 'listMenuPage' );
        });
      });

      /**Create New Menu*/
      $('#createMenuModal').on('submit', function(e){
        e.preventDefault();
        $.ajax({
          method: 'Post',
          url: $(this).data('link'),
          data:new FormData(this),
          dataType: 'JSON',
          contentType: false,
          cache: false,
          processData: false,
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
            //console.log(res.status);

            /**Table list appended*/
            let trowOutput = '<tr>';
              trowOutput += '<td>' + res.last_insert_id + '</td>';
              trowOutput += '<td>' + res.title;
                trowOutput += '<div class="table-action">';
                  trowOutput += '<a href="/menu/' + res.last_insert_id + '/menu_edit" class="btn-link"><small>Edit</small></a> | <a href="http://" class="btn-link text-danger" data-menuid="' + res.last_insert_id + '" data-toggle="modal" data-target="#deleteMenu"><small>Delete</small></a>';
                trowOutput += '</div>';
              trowOutput += '</td>';
              trowOutput += '<td>' + res.date + '</td>';
            trowOutput += '</tr>';

            if( ! $.isEmptyObject(res.errors) ){
              errorMessages(res.errors, res.status);
            }else{
              /**checking-status-if-already-exists*/
              if(res.status === 'taken'){
                $('label[for="menu_title"]').css({'color':'#e86060'});
                $('input[name="menu_title"]').addClass('is-invalid').removeClass('is-valid');
                $('.has-error').html(res.message);
              }else{
                $('label[for="menu_title"]').css({'color':'#212529'});
                $('input[name="menu_title"]').addClass('is-valid').removeClass('is-invalid');     
                $('.has-error').html('');
                
                /**Prepend Output*/
                $('table#jscMenuListTable > tbody').prepend(trowOutput);

                /**Re-call the hover snippet*/
                $('table.table-hover tr').hover(function(e){
                  $(this).find('td').find('.table-action').addClass('visible');
                },function(e){
                  $(this).find('td').find('.table-action').removeClass('visible');
                });

                /**Display the message*/
                customTooltips(res.status, res.message);

                /**Remove Class using settimeout*/
                setTimeout(function(){
                  $('input[name="menu_title"]').removeClass('is-valid').removeClass('is-invalid');
                }, 5000);    
                
              }
            }
          }
        });
      });

      /**Delete table menu list*/
      $(document).on('click', '#deletePageMenus', function(e){
        e.preventDefault();
        let id = $(this).data('modalstatusid');
        let method = $(this).data('modalmethod');
        let action = $(this).data('modalaction');

        $.ajax({
          type: 'POST',
          url: '/menu/destroy/' + id,
          data:{
            '_token': $('input[name="_token"]').val(),
            '_method': 'DELETE',
            'menu_action': action,
            'menu_method': method,
            'id': id
          },
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
            if(res.redirect == 1){
              
              /**Hide Modal*/
              $('#deleteMenus').modal('hide');
              
              /**Redirect*/
              window.location = '/menu';
            }
          }
        });
      });

      /**Reset Modal*/
      $('#menuModal').on('hidden.bs.modal', function(){
        $('label[for="menu_title"]').css({'color':'#212529'});
        $('input[name="menu_title"]').removeClass('is-invalid').removeClass('is-valid').val('');
        $('.has-error').addClass('d-none').html(' ');   
      });
    });

    const errorMessages = (error, status) => {
      $.each( error, function(key, value){
        //console.log(key + ' | ' + value)
        if(status == 'taken' || $('input[name="menu_title"]').length > 0){
          $('label[for="menu_title"]').css({'color':'#e86060'});
          $('input[name="menu_title"]').addClass('is-invalid').removeClass('is-valid');
          $('.has-error').html(value);
        }
      });
    }
  </script>
@endsection
