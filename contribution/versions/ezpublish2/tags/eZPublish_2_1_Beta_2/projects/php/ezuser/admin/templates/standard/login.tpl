<!-- BEGIN error_message_tpl -->
<h3 class="error">{intl-error_login}</h3>
<!-- END error_message_tpl -->

<!-- BEGIN max_message_tpl -->
<h3 class="error">{intl-max_logins}</h3>
<!-- END max_message_tpl -->

<form method="post" action="/user/login/login/">

<h1>{intl-head_line}</h1>

<hr noshade size="4"/>

<p class="boxtext">{intl-username}:</p>
<input type="text" size="20" name="Username"/>

<p class="boxtext">{intl-password}:</p>
<input type="password" size="20" name="Password"/><br>

<input type="hidden" name="RefererURL" value="{referer_url}" />

<br></br>

<hr noshade size="4"/>

<input class="okbutton" type="submit" value="{intl-ok}" />

</form>
