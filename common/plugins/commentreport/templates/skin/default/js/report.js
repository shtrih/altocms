$(function() {

	if(jQuery().jqm){
		$('#report-form').jqm();
	}

});

var ls = ls || {};

ls.commentreport =( function ($) {

	this.open = function (iId,user) {
	   $('#report-comment-id').val(iId);
	   $('#reportspan').html(iId);
	   $('#reportuserspan').html(user);
	   $('#report-url').val( 'http://' + window.location.hostname + window.location.pathname + '#comment' + iId );
	   $('#reporturlspan').html( 'http://' + window.location.hostname + window.location.pathname + '#comment' + iId );
	   $('#report-reason').focus()
	   $('#report-form').jqmShow();
	}

	this.send = function (form) {
		ls.ajaxSubmit(aRouter['ajax']+'commentreport', form, function(data) {
		   if (!data.bStateError) {
			   ls.msg.notice(data.sMsgTitle,data.sMsg);
			   $('#report-form').jqmHide();
		   } else {
			   ls.msg.error(data.sMsgTitle,data.sMsg);
		   }
		});
	}

	return this;
}).call(ls.commentreport || {},jQuery);
