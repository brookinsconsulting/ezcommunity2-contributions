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
<tr>
<td></td>
<td><a href="/datamanager/advancedsearch/">{intl-advanced_search}</a></td>
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
	<a href="/datamanager/itemview/{item_id}/">{item_name}</a>
	</td>
</tr>
<!-- END item_tpl -->

</table>

<hr size="4" noshade="noshade" />

<!-- END item_list_tpl -->
