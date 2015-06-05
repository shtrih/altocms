{strip}
	{assign var="oUser" value=$oComment->getUser()}
	<li>
		<a href="#"
		   class="link-dotted"
		   title="{$aLang.plugin.commentreport.title}"
		   onclick="ls.commentreport.open({$oComment->getCommentId()},'{$oUser->getLogin()}');return false;">{$aLang.plugin.commentreport.report}</a>
	</li>
{/strip}