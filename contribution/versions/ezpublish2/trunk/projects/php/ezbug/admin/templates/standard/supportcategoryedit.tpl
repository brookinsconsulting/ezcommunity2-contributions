<form method="post" action="{www_dir}{index}/bug/support/category/{action}/{id}">

<h1>{intl-head_line}</h1>

<hr noshade size="4" />

<!-- BEGIN empty_error_tpl -->
<h3 class="error">{intl-empty_error}</h3><br>
<!-- END empty_error_tpl -->
<!-- BEGIN email_error_tpl -->
<h3 class="error">{intl-email_error}</h3><br>
<!-- END email_error_tpl -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-name}:</p>
	<input type="text" class="box" size="20" name="Name" value="{name}" />
	<br /><br />
	</td>
</tr>
<tr>
	<td>
	<p class="boxtext">{intl-module}:</p>
	<select name="BugModuleID">
        <!-- BEGIN category_element_tpl -->
	<option value="{category_id}" {selected}>{category_name}</option>
	<!-- END category_element_tpl -->
        </select>

	<br /><br />
	</td>
</tr>
<tr>
	<td colspan="2">
	<p class="boxtext">{intl-email}:</p>
	<input type="text" class="box" size="40" name="Email" value="{email}" />
	<br /><br />
	</td>
</tr>
<tr>
        <td colspan="2">
	<p class="boxtext">{intl-replyto}:</p>
	<input type="text" class="box" size="40" name="ReplyTo" value="{replyto}" />
	<br /><br />
	</td>
</tr>
<tr>
	<td colspan="2">
	<p class="boxtext">{intl-password}:</p>
	<input type="password" class="box" size="20" name="Password" value="{password}" />
	<br /><br />
	</td>
</tr>
<tr>
	<td colspan="2">
	<p class="boxtext">{intl-mailserver}:</p>
	<input type="text" class="box" size="20" name="MailServer" value="{mailserver}" />
	<br /><br />
	</td>
</tr>
<tr>
	<td colspan="2">
	<p class="boxtext">{intl-mailserverport}:</p>
	<input type="text" class="box" size="20" name="MailServerPort" value="{mailserverport}" />
	<br /><br />
	</td>
</tr>
<tr>
	<td colspan="2">
	<input type="checkbox" name="SupportNo" {supportno_checked} />{intl-supportno}
	</td>
</tr>
</table>
	
<br />

<hr noshade size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input type="hidden" name="ID" value="{id}" />
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</td>
</table>
</form>
