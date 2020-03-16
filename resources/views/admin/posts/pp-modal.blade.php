<div id="featImgModal" class="modal fade media-model jsc-core-ui jsc-modal-salmon" tabindex="-1" role="dialog" aria-labelledby="featModalImg" aria-hidden="true">
	<div class="modal-dialog media-dialog jsc-modal-dialog">
		<div id="dropArea" class="media-modal-content modal-content jsc-modal-content" role="document">
			<button id="closeModal2" type="button" class="close media-modal-close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true" class="media-modal-icon">×</span>
			</button>
			<div class="media-frame mode-select hide-menu">
				<div class="media-frame-title">
					<h1>Add Featured Image</h1>
				</div>
				<div class="media-frame-router">
					<ul class="nav nav-tabs media-router" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="upload2-tab" data-toggle="tab" href="#upload2" role="tab" aria-controls="upload2" aria-selected="true">Upload</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="media2-tab" data-toggle="tab" href="#media2" role="tab" aria-controls="media2" aria-selected="false">Media</a>
						</li>
					</ul>						
				</div>
				<div class="media-frame-content tab-content" data-columns="10">
					<div class="tab-pane fade show active" id="upload2" role="tabpanel" aria-labelledby="upload-tab">
						<div class="uploader-inline">
							<div class="uploader-inline-content">
								<div class="upload-ui">
									<h2 class="upload-instructions drop-instructions">Drop files to upload</h2>
									<p class="upload-instructions drop-instructions">or</p>
									<form id="addFileForm2" enctype="multipart/form-data">
										@csrf
										<div class="col-12 col-md-3 col-lg-3 m-auto mb-3">
											<div class="custom-file">
												<label id="file-label2" for="file_name2" class="lbl_upload">Choose file</label>
												<div class="uploader_wrap"><input type="file" name="file_name" id="file_name2" class="form-control-file" required></div>
											</div>
											<small id="errorlog2" class="text-danger mb-2 mt-2"></small>
										</div>
									</form>
									<span id="slctdFile2" class="d-block"></span>
									<p class="upload-instructions drop-instructions text-muted">Maximum upload file size: 20 MB.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="attachment-browser tab-pane fade" id="media2" role="tabpanel" aria-labelledby="media-tab">
						<h2 class="media-views-heading sr-only">Attachment List</h2>
						<ul id="allFT" tabindex="-1" class="attachments ui-media">
							@foreach ($media as $medias)
								<li class="attachment-list save-ready" role="checkbox" aria-label="{{ $medias->attach_org_filename }}" aria-checked="false" data-id="{{ $medias->id }}" tabindex="0">
									<div class="attachment-preview type-image landscape">
										<div class="thumbnail">
											<div class="centered">
												<img src="{{ asset($medias->attach_url) }}">
											</div>
										</div>
									</div>
									<button type="button" class="btn btn-info btn-jsc-minus position-absolute" tabindex="0">
										<i class="fas fa-check"></i>
									</button>
								</li>
							@endforeach
						</ul>
						<div class="media-sidebar"></div>
					</div>
				</div>
				<div class="media-frame-toolbar">
					<div class="media-toolbar">
						<div class="media-toolbar-primary search-form">
							<button type="button" class="btn btn-outline-primary jsc-media-select" disabled>Set featured image</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>