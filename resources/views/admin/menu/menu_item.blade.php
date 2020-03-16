@extends('layouts.app')
@section('active_menu_list','active')
@section('page-heading')
  @csrf
	<i class="fas fa-bars"></i> <span>{{ $getMenu->menu_title }}</span> <i class="fas fa-pencil-alt jsc-cursor" id="editableHeading" data-toggle="tooltip" data-placement="right" title="click me to update menu title."></i>
@endsection
@section('content')
  <div id="menuItemWrap" class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-12 col-md-3">
          <div id="addMenu" class="page-heading d-flex justify-content-between align-items-center mb-4">
            <h3 class="page-title">Add Menu Item</h3>
          </div>
          <div class="accordion mb-3" id="accordionExample">
            <div class="card">
              <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                  <button class="btn btn-link btn-segoe-bold p-1" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Custom Link
                  </button>
                </h2>
              </div>
              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                @csrf
                <div class="card-body">
                  <div class="form-group form-row">
                    <label for="menu_item_url" class="col-sm-2">URL</label>
                    <div class="col-sm-10"><input type="url" name="menu_item_url" id="menu_item_url" class="form-control" placeholder="http://example.com"><span id="menu_item_url"></span></div>
                  </div>
                  <div class="form-group form-row">
                    <label for="menu_item_title" class="col-sm-2">Title</label>
                    <div class="col-sm-10"><input type="text" name="menu_item_title" id="menu_item_title" class="form-control"><span id="menu_item_title"></span></div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <button type="button" class="btn btn-outline-salmon" id="addNewMenuLink" data-id="{{ $menu_id }}">Add New Menu</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-9">
          <div class="page-heading d-flex justify-content-between align-items-center">
            <h3 class="page-title">Menu Structure</h3>
            <div class="page-filters">
              <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#menuModal">Add Menu</button>
            </div>
          </div>
          <div class="page-content position-relative">
            <form data-menuid="{{$menu_id}}" id="menuItemForm">
              @csrf
              @method('POST')
              <div id="nested_menu" class="dd jsc-menu-list mb-3" style="min-height:175px;">
                <ol class="dd-list menu-nested-menu">
                  @if ($menu_item->whereNotIn('menu_item_status', ['trash'])->count() > 0)
                    @php 
                      $parent = '0';
                    @endphp
                    @foreach ($menu_item as $mItems)
                     @if ($mItems->parent_id == $parent)
                        <li class="dd-item" id="listItem_{{ $mItems->id }}" data-id="{{ $mItems->id }}">
                          <div class="dd-handle">
                            <span>{{ $mItems->menu_item_title }}</span>
                            <div class="jc-tooltips">
                              <a href="javascript:void(0);" data-itemid="{{ $mItems->id }}" data-mparentid="{{ $mItems->menu_id }}" id="list_modal_edit" class="text-info" data-toggle="modal" data-target="#menuModalItem_{{ $mItems->id }}"><i class="fas fa-edit"></i> Edit</a> | <a href="javascript:void(0);" data-itemid="{{ $mItems->id }}" data-mparentid="{{ $mItems->menu_id }}" id="list_modal_delete" class="text-danger"><i class="fas fa-trash"></i> Delete</a>
                            </div>
                          </div>
                          @foreach ($mItems->children as $child)
                            <button data-action="collapse" type="button">Collapse</button><button data-action="expand" type="button" style="display: none;">Expand</button>
                            <ol class="dd-list">
                              <li class="dd-item">
                                <div class="dd-handle">
                                  <span>{{ $child->menu_item_title }}</span>
                                </div>
                              </li>
                            </ol>
                          @endforeach
                        </li>
                      @endif
                    @endforeach                               
                  @else
                    <li id="noRecord" class="dd-item">No Record Found</li>
                  @endif
                </ol>
              </div>
              <div class="jsc-menu-locations-wrap">
                @php
                    $locationUnserialize = unserialize($getMenu->menu_location);
                    $automatic = ( $locationUnserialize['automatic'] == 'on' ) ? 'checked' : '';
                    $primary = ( $locationUnserialize['primary'] == 'on' ) ? 'checked' : '';
                    $social = ( $locationUnserialize['social'] == 'on' ) ? 'checked' : '';
                    $footer = ( $locationUnserialize['footer'] == 'on' ) ? 'checked' : '';
                @endphp
                <p class="page-title">Menu Location</p>
                <p class="text-secondary mb-3"><small><i>* You may choose were you want to add the location of your menu item.</i></small></p>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" name="location[{{$menu_id}}][automatic]" id="location_automatic" value="{{ old('on', $locationUnserialize['automatic']) }}" {{ $automatic }}>
                  <label class="custom-control-label" for="location_automatic">Automatically Navigation</label>
                </div>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" name="location[{{$menu_id}}][primary]" id="location_primary" value="{{ old('off', $locationUnserialize['primary']) }}" {{ $primary }}>
                  <label class="custom-control-label" for="location_primary">Primary Menu</label>
                </div>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" name="location[{{$menu_id}}][social]" id="location_social" value="{{ old('off', $locationUnserialize['social']) }}" {{ $social }}>
                  <label class="custom-control-label" for="location_social">Social Menu</label>
                </div>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" name="location[{{$menu_id}}][footer]" id="location_footer" value="{{ old('off', $locationUnserialize['footer']) }}" {{ $footer }}>
                  <label class="custom-control-label" for="location_footer">Footer Menu</label>
                </div>
              </div>
              <div class="jsc-menu-actions text-right">
                <button type="button" id="deleteMenu" class="btn btn-danger" data-menuid="{{ $menu_id }}" data-toggle="modal" data-target="#deleteMenus">Delete</button>
                <button type="button" id="saveUpdateMenuItem" data-menuaction="saveUpdateMenu" class="btn btn-outline-info">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@include('admin.menu.menu_modal_create')
