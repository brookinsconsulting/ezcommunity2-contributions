<h1>{intl-type_list} - {current_type_name}</h1>

<hr size="4" noshade="noshade" />
<!-- BEGIN type_list_tpl -->

<table width="100%" cellpadding="4" cellspacing="2" >
<tr>
	<th>
	<p class="boxtext">{intl-type_name}:</p>
	</th>
</tr>

<!-- BEGIN type_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/datamanager/typelist/{type_id}/" >{type_name}</a>
	</td>

</tr>
<!-- END type_tpl -->

</table>

<hr size="4" noshade="noshade" />

<!-- END type_list_tpl -->


<!-- BEGIN item_list_tpl -->

<b><a href="/datamanager/typelist/0/"><< {intl-type_list}</a></b>

<table width="100%" cellpadding="4" cellspacing="2" >
<tr>
	<th>
	<p class="boxtext">{intl-item_name}:</p>
	</th>
</tr>

<!-- BEGIN item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/datamanager/itemview/{item_id}/">{item_name}</a>
	</td>

</tr>
<!-- END item_tpl -->

</table>

<hr size="4" noshade="noshade" />

<!-- END item_list_tpl -->
