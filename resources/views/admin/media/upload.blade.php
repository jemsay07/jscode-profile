@extends('layouts.app')
@section('active_media_upload','active')
@section('page-heading')
	<i class="fas fa-photo-video"></i> Media
@endsection
@section('content')
  <div id="drop_area" class="text-center">
    <form action="{{ route('media.store') }}" method="POST" class="box" enctype="multipart/form-data">
      @csrf
      <p>Drag files to upload</p>
      <input type="file" id="file" class="box_file" onchange="handleFiles(this.files)" data-multiple-caption="{count} files selected" multiple>
      <label for="file" id="label_file_upload">Select files</label>
      {{--  <div class="box_input">
        <input type="file" name="files[]" id="file" class="box_file" data-multiple-caption="{count} files selected" multiple>
        <label for="file"><strong>Choose a file</strong><span class="box__dragndrop"> or drag it here</span></label>
        <button type="submit" class="btn btn-primary box_button">Upload</button>
      </div>
      <div class="box__uploading">Uploading&hellip;</div>
      <div class="box__success">Done!</div>
      <div class="box__error">Error! <span></span>.</div>  --}}
    </form>    
  </div>
  <p class="text-muted">Maximum upload file size: 50 MB.</p>
  <ul id="attachmentWrap" class="list-group list-group-flush"></ul>

@endsection
@section('script')
  <script type="application/javascript">
    let dropArea = _('drop_area');
    dropArea.classList.add('has-advance-upload');
    //drag dragstart dragend dragover dragenter dragleave drop
    {{--  dropArea.addEventListener('dragenter', handlerFunction, false);
    dropArea.addEventListener('dragleave', handlerFunction, false);
    dropArea.addEventListener('dragover', handlerFunction, false);
    dropArea.addEventListener('drop', handlerFunction, false);  --}}

    dropArea.addEventListener('drop', handlerDrop, false);

    ['dragenter','dragleave','dragover','drop'].forEach( eventName => {
      dropArea.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach( eventName => {
      dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach( eventName => {
      dropArea.addEventListener(eventName, unhighlight, false);
    });

    function preventDefaults(e){
      e.preventDefault();
      e.stopPropagation();
    }

    function highlight(e){
      dropArea.classList.add('highlight');
    }

    function unhighlight(e){
      dropArea.classList.remove('highlight');
    }

    function handlerDrop(e){
      let dt = e.dataTransfer
      let files = dt.files

      handleFiles(files)
    }

    function handleFiles(files){
      files = [...files]
      files.forEach(uploadFile)
      //showFiles(files)
    }

    //function showFiles(file){}

    function uploadFile(file){
      let formData = new FormData()
      formData.append('file', file)
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        url: '/media/store',
        type: 'POST',
        data:formData,
        dataType:'json',
        contentType: false,
        processData: false,
        beforeSend: function(){},
        complete: function(){},
        success: function(res){
          if( ! $.isEmptyObject(res.errors) ){
            errorMessages(res.errors, res.status);
          }else{
            
            let files = res.files;
            for (let i = 0; i < files.length; i++) {
              let fileName = files[i][0];
              let fileUrl = files[i][1];

              let mediaListOutput = '<li class="list-group-item">';
                mediaListOutput += '<img src="' + fileUrl + '">';
                mediaListOutput += '<div class="filename">';
                  mediaListOutput += '<span class="title">' + fileName + '</span>';
                mediaListOutput += '</div>';
                mediaListOutput += '<span class="badge badge-success badge-pill">' + res.status + '</span>';
              mediaListOutput += '</li>';

              $('#attachmentWrap').prepend(mediaListOutput);
            }
          }
        }
      });
    }

    const errorMessages = (error, status) => {
      $.each(error, function(key, value){
        customTooltips('danger', value);
      });
    }

  </script>
@endsection