@if ( count($data) > 0 )
  @if ( $mode === 'list' )
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
        <span class="mr-3" id="countItem">{{$counts}} items</span>
        <div class="d-inline-block">{{ $data->links() }}</div>
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
          @foreach ($data as $list)
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
  @else
    <ul class="attachments ui-sortable ui-sortable-disabled">
      @foreach ($data as $list)
        <li tabindex="0" aria-label="{{ $list->attach_org_filename }}" data-id="{{ $list->id }}" class="attachment">
          <div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait">
            <div class="thumbnail" data-toggle="modal" data-target="#thumbnail_{{ $list->id }}">
              <div class="centered"><img src="{{ asset($list->attach_url) }}" alt=""></div>
            </div>
          </div>
        </li>
      @endforeach
    </ul>
  @endif
@else
  @include('layouts.inc.no-found', ['data' => $query, 'filters' => $filters ])
@endif
