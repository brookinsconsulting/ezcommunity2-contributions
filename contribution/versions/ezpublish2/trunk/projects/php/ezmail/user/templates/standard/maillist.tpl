<!-- BEGIN header_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line} - {current_folder_name}</h1>
	</td>
<!--	<td align="right">
	<form action="/article/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td> -->
</tr>
</table>

<hr noshade="noshade" size="4" />
<!-- END header_item_tpl -->

<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="">
<a class="path" href="/mail/folder/0/">{intl-top_level}</a>

<!-- BEGIN path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="">
<a class="path" href="/mail/folder/{folder_id}/">{folder_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />


<!-- BEGIN folder_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-folder}:</th>
	<th>{intl-description}:</th>
</tr>

<!-- BEGIN folder_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/mail/folder/{folder_id}/">{folder_name}</a>&nbsp;
	</td>
</tr>
<!-- END folder_item_tpl -->
</table>
<br />
<!-- END folder_list_tpl -->


<!-- BEGIN mail_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN mail_item_tpl -->
<tr>
  <td class="{td_class}">
  <a href="/mail/view/{mail_id}/">{from_adress}</a>&nbsp;
  </td>
  <td class="{td_class}">
  {mail_subject}&nbsp;
  </td>
</tr>
<!-- END mail_item_tpl -->
</table>
<!-- END mail_list_tpl -->


