<table width="100%" border="0">
<tr>
        <td valign="bottom">
        <h1>{intl-headline}</h1>
        </td>
        <td rowspan="2" align="right">
        <form action="/contact/search/company" method="post">
        <input type="text" name="SearchText" size="12" />       
        <input type="submit" value="{intl-search}" />
        </form>
        </td>
</tr>
</table>

<hr noshade="noshade" size="4"/ >

<!-- BEGIN path_tpl -->

<img src="/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/contact/company/list/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/images/path-slash.gif" height="10" width="20" border="0">

<a class="path" href="/contact/company/list/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4"/ >

<!-- BEGIN category_list_tpl -->
<h2>{intl-categories}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href=/contact/company/list/{category_id}>{category_name}</a>
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<!-- END category_list_tpl -->


<!-- BEGIN company_list_tpl -->
<h2>{intl-companies}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>{intl-image}:</th>
	<td align="right"><b>{intl-telephone}:</b></td>
</tr>
<!-- BEGIN company_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/contact/company/view/{company_id}/">{company_name}</a>
	</td>
	<td class="{td_class}">

	<!-- BEGIN image_view_tpl -->
    <img src="{logo_image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
	<!-- END image_view_tpl -->

	<!-- BEGIN no_image_tpl -->
<!--	<p>{intl-no_image}</p> -->
	<!-- END no_image_tpl -->	
	</td>	 
<!-- BEGIN phone_item_tpl -->

	<td class="{td_class}" align="right">
	{company_telephone}
	</td>
<!-- END phone_item_tpl -->

</tr>

<!-- END company_item_tpl -->
</table>
<!-- END company_list_tpl -->


