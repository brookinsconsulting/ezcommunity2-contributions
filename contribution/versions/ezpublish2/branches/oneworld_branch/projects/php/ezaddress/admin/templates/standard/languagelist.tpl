<h1>{intl-list_headline}</h1>
<hr noshade="noshade" size="4" />
<br />
<form method="post" action="{www_dir}{index}/address/language/list/{offset}/">
<!-- BEGIN name_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th colspan="3">{intl-name}:</th>
</tr>

<!-- BEGIN name_item_tpl -->
<tr class="{bg_color}">
  <td width="98%">{language_name}</td>
  <td width="1%"><a href="{www_dir}{index}/address/language/edit/{language_id}/{offset}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{language_id}-red','','{www_dir}/admin/images/redigerminimrk.gif',1)"><img name="ezaa{language_id}-red" border="0" src="{www_dir}/admin/images/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
</td>
  <td width="1%"><input type="checkbox" name="LanguageArrayID[]" value="{language_id}"></td>
</tr>
<!-- END name_item_tpl -->
</table>
<!-- END name_list_tpl -->

<!-- BEGIN type_list_tpl -->

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/address/language/list/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/address/language/list/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/address/language/list/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->
</tr>
</table>
<!-- END type_list_tpl -->

<br />

<input type="submit" class="stdbutton" Name="NewLanguage" value="{intl-new_language}" />&nbsp;
<input type="submit" class="stdbutton" Name="DeleteLanguages" value="{intl-delete_languages}" />
</form>