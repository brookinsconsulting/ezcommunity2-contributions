<table width="100%">
<tr>
	<td>
	<h1>{intl-search} - {search_text}</h1>
	</td>
	<td>
	<form method="post" action="/datamanager/search/">

	<input type="text" name="SearchText" value="{search_text}" />

	<input class="stdbutton" type="submit" name="Search" value="{intl-search}" />

	</form>
	</td>
</tr>
</table>


<hr size="4" noshade="noshade" />


<!-- BEGIN item_list_tpl -->

<table width="100%" cellpadding="4" cellspacing="2" >
<tr>
	<th>
	<p class="boxtext">{intl-item_name}:</p>
	</th>
</tr>

<!-- BEGIN item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/datamanager/itemedit/{item_id}/">{item_name}</a>
	</td>
</tr>
<!-- END item_tpl -->

</table>

<hr size="4" noshade="noshade" />

<!-- END item_list_tpl -->


<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/datamanager/search/{search}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/datamanager/search/{search}/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/datamanager/search/{search}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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



