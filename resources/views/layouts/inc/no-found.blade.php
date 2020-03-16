<div id="notFoundWrap">
  <div class="p-3 text-center">
    <div id="searchWrapNotfound">
      <i class="fas fa-search"></i>
    </div>
    <p class="text-center text-muted h4">Sorry, But {!! ($filters === 'search') ? '<span id="searchTextItem">' . $data . '</span> is not': 'no item' !!} found.</p>
    @if ( $filters === 'search' )
      <p class="text-muted font-italic">Try to adjusting your search or filter to find what you are looking for.</p>
    @endif
  </div>
</div>