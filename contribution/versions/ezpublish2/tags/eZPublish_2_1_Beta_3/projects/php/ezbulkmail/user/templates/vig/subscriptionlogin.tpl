<h1>{intl-subscription_login}</h1>

<hr noshade="noshade" size="4" />
<!-- BEGIN error_message_tpl -->
<h3 class="error">{error_message}</h3>
<!-- END error_message_tpl -->

<form action="/bulkmail/login" method="post">

<p class="boxtext">{intl-email}:</p>
<input type="text" name="Email">
<p class="boxtext">{intl-password}:</p>
<input type="password" name="Password">
<!-- BEGIN second_password_tpl -->
<p class="boxtext">{intl-password_confirm}:</p>
<input type="password" name="Password2">
<!-- END second_password_tpl -->
<!-- BEGIN new_tpl -->
<p><a href="/bulkmail/newsubscription">{intl-new_address}</a></p>
<!-- END new_tpl -->
<!-- BEGIN login_tpl -->
<p><a href="/bulkmail/login">{intl-normal_login}</a></p>
<!-- END login_tpl -->
<hr noshade="noshade" size="4" />

<input type="hidden" name="Action" value="{action_value}" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input type="submit" class="okbutton" name="Ok" value="{intl-ok}" /></td>
</tr>
</table>

</form>