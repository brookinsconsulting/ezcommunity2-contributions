<form action="{www_dir}{index}/article/articleedit/pollist/{article_id}/" method="post">

<h1>{intl-poll_list}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_polls_item_tpl -->
<div>{intl-no_polls_exist}</div>
<!-- END no_polls_item_tpl -->

<!-- BEGIN poll_list_tpl -->
<select name="selectedPollID">
<option value="0">{intl-no_poll_selected}</option>
<!-- BEGIN poll_item_tpl -->
<option value="{poll_id}" {selected}>{poll_name}</option>
<!-- END poll_item_tpl -->
</select>
<!-- END poll_list_tpl -->

<br/>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
</tr>
</table>

</form>
