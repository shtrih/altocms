{strip}
	<div class="modal modal-login" id="report-form">
		<header class="modal-header">
			<h3>{$aLang.plugin.commentreport.title}</h3>
			<a href="#" class="close jqmClose"></a>
		</header>

		<div class="modal-content">

			<form method="POST" action="" id="report-content" enctype="multipart/form-data" onsubmit="return false;">

				<p>{$aLang.plugin.commentreport.comment_id}: #<span id="reportspan"></span><br>
				{$aLang.plugin.commentreport.user}: <span class="user" id="reportuserspan"></span><br>
				{$aLang.plugin.commentreport.url}: <span id="reporturlspan"></span>
				</p>

				<input type="hidden" name="comment_id" id="report-comment-id" value=""/>
				<input type="hidden" name="url" id="report-url" value=""/>

				<p>
					<label for="report-reason">{$aLang.plugin.commentreport.reason}:</label>
					<textarea name="reason" id="report-reason" class="input-text input-width-full" rows="5"></textarea>
				</p>

				<button type="submit"  class="button button-primary" onclick="ls.commentreport.send('report-content');">{$aLang.plugin.commentreport.send}</button>
				<button type="submit"  class="button jqmClose">{$aLang.uploadimg_cancel}</button>
			</form>

			{if !defined('ALTO_VERSION')}<br><a style="float:right;color:#999;font-size: 11px;" href="http://altocms.ru/" title="AltoCMS - движок для создания сообществ">Плагин от AltoCMS</a>{/if}

		</div>

		</div>
{/strip}