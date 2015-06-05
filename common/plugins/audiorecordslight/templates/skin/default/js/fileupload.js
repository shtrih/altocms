/*
 Audiorecordslight plugin
 (P) PSNet, 2008 - 2013
 http://psnet.lookformp3.net/
 http://livestreet.ru/profile/PSNet/
 https://catalog.livestreetcms.com/profile/PSNet/
 http://livestreetguide.com/developer/PSNet/
 */

var ls = ls || {};

ls.arl_upload = (function ($) {

	this.AddNewButtonsToEditor = function () {
		//
		// declare code of button for using it for markitup settings
		//
		var oButton = {
			'name': ls.lang.get ('plugin.audiorecordslight.panel_button'),
			'className': 'editor-audiorecordslight',
			'beforeInsert': function (m) {
				$ ('#ARL_Upload_Mp3').jqmShow ();
			}
		};

		//
		// adding new button to MarkItUp! editor for topics
		//
		var oSettingsForTopics = ls.settings.getMarkitup ();
		oSettingsForTopics.markupSet.push (oButton);
		ls.settings.getMarkitup = function () {
			return oSettingsForTopics;
		};

		//
		// adding new button to MarkItUp! editor for comments
		//
		var oSettingsForComments = ls.settings.getMarkitupComment ();
		oSettingsForComments.markupSet.push (oButton);
		ls.settings.getMarkitupComment = function () {
			return oSettingsForComments;
		}
	};

	// ---

	this.CheckFileTypeAndSize = function (oThis) {
		if (oThis.files && oThis.files.length == 1) {
			// check file extension
			sFileExt = '';
			iDotPos = oThis.files [0].name.lastIndexOf ('.');
			if (iDotPos != -1) sFileExt = oThis.files [0].name.substr (iDotPos + 1).toLowerCase ();
			if (sFileExt != 'mp3') {
				ls.msg.error (ls.lang.get ('plugin.audiorecordslight.Not_Mp3'));
				return false;
			}

			// check file size
			iMaxSize = $ (oThis).closest ('form').find ('input[name="MAX_FILE_SIZE"]').val ();
			if (oThis.files [0].size > iMaxSize) {
				ls.msg.error (ls.lang.get ('plugin.audiorecordslight.Size_Limit') + (iMaxSize / (1024 * 1024)) + 'Mb');
				return false;
			}

			return true;
		}
	};

	// ---

	this.ResetForm = function (oThis) {
		$ (oThis).get (0).form.reset ();
	};

	// ---

	this.AttachSubmitFormHandler = function (sFormId) {
		$ (sFormId).ajaxForm ({
			dataType: 'json',
			beforeSend: function () {
				// add load indicator for text field if it is set
				oTextUrl = $ (sFormId).find ('input[name="audio_url"]');
				if ($.trim (oTextUrl.val ()) != '') oTextUrl.addClass ('loader');
				// disable submit button
				$ (sFormId).find ('input[name="ARL_Submit"]').attr ('disabled', 'disabled').removeClass ('button-primary');
			},
			uploadProgress: function (event, position, total, percentComplete) {
				$ (sFormId).find ('.ProgressBar').width (percentComplete + '%');
			},
			success: function (data) {
				$ (sFormId).find ('.ProgressBar').width ('100%');
				// process result
				if (data.bStateError) {
					ls.msg.error (data.sMsgTitle,data.sMsg);
				} else {
					$.markItUp ({replaceWith: data.sText});
					ls.arl_upload.ResetForm ($ ('#ARL_AudioFile'));
					$ (sFormId).closest ('.modal').jqmHide ();
					ls.msg.notice ('Ok');
				}
			},
			complete: function (xhr) {
				$ (sFormId).find ('.ProgressBar').width ('0%');
				$ (sFormId).find ('.Cover').html (ls.lang.get ('plugin.audiorecordslight.SelectFile'));
				// remove load indicator for text field if it is set
				$ (sFormId).find ('input[name="audio_url"]').removeClass ('loader');
				// enable submit button
				$ (sFormId).find ('input[name="ARL_Submit"]').removeAttr ('disabled').addClass ('button-primary');
			}
		});
	};

	// ---

	this.AttachFileTypeChecking = function (sFileField) {
		$ (sFileField).bind ('change.audiorecordslight', function () {
			if (!ls.arl_upload.CheckFileTypeAndSize (this)) {
				ls.arl_upload.ResetForm (this);
				return false;
			}
			// to display filename in div
			$ (this).closest ('form').find ('.FileField .Cover').html ($ (this).val ());
		});
	};

	// ---

	return this;

}).call (ls.arl_upload || {}, jQuery);

// ---

jQuery (document).ready (function ($) {

	if (ARL_Files_Uploads_Allowed) {
		//
		// Add button on panel
		//
		ls.arl_upload.AddNewButtonsToEditor ();

		//
		// init upload window
		//
		$ ('#ARL_Upload_Mp3').jqm ();

		//
		// upload file action
		//
		ls.arl_upload.AttachSubmitFormHandler ('#ARL_AudioForm');

		//
		// Select file type checking
		//
		ls.arl_upload.AttachFileTypeChecking ('#ARL_AudioFile');
	}

});
