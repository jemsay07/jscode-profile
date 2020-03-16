@if ( $mediaDetails->count() > 0 )
  <div class="media-uploader-status" style="display:none">
    progress bar here
  </div>
  <div class="attachment-details save-ready">
    <h2>
      Attachment Details
      <span id="modalSaved" class="settings-save-status position-absolute" style="top:0;right:5px;" role="status"></span>
    </h2>
    <div class="attachment-info">
      <div class="thumbnail thumbnail-image">
        <img src="{{ asset($mediaDetails->attach_url) }}" />
      </div>
      <div class="details">
        @php
          $bytes = filesize($mediaDetails->attach_url);
          if( $bytes >= 1024 ):
            $bytes = number_format($bytes/1024, 2) . 'KB';
          elseif( $bytes >= 1048576 ):
            $bytes = number_format($bytes/1048576, 2) . 'MB';
          else:
            $bytes = '';
          endif;
          list($width, $height) = getimagesize($mediaDetails->attach_url);
        @endphp
        <div class="filename"><strong>{{$mediaDetails->attach_filename}}</strong></div>
        <div class="uploaded">{{$mediaDetails->created_at->format('F d, Y')}}</div>
        <div class="file-size">{{ $bytes }}</div>
        <div class="dimensions">{{ $width .' x '. $height }}</div>
        <a href="{{ route('media.edit', ['id'=>$mediaDetails->id]) }}" class="btn btn-link text-info p-0 d-block text-left">Edit</a>
        <button class="btn btn-link text-danger p-0 d-block text-left">Delete Permanently</button>
      </div>
    </div>
    <span class="setting has-description">
      <label for="attachment-details-alt-text-{{$mediaDetails->id}}" class="name">Alt Text</label>
      <input type="text" name="" id="attachment-details-alt-text-{{$mediaDetails->id}}" aria-describedby="alt-text-description" value="{{$mediaDetails->attach_image_alt}}">
    </span>
    <span class="setting" data-setting="title">
      <label for="attachment-details-title-{{$mediaDetails->id}}" class="name">Title</label>
      <input type="text" id="attachment-details-title-{{$mediaDetails->id}}" value="{{$mediaDetails->attach_org_filename}}">
    </span>
    <span class="setting" data-setting="caption">
      <label for="attachment-details-caption-{{$mediaDetails->id}}" class="name">Caption</label>
      <textarea id="attachment-details-caption-{{$mediaDetails->id}}">{{$mediaDetails->attach_excerpt}}</textarea>
    </span>
    <span class="setting" data-setting="description">
      <label for="attachment-details-description-{{$mediaDetails->id}}" class="name">Description</label>
      <textarea id="attachment-details-description-{{$mediaDetails->id}}">{{$mediaDetails->attach_content}}</textarea>
    </span>
    <span class="setting" data-setting="url">
      <label for="attachment-details-copy-link">Copy Link</label>
      <input type="text" id="attachment-details-copy-link" value="{{ asset($mediaDetails->attach_url) }}" readonly>
    </span>
  </div>    
@endif
