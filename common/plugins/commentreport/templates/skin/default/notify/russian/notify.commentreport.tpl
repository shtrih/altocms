Пользователь <b>{$oUser->getLogin()}</b> уведомляет о неуместном комментарии.
<br><br>
ID: #{$oComment->getCommentId()}<br>
URL: <a href="{$url|escape}">{$url|escape}</a><br>
Автор комментария: <b>{$oComment->getUser()->getLogin()}</b>
{if $reason}
<br>
Причина: {$reason|escape}
{/if}


<br><br>
С уважением, администрация сайта <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>