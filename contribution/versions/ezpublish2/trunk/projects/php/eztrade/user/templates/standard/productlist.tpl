<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{intl-productlist}</h1>
	</td>
     <td align="right">
	 <form action="/{module}/search/" method="post">
	       <input type="text" name="Query">
	       <input type="submit" name="search" value="{intl-search_button}">
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4"/>

<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="/{module}/{module_list}/0/">{intl-top}</a>

<!-- BEGIN path_tpl -->
<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="/{module}/{module_list}/{category_id}/">{category_name}</a>
<!-- END path_tpl -->

<hr noshade="noshade" size="4" />
<!-- BEGIN category_list_tpl -->

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-category}:</th>
	<th>{intl-description}:</th>
</tr>

<!-- BEGIN category_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/{module}/{module_list}/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
</tr>
<!-- END category_tpl -->

</table>

<!-- END category_list_tpl -->


<!-- BEGIN product_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN product_tpl -->
<tr>
	<td>

	<a href="/{module}/{module_view}/{product_id}/{category_id}/"><h2>{product_name}</h2></a>

<!-- BEGIN product_image_tpl -->
    <table align="right">
    <tr>
        <td>
	<a href="/{module}/{module_view}/{product_id}/{category_id}/">
        <img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
	</a>
        </td>
    </tr>
    <tr>
        <td class="pictext">
        {thumbnail_image_caption}
        </td>
    </tr>
    </table>
<!-- END product_image_tpl -->

<p>{product_intro_text}</p>

<!-- BEGIN price_tpl -->
<p class="pris">{product_price}</p>
<!-- END price_tpl -->

	</td>
</tr>
<!-- END product_tpl -->

</table>

<!-- END product_list_tpl -->

