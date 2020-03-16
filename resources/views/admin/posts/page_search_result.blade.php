@if ( $page->count() > 0 )
  <div id="searchItem" class="d-block-sm d-md-flex justify-content-between align-items-center">
    <div class="text-muted d-flex mb-3">
      <div class="form-row">
        <div class="col-5">
          <select name="page-action" id="pageAction" class="form-control">
            <option value="bulk">Bulk Action</option>
            <option value="trash">Move to trash</option>
          </select>
        </div>
        <div class="col-5">
          <select name="page-date" id="pageDate" class="form-control">
            @if ( count($date) > 0 )
              @foreach ($date as $dKey => $dVal)
                @php                  
                  if($request_date == $dKey){
                    $selected = 'selected';
                  }else{
                    $selected = '';
                  }
                @endphp
                {{--  {{ $dKey . ' ay ' . $selected . ' dahil si ' . $request_date }}  --}}
                <option value="{{ $dKey }}" {{ $selected }}>{{ ($dKey !== 'all') ? $dVal['month'] . ' ' . $dVal['year'] : 'All Dates' }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-2"><button class="btn btn-outline-primary jsc-page-filter">Filter</button></div>
      </div>
    </div>
    <div class="has-count mb-3">
      {{$count}} <span>Items</span>
      <span class="d-inline-block">{{ $page->links() }}</span>
    </div><!--For-filters-->
  </div>
  <div class="table-responsive">
    <table id="jscPageListTable" class="table table-borderless table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <th width="3%" scope="col">
            <div class="custom-control custom-checkbox text-center">
              <input type="checkbox" name="page_check_all" class="custom-control-input" id="pageCheckAll">
              <label class="custom-control-label" for="pageCheckAll"></label>
            </div>
          </th>
          <th scope="col">Title</th>
          <th scope="col">Author</th>
          <th width="10%" scope="col">Date</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($page as $pages)
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
                  <span class="jc-media-edit"><a href="{{ route('page.edit', ['id'=>$pages->id]) }}" class="text-info">Edit</a> | </span>
                  <span class="jc-media-delete"><a class="text-danger">Delete Permanently</a></span>
                </div>
              </div>
            </td>
            <td>{{$pages->role}}</td>
            <td>{{ Carbon::parse($pages->updated_at)->diffForHumans(Carbon::now()) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@else
  @include('layouts.inc.no-found', ['data' => $query, 'filters' => $filters ])
@endif