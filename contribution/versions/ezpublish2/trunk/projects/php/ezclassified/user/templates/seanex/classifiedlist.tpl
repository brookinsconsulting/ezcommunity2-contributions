<h1>Stillingsannonser</h1>

<hr noshade="noshade" size="4"/ >

<!-- BEGIN path_tpl -->

<img src="/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/classified/classifiedlist/list/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/images/path-slash.gif" height="10" width="20" border="0">

<a class="path" href="/classified/classifiedlist/list/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4"/ >

<!-- BEGIN category_list_tpl -->
<h2>{intl-categories}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN category_item_tpl -->
<tr>
	<td> <!-- class="{td_class}" -->
	<a href=/classified/classifiedlist/list/{category_id}>{category_name}</a>
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<!-- END category_list_tpl -->


<!-- BEGIN classified_list_tpl -->

<h2>{intl-companies}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>{intl-company}:</th>
</tr>
<!-- BEGIN classified_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/classified/view/{classified_id}/">{classified_name}</a>
	</td>
	<td class="{td_class}">
	{company_name}
	</td>

</tr>
<!-- END classified_item_tpl -->
</table>
<!-- END classified_list_tpl -->


