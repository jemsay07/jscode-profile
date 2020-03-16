<div class="modal fade jsc-modal jsc-modal-salmon" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel" aria-hidden="true">
  <div class="modal-dialog jsc-dialog-modal modal-dialog-centered" role="document">
    <div class="modal-content jsc-content-modal">
      <form id="createMenuModal" data-link="{{route('menu.store')}}">
        @method('POST')
        @csrf
        <input type="hidden" name="menu_method" value="create_menu">
        <div class="modal-header jsc-header-modal">
          <h5 class="modal-title" id="menuModalLabel">Create Menu</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="menuMessages"></div>
          <div id="createNewMenu" class="form-group input-group">
            <label for="menu_title" class="col-sm-2">Menu</label>
            <div class="col-sm-10">
              <input type="text" name="menu_title" id="menu_title" class="form-control">
              <span class="has-error">&nbsp;</span>
            </div>
          </div>
        </div>
        <div class="modal-footer jsc-footer-modal">
          <button id="menuAdd" class="btn btn-outline-salmon">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
