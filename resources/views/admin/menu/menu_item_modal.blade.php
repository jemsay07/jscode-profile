<div class="jsc-modal-wrap"></div>

<!-- Modal -->
@if ( count( $menu_item) )
    @foreach ($menu_item as $modal_item)
      <div class="modal fade jsc-modal jsc-modal-salmon" id="menuModalItem_{{$modal_item->id}}" tabindex="-1" role="dialog" aria-labelledby="menuModalItem_{{$modal_item->id}}" aria-hidden="true">
        <div class="modal-dialog jsc-dialog-modal" role="document">
          <input type="hidden" name="mId[]" id="mId-{{$modal_item->id}}" class="mId form-control" value="{{$modal_item->id}}">
          <form id="menuItem" role="form">
            @method('post')
            @csrf
            <div class="modal-content jsc-content-modal">
              <div class="modal-header jsc-header-modal">
                <h5 class="modal-title" id="menuModalItem_{{$modal_item->id}}"><i class="fas fa-pencil-alt"></i> {{$modal_item->menu_item_title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="menu_modal_item_url_{{$modal_item->id}}">URL</label>
                  <input type="text" name="menu_modal_item_url_{{$modal_item->id}}" id="menu_modal_item_url_{{$modal_item->id}}" class="form-control" value="{{ $modal_item->menu_item_url }}">
                </div>
                <div class="form-group">
                  <label for="menu_modal_item_title_{{$modal_item->id}}">Navigation Label</label>
                  <input type="text" name="menu_modal_item_title_{{$modal_item->id}}" id="menu_modal_item_title_{{$modal_item->id}}" class="form-control" value="{{ $modal_item->menu_item_title }}">
                </div>
                <div class="form-group">
                  <label for="menu_modal_item_attr_title_{{$modal_item->id}}">Title Attribute</label>
                  <input type="text" name="menu_modal_item_attr_title_{{$modal_item->id}}" id="menu_modal_item_attr_title_{{$modal_item->id}}" class="form-control" value="{{ $modal_item->menu_item_attr_title }}">
                </div>
                <div class="custom-control custom-checkbox form-group">
                  <input type="checkbox" class="custom-control-input" id="menu_modal_item_attr_tab_{{$modal_item->id}}" {{ ($modal_item->menu_item_tab === 1) ? 'checked': '' }}>
                  <label class="custom-control-label" for="menu_modal_item_attr_tab_{{$modal_item->id}}">Check this custom checkbox</label>
                </div>
                <div class="form-group form-row">
                  <div class="col-12 col-md-6">
                    <label for="menu_modal_item_attr_css_{{$modal_item->id}}">CSS Classes (optional)</label>
                    <input type="text" name="menu_modal_item_attr_css_{{$modal_item->id}}" id="menu_modal_item_attr_css_{{$modal_item->id}}" class="form-control" value="{{ $modal_item->menu_item_css }}">
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="menu_modal_item_attr_id_{{$modal_item->id}}">CSS Id (optional)</label>
                    <input type="text" name="menu_modal_item_attr_id_{{$modal_item->id}}" id="menu_modal_item_attr_id_{{$modal_item->id}}" class="form-control" value="{{ $modal_item->menu_item_id }}">
                  </div>
                </div>
                <div class="form-group">
                  <label for="menu_modal_item_xfn_{{$modal_item->id}}">Link Relationship (XFN)</label>
                  <input type="text" name="menu_modal_item_xfn_{{$modal_item->id}}" id="menu_modal_item_xfn_{{$modal_item->id}}" class="form-control" value="{{ $modal_item->menu_item_xfn }}">
                </div>
                <div class="form-group">
                  <label for="menu_modal_item_desc_{{$modal_item->id}}">Descriptions</label>
                  <textarea name="menu_modal_item_desc_{{$modal_item->id}}" id="menu_modal_item_desc_{{$modal_item->id}}" class="form-control">{{ $modal_item->menu_item_desc }}</textarea>
                </div>
              </div>
              <div class="modal-footer jsc-footer-modal">
                <button type="button" class="btn btn-outline-salmon update_menu">Update changes</button>
              </div>
            </div>
          </form>
        </div>
      </div>        
    @endforeach
@endif
