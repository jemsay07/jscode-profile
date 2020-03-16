@extends('admin.posts.page-add-new')

@section('title', $page->post_title)
@section('page-content')
  {!! $page->post_content !!}
@endsection

@section('btn-publish')
  <button class="btn btn-outline-info btn-sm jsc-post-update-button" data-last_id="{{ $last_id }}">Update</button>
@endsection

@section('status')
  <span class="text-{{ ( $page->post_status === 'publish' ) ? 'success': 'warning'}}">{{ucfirst($page->post_status)}}</span>
@endsection
@section('date')
  {{ date('m/d/Y', strtotime($page->created_at)) }}
@endsection
@section('featured-image')
<div class="jsc-editor-post-featured-img">
  @if ( $image !== 0 )
    <button type="button" class="btn btn-block text-center btn-editor-post-featured_ifmg_preview btn-edit-post-img" data-toggle="modal" data-target="#featImgModal" data-img="{{$image->id}}">
      <span><img src="{{ asset($image->attach_url) }}" class="components-responsive-wrapper__content"></span>
    </button>
    <button type="button" class="btn btn-link text-danger is-destructive">Remove featured image</button>
  @else
    <button type="button" class="btn btn-block text-center btn-editor-post-featured_img btn-edit-post-img"  data-toggle="modal" data-target="#featImgModal" data-img="0">Add Featured Image here.</button>
  @endif
</div>
@endsection

@section('page-type')
    <select name="page-type" id="pageType" class="form-control">
      <option value="none" {{($page->post_type === 'page') ? 'selected': ''}}>Default Template</option>
      <option value="front-page" {{($page->post_type === 'front-page') ? 'selected': ''}}>Front Page</option>
    </select>
@endsection

@section('edit-script')
  /**Update*/
  let $id = $('.jsc-post-update-button').data('last_id');
  pageProcess('.jsc-post-update-button', '/page/' + $id, 'edit');
@endsection