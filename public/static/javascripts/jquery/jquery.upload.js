if(jQuery) (function($){$.upload = {
	
	swfuIntance : {},

	progressBar : {},

	init : function(config,progressBar) {

		$.upload.config.file_queued_handler = $.upload.file_queued_handler;
		$.upload.config.file_queue_error_handler = $.upload.file_queue_error_handler;
		$.upload.config.file_dialog_complete_handler = $.upload.file_dialog_complete_handler;
		$.upload.config.upload_start_handler = $.upload.upload_start_handler;
		$.upload.config.upload_progress_handler = $.upload.upload_progress_handler;
		$.upload.config.upload_error_handler = $.upload.upload_error_handler;
		$.upload.config.upload_success_handler = $.upload.upload_success_handler;
		$.upload.config.upload_complete_handler = $.upload.upload_complete_handler;
		$.upload.config.queue_complete_handler = $.upload.queue_complete_handler;

		for(var i in config) {

			$.upload.config[i] = config[i];
		
		}

		if(progressBar)
			$.upload.progressBar = progressBar;

		$.upload.swfuIntance = new SWFUpload($.upload.config);	
	
	},
	

	file_queue_error_handler : function (file, errorCode, message) {
		try {
			var msg = ("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
			if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
				
				//$.upload.fileProgress.setStatus(file,'Queue Error:' + msg);	
				alert(msg);
				return;
			}

			switch (errorCode) {
			case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
				//$.upload.fileProgress.setStatus(file,'Queue Error:' + "File is too big.");	
				alert("File is too big.");
				break;
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
				//$.upload.fileProgress.setStatus(file,'Queue Error:' + "Cannot upload Zero Byte files.");
				alert("Cannot upload Zero Byte files.");
				break;
			case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
				//$.upload.fileProgress.setStatus(file,'Queue Error:' + "Invalid File Type.");	
				alert("Invalid File Type.");
				break;
			default:
				if (file !== null) {
					//$.upload.fileProgress.setStatus(file,'Queue Error:' + "Unhandled Error");	
					alert("Unhandled Error");

					$.upload.progressBar = progressBar;
				}
				break;
			}
		} catch (ex) {
			alert(ex);
		}
	},

	file_queued_handler : function(file) {
		try {
			$.upload.fileProgress.init(file);
		} catch (ex) {
			alert(ex);
		}
	},

	queue_complete_handler : function(numFilesUploaded) {

	},

	file_dialog_complete_handler : function(numFilesSelected, numFilesQueued) {

	},

	upload_start_handler : function (file) {

		$.upload.fileProgress.setStatus(file,'Uploading...');

	},

	upload_progress_handler : function(file, bytesLoaded, bytesTotal) {

		try {
			var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
			$.upload.fileProgress.setProgress(file,percent);
			if(percent == 100) {
				$.upload.fileProgress.setStatus(file,"Creating thumbnail...");
			} else {
				$.upload.fileProgress.setStatus(file,'Uploading...');
			}
		} catch (ex) {
			alert(ex);
		}
	},
	
	upload_error_handler : function(file, errorCode, message) {
		//alert("Server response error:" + message);
	},
	upload_success_handler : function(file, serverData) {

		try {
			//alert(serverData);
			var response = eval( '(' + serverData + ')');

			if(response.success) {
				$.upload.fileProgress.setStatus(file,response.success.message);
			} else if(response.errors) {
				$.upload.fileProgress.setStatus(file,response.errors.message);
			} else {
				$.upload.fileProgress.setStatus(file,'PHP Error:' + serverData);
			}


		} catch (ex) {
			
			$.upload.fileProgress.setStatus(file,'Upload failed:' + ex);
		}
		

		//alert(serverData);

	},
	upload_complete_handler : function (file) {

	},

	cancel : function(file) {
		try {
			$.upload.swfuIntance.cancelUpload(file.id);
			var id = "#" + file.id;
			$(id).remove();
		} catch(e){
			alert(e);
		}
	},

	fileProgress : {

		init : function(file){
			var id = "#" + file.id;

			if(!$(id)[0]) {

				$(".grid .upload").append('<tr id="' + file.id +'"><td>' + file.name +'</td><td><span class="progressBar">0%</span></td><td><span class="status">Waiting for Upload</span><span class="remove">Remove this file</span></td></tr>');

				$(id + " .progressBar").progressBar($.upload.progressBar);
				$(id + " .remove").click(function(){
					$.upload.cancel(file);
				});

			
			}			   
		},

		setProgress : function(file,percent) {
			var id = "#" + file.id;
			$(id + " .progressBar").progressBar(percent,$.upload.progressBar);
		},

		setStatus : function(file,message) {
			var id = "#" + file.id;
			$(id + " .status").html(message);
		}


	},
	

	config : {
		// Backend Settings
		upload_url: '',
		post_params: 'upload[]',

		// File Upload Settings
		file_size_limit : "10 MB",	// 2MB
		file_types : "*.png;*.jpg;*.jpeg",
		file_types_description : "Photos",
		file_upload_limit : "10",


		// Button Settings
		//button_image_url : "images/SmallSpyGlassWithTransperancy_17x18.png",
		button_placeholder_id : "spanButtonPlaceholder",
		button_width: '100%',
		button_height: 18,
		button_text : '<span class="buttonText">Select Images</span>',
		button_text_style : '.buttonText { display:block;font-family: Helvetica, Arial, sans-serif; font-size: 14pt; color:#333; font-weight : bold;text-align:center; }',
		button_text_top_padding: 0,
		button_text_left_padding: 0,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		
		// Flash Settings
		flash_url : "/javascripts/swfupload/swfupload.swf",
		
		// Debug Settings
		debug: false
	}


		
}})(jQuery);
