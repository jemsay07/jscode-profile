// global.$ = global.jQuery = require('jquery');

jQuery(document).ready(function($){

	/**-Resize Sidebar Menu-------------------------------------------------------------------------*/
	$('.btn[data-action="jsc_sidebar_mini_toggle"]').on('click',function(e){
		e.preventDefault();
		$('#jsc_nav').toggleClass('mini-sidebar');
		$('#app').toggleClass('jsc-minibar');
	});


	/**-Hide & Show-------------------------------------------------------------------------*/
	$('button[data-action="sidebar_toggle"]').on('click', function(e){
		//Show the sidebar
		$('#jsc_nav').stop(true,true).animate({
			marginLeft: '0px',
			opacity: '1'
		}, 600, 'linear');
	});

	$('#jsc_close_nav').on('click', function(e){
		//Hide the sidebar
		$('#jsc_nav').stop(true,true).animate({
			marginLeft: '-999px',
			opacity: '0'
		}, 600, 'linear');
	});

	/**-RWD Class-------------------------------------------------------------------------*/
	$(window).on('load resize',function(){
    	var viewWidth = $(window).width();
    	var app = $('#app');
    	var jsc_nav = $('#jsc_nav');
		if ( viewWidth <= 769 ){
			app.addClass('jsc-rwd-sidebar');
		}else{
			app.removeClass('jsc-rwd-sidebar');

			if ( jsc_nav.attr('style') ) {
				jsc_nav.removeAttr('style');
			}
		}
	});

	/**-Snippet Actions Hover-------------------------------------------------------------------------*/
	$('table.table-hover tr').hover(function(e){
		$(this).find('td').find('.table-action').addClass('visible');
	},function(e){
		$(this).find('td').find('.table-action').removeClass('visible');
	});

	/**-Window Load add some DOM-------------------------------------------------------------------------*/
	$(window).on('load', function(){
		$('#jsc_content_wrap').prepend('<div id="jscToolTips"></div>').prepend('<div id="jscCustomModal"></div>');
	});
});

	function _(el) {
		return document.getElementById(el);
	}
	/**-Modal-------------------------------------------------------------------------
	 * ID -> number
	 * Target
	 * Title
	 * Messages
	 * Button ID
	 * Button Messages
	 * Type (multiple,single)
	 * Status (delete,success,info)
	 * method 
	 * Action (front list, page, post)
	 */
	const customModalMsg = (id = 0, target = 'exampleModal', title = 'Modal title', message = null, btn_id = 'jscExample', btn_msg = 'Save', type = 'single', status = 'default', method = null, action = null)  => {
		let modalOutput = '';

		// < !--Button trigger modal-- >
		// <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
		// 	Launch demo modal
		// </button>
		switch (status) {
			case 'success':
				btn_status_class = 'success';
				modal_class = 'jsc-modal-success';
				break;
			case 'warning':
				btn_status_class = 'warning';
				modal_class = 'jsc-modal-warning';
				break;
			case 'info':
				btn_status_class = 'info';
				modal_class = 'jsc-modal-info';
				break;
			case 'delete':
				btn_status_class = 'danger';
				modal_class = 'jsc-modal-danger';
				break;
		
			default:
				btn_status_class = 'primary';
				modal_class = 'jsc-modal-salmon';
				break;
		}

		//<!--Modal -->
		modalOutput += '<div class="modal jsc-modal fade ' + modal_class + '" id="' + target + '" tabindex="-1" role="dialog" aria-labelledby="jscModalLabel" aria-hidden="true">';
			modalOutput += '<div class="modal-dialog jsc-dialog-modal" role="document">';
				modalOutput += '<div class="modal-content jsc-content-modal">';
					modalOutput += '<div class="modal-header jsc-header-modal">';
						modalOutput += '<h5 class="modal-title" id="jscModalLabel">' + title + '</h5>';
						modalOutput += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
						modalOutput += '<span aria-hidden="true">&times;</span>';
					modalOutput += '</button>';
					modalOutput += '</div>';
					modalOutput += '<div class="modal-body">' +  message + '</div>';
					modalOutput += '<div class="modal-footer jsc-footer-modal">';
						modalOutput += '<button type="button" id="' + btn_id + '" class="btn btn-' + btn_status_class + '" data-modalstatusid="' + id + '" data-modalmethod="' + method + '" data-modalaction="' + action + '">' + btn_msg + '</button>';
					modalOutput += '</div>';
				modalOutput += '</div>';
			modalOutput += '</div>';
		modalOutput += '</div>';

		if (type == 'multiple') {
			_('jscCustomModal').innerHTML += modalOutput;;
		}else{
			_('jscCustomModal').innerHTML = modalOutput;
		}
		
	}

	/**-Tooltips-------------------------------------------------------------------------*/
	const customTooltips = (status = 'danger', message) =>{

		let tooltip = '<div class="alert alert-' + status + ' position-fixed" style="width: auto;right: 10px;bottom: 10px;z-index:1060">' + message + '</div>';

		jQuery('#jscToolTips').fadeIn('slow', function () {
			jQuery(this).append(tooltip).delay(2000).fadeOut('slow');
		});
	}

	/**-MoveToTrash-------------------------------------------------------------------------*/
	const moveToTrash = ($id, $method_action = null, $method_method = null, $method_url = null, $modal_id, $redirect_url) => {
		$.ajax({
			type: 'POST',
			url: $method_url,
			data: {
				'_token': $('input[name="_token"]').val(),
				'_method': 'PUT',
				'o_id': $id,
				'o_action': $method_action,
				'o_method': $method_method,
				'o_redirected': $redirect_url,
			},
			beforeSend: function () {
				//Show loader
				$('body').addClass('overflow-y-none');
				$('.loader-wrap').show();
			},
			complete: function () {
				//Hide loader
				$('body').removeClass('overflow-y-none');
				$('.loader-wrap').hide();
			},
			success: function (res) {			
				if (res.trash_status === 'single') {

					/**Hide Modal*/
					$('#' + $modal_id).modal('hide');

					/**Redirect*/
					//* window.location = '/' + $redirect_url;*/
				}else{
					
					$('.itemCheckPage:checked').each(function (e) {
						let $closest = $(this).closest('tr');
						$closest.fadeOut(800, function(){
							$(this).remove();
						});
					});

					/**Table Empty */
					let $tb = $('#jscPageListTable tbody');
					let $newTr = '';
					let $msg_available = 'Page';
					let $checkAll = $('#pageCheckAll');
					if ($checkAll.is(':checked', true)){
						$newTr += '<tr data-trowid="0">';
							$newTr += '<td colspan="4">';
								$newTr += '<p class="no-post text-center mb-0">No ' + $msg_available + ' available</p>';
							$newTr += '</td>';
						$newTr += '</tr>';
						$tb.html($newTr);
						$checkAll.prop('disabled', true);
					}

					/**Counts Checking */
					let $list = $('.list-item-group');
					if (res.countAll > 0){
						$list.find('a#page_all span').html('(' + res.countAll + ')');
					}else{
						$('a#page_all').parent().remove();
					}

					if (res.countPublish > 0){
						$list.find('a#page_publish span').html('(' + res.countPublish + ')');
					}else{
						$('a#page_publish').parent().remove();
					}

					if (res.countDraft > 0){
						$list.find('a#page_draft span').html('(' + res.countDraft + ')');
					}else{
						$('a#page_draft').parent().remove();
					}

					if (res.countTrash > 0){
						$list.find('a#page_trash span').html('(' + res.countTrash + ')');
					}else{
						$('a#page_trash').parent().remove();
					}

				}
			}
		});
	}

	/**-Restore-------------------------------------------------------------------------*/
	const restorePage = ($id, $method_action = null, $method_method = null, $method_url = null, $modal_id, $redirect_url) => {
		$.ajax({
			type: 'POST',
			url: $method_url,
			data: {
				'_token': $('input[name="_token"]').val(),
				'_method': 'PUT',
				'r_id': $id,
				'r_action': $method_action,
				'r_method': $method_method,
				'r_redirected': $redirect_url,
			},
			beforeSend: function () {
				//Show loader
				$('body').addClass('overflow-y-none');
				$('.loader-wrap').show();
			},
			complete: function () {
				//Hide loader
				$('body').removeClass('overflow-y-none');
				$('.loader-wrap').hide();
			},
			success: function (res) {
				if (res.redirect === 'single') {

					/**Hide Modal*/
					$('#' + $modal_id).modal('hide');

					/**Redirect*/
					window.location = '/' + $redirect_url;
				} else {}
			}
		});
	};