@include('admin.menu.menu_item_modal')

@section('script')
  <script src="{{ asset('/js/jquery.nestable.js') }}"></script>
  <script type="application/javascript">
    jQuery(document).ready(function($){
      
      /**Menu checkbox check if non is check.*/
      $(window).on('load', function(){
        $('[data-toggle="tooltip"]').tooltip()
        {{--  $('.jsc-menu-locations-wrap input[type="checkbox"]').each(function(){
          if( $(this).is(':checked') === false ){
            $('#location_automatic').prop('checked', true).attr('value', 'on');
          }
        });  --}}
      });

      /**Editable: Display input box.*/
      $('#editableHeading').on('click', function(e){
        e.preventDefault();
        let edit = $(this);
        if( edit.find('editWrap').length > 0 ){
          return;
        }
        let removeSpace = $('.jsc-content .page-heading span').text();
        let currentText = removeSpace.trim();

        let editOutput = '';
        editOutput += '<div class="d-flex editWrap mb-0 align-items-center position-absolute" style="left:42px;top:0;">';
          editOutput += '<input type="text" id="editItem" name="menu_name" class="form-group mr-2 mb-0" value="' + currentText + '">';
          editOutput += '<button id="saveEditMenuTitle" class="btn btn-primary btn-sm d-inline-block mr-1" data-id="{{ $getMenu->id }}">Save</button>';
          editOutput += '<button id="editableHeadingClosed" class="btn btn-secondary btn-sm d-inline-block" >Cancel</button>';
        editOutput += '</div>';

        $('.jsc-content .page-heading').append(editOutput);
        edit.hide();

      });

      /**Editable: Save the value.*/
      $(document).on('click', '#saveEditMenuTitle', function(e){
        e.preventDefault();
        let id = $(this).data('id');
        let _token = $('input[name="_token"]').val();
        let menu_action = 'updateMenuTitle';
        let title = $('input[name="menu_name"]').val();
        let url = '/menu/' + id;
        $.ajax({
          type: 'POST',
          url: url,
          data: {
            '_token': _token,
            '_method': 'PUT',
            'menu_action': menu_action,
            'menu_title': title
          },
          success: function(res){
            if(res.status == 'success'){
              $('.jsc-content .page-heading span').html(res.title);
            }
          }
        });
      });

      /**Editable: Remove/Hide the input textbox.*/
      $(document).on('click', '#editableHeadingClosed', function(e){
        e.preventDefault();
        $('.editWrap').remove();
        $('#editableHeading').show();
      });
      
      /**Menu Link*/
      $('#addNewMenuLink').on('click', function(e){
        e.preventDefault();
        let _token = $('input[name="_token"]').val();
        let url = '/menu/store';
        $.ajax({
          method: 'Post',
          url: url,
          data:{
            '_token': _token,
            'menu_id': $(this).data('id'),
            'menu_item_url': $('input[name="menu_item_url"]').val(),
            'menu_item_type': 'custom',
            'menu_item_status': 'draft',
            'menu_item_object': 'custom',
            'menu_method': '',
            'menu_item_title': $('input[name="menu_item_title"]').val(),
          },
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
            
            /**Check status*/
            if( res.status == 'success' ){
              $('#collapseOne .form-group input').removeClass('is-invalid').addClass('is-valid');
              if($('#collapseOne .form-group span').has('.has-error')){
                $('#collapseOne .form-group span').removeClass('has-error').html('');
              }
              /**append-menu-list*/
              let list_output = '<li class="dd-item" id="listItem_' + res.last_insert_id + '" data-id="' + res.last_insert_id + '">';
                list_output += '<div class="dd-handle">';
                  list_output += '<span>' + res.title + '</span>';
                  list_output += '<div class="jc-tooltips">';
                    list_output += '<a href="javascript:void(0);" data-itemid="' + res.last_insert_id + '" data-mparentid="' + res.menu_id + '" id="list_modal_edit" class="text-info" data-toggle="modal" data-target="#menuModalItem_' + res.last_insert_id + '"><i class="fas fa-edit"></i> Edit</a> | <a href="javascript:void(0);" data-itemid="' + res.last_insert_id + '" data-mparentid="' + res.menu_id + '" id="list_modal_delete" class="text-danger"><i class="fas fa-trash"></i> Delete</a>';
                  list_output += '</div>';
                list_output += '</div>';
              list_output += '</li>';

              if( $('ol.menu-nested-menu > li.dd-item').has('#noRecord') ){
                $('li#noRecord').remove();
              }
              $('#nested_menu > ol.menu-nested-menu').append(list_output);

              /**Display modal*/
              $('.jsc-modal-wrap').html(res.modal);

              /**Clear-forms*/
              $('input[name="menu_item_url"]').val('');
              $('input[name="menu_item_title"]').val('');
            }

            if( ! $.isEmptyObject(res.errors) ){
              errorMenuMessages(res.errors);
            }
          }
        });
      });

      /**nested*/
      var updateOutput = function(e){
        var list = e.length ? e : $(e.target), output = list.data('output');
        var data = {
          'list': list.nestable('serialize'),
          '_token': $('input[name=_token]').val(),
          '_method': 'POST'
        }
        {{--  console.log(list.nestable('serialize'));  --}}
        $.ajax({
          type: 'POST',
          url: '/menu/menu_edit/nestable',
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
            console.log(res.message)
          }
        });
      }

      $('#nested_menu').nestable({
        group: 1
      }).on('change', updateOutput);
      let idMode = 0;

      $('.dd a').on('mousedown', function(e){
        e.preventDefault();
        idMode = $(this).data('itemid');
        return false;
      });

      $('.dd').on('mousedown','a',function(e) {
        e.preventDefault();
        idMode = $(this).data('itemid');
        return false;
      });

      $('.dd form button').on('mousedown', function(e) {
        e.preventDefault();
        var itemid = $(this).data('itemid');
        $('.mId').val(itemid);
        return false;
      });

      $('.dd').on('mousedown','#list_modal_edit',function(event) {
          event.preventDefault();
          var itemid = $(this).data('itemid');
          $('.mId').val(itemid);
          return false;
      });

      $(document).on('click', '#list_modal_edit', function(e){
        e.preventDefault();
        var itemid = $(this).data('itemid');
        $('.mId').val(itemid);
        console.log(itemid);
      });

      /**Modal-Update*/
      $(document).on('click', '.update_menu', function(e){
        e.preventDefault();
        let menu_id = $('#mId-' + idMode).val();
        let url = '/menu/' + menu_id;
        let tab = ($('#menu_modal_item_attr_tab_' + menu_id).is(':checked')) ? '1': 0;
        $.ajax({
          type: 'POST',
          url: url,
          data: {
            'menu_id': menu_id,
            'menu_item_title': $('input[name="menu_modal_item_title_' + menu_id + '"]').val(),
            'menu_item_attr_title': $('input[name="menu_modal_item_attr_title_' + menu_id + '"]').val(),
            'menu_item_desc': $('#menu_modal_item_desc_' + menu_id ).val(),
            'menu_item_url': $('input[name="menu_modal_item_url_' + menu_id + '"]').val(),
            'menu_item_xfn': $('input[name="menu_modal_item_xfn_' + menu_id + '"]').val(),
            'menu_item_tab': tab,
            'menu_item_css': $('input[name="menu_modal_item_attr_css_' + menu_id + '"]').val(),
            'menu_item_id': $('input[name="menu_modal_item_attr_id_' + menu_id + '"]').val(),
            'menu_action': 'modal_update_details',
            'menu_action_status':'normal',
            '_token': $('input[name=_token]').val(),
            '_method': 'PUT',
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
            if(res.status === 'success'){
              if(menu_id === res.last_insert_id){
                let list_output = '<div class="dd-handle">';
                  list_output += '<span>' + res.last_title + '</span>';
                  list_output += '<div class="jc-tooltips">';
                    list_output += '<a href="javascript:void(0);" data-itemid="' + res.last_insert_id + '" data-mparentid="' + res.item_menu_id + '" id="list_modal_edit" class="text-info" data-toggle="modal" data-target="#menuModalItem_' + res.last_insert_id + '"><i class="fas fa-edit"></i> Edit</a> | <a href="javascript:void(0);" data-itemid="' + res.last_insert_id + '" data-mparentid="' + res.item_menu_id + '" id="list_modal_delete" class="text-danger"><i class="fas fa-trash"></i> Delete</a>';
                  list_output += '</div>';
                list_output += '</div>';
                $('#listItem_' + res.last_insert_id).html(list_output);
              }
            }
          }
        });
      });

      /**Delete-List*/
      $(document).on('click', '#list_modal_delete', function(e){
        e.preventDefault();

        let itemId = $(this).data('itemid');
        let mParentId = $(this).data('mparentid');

        $.ajax({
          type: 'POST',
          url: '/menu/destroy/' + itemId,
          data: {
            '_token': $('input[name="_token"]').val(),
            '_method': 'DELETE',
            'menu_action': 'listMenuItemDelete',
            'menu_method': 'nestedMenuItemDelete',
            'menu_parentID': mParentId,
            'menu_itemID': itemId,
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
            if(res.status === 'success'){
              /**compare the two value*/
              if( itemId == res.menu_itemID ){
                $('li#listItem_' + res.menu_itemID).remove();
              }
              /**check the length of the .menu-nested-menu*/
              if($('ol.menu-nested-menu').has('li').length === 0){
                $('ol.menu-nested-menu').html('<li id="noRecord" class="dd-item">No Record Found</li>');
              }

              /**Display the message*/
              customTooltips(res.status, res.message);
            }
          }
        });
      });

      /**Delete Menu Modal*/
      $(window).on('load', function(e){
        e.preventDefault();
        $('#deleteMenu').on('click', function(){
          let id = $(this).data('menuid');
          let target = $(this).data('target').replace('#', '');
          let title = 'Delete Menu';
          let messages = 'Are you sure, you want to delete this menu.';
          customModalMsg(id,target, title, messages, 'deletePageMenus', 'Yes', 'multiple', 'delete', 'deleteMenuPage', 'listMenuPage' );
        });
      });

      /**Delete Menu Page*/
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
            /**Table List*/
            {{-- let trow =  $('table#jscMenuListTable tr');
            $(trow).each(function(e){
              e.preventDefault();
              target = $(this).find('#deleteMenu').data('target');
            }); --}}
          }
        });
      });

      /**Menu checkbox location*/
      $('.jsc-menu-locations-wrap input[type="checkbox"]').on('click', function(){
        return ($(this).prop('checked') ) ? $(this).attr('value', 'on') : $(this).attr('value', 'off');
      });

      /**Save/Update-Menu*/
      $(document).on('click','#saveUpdateMenuItem',function(e){
        e.preventDefault();
        /**Checking if selected one locations*/
        if( $('.jsc-menu-locations-wrap').find('input[type="checkbox"]').is(':checked') === true ){
          
          let menuForm = $('#menuItemForm');
          let menuId = menuForm.data('menuid');
          let menuAction = $(this).data('menuaction');
          let primary = $('#location_primary').val();
          let social = $('#location_social').val();
          let footer = $('#location_footer').val();

          /**Checking if selected one locations before fire ajax*/
          if( $('.jsc-menu-locations-wrap').find('input[type="checkbox"]').is(':checked') === true ){
            $.ajax({
              type: 'POST',
              url: '/menu/' + menuId,
              data: {
                'menu_action': 'saveUpdateMenuItem',
                '_m_action': menuAction,
                '_menu_id': menuId,
                '_token': $('input[name="_token"]').val(),
                '_method': 'PUT',
                'automatic' : $('#location_automatic').val(),
                'primary' : $('#location_primary').val(),
                'social' : $('#location_social').val(),
                'footer' : $('#location_footer').val(),
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
                if(res.status == 'success'){
                  customTooltips(res.status, res.message);
                }
                {{-- if( res.redirect ){
                  window.location = '/' + res.redirect;
                } --}}
              }
            }); 
          }else{
            /**Display some errors*/
            customTooltips('danger', 'Select atleast one of the location.');
          }
        }else{
          /**Display some errors*/
          customTooltips('danger', 'Select atleast one of the location.');
        }
      });
    });

    const errorMenuMessages = (errors) =>{
      $.each(errors, function(key,value){
        let formG = $('.form-group');
        formG.find('input#' + key).addClass('is-invalid');
        formG.find('span#' + key).addClass('has-error').html(value);
      });
    }

    
  </script>
@endsection