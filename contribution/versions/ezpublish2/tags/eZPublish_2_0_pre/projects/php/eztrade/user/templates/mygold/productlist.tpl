<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td>
			<h1>{intl-productlist}</h1>
		</td>
    	<td align="right">
			<form action="/trade/search/" method="post">
				<input type="text" name="Query" size="8" />
				<input class="okbutton" type="submit" name="search" value="{intl-search_button}" />
			</form>
		</td>
	</tr>
</table>
<hr noshade="noshade" size="1" />
<img src="/eztrade/images/path-arrow.gif" height="10" width="15" border="0" alt=""/>
<a class="nav" href="/trade/productlist/0/">{intl-top}</a>
<!-- BEGIN path_tpl -->
<img src="/eztrade/images/path-slash.gif" height="10" width="20" border="0" alt=""/>
<a class="nav" href="/trade/productlist/{category_id}/">{category_name}</a>
<!-- END path_tpl -->
<hr noshade="noshade" size="1" />
<!-- BEGIN category_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
	<tr>
		<th align="left">{intl-category}:</th>
		<th align="left">{intl-description}:</th>
	</tr>
	<!-- BEGIN category_tpl -->
	<tr>
		<td class="{td_class}">
			<a class="nav" href="/trade/productlist/{category_id}/">{category_name}</a>&nbsp;
		</td>
		<td class="{td_class}">
			{category_description}&nbsp;
		</td>
	</tr>
	<!-- END category_tpl -->
</table>
<hr noshade="noshade" size="1" />
<!-- END category_list_tpl -->
<!-- BEGIN product_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<!-- BEGIN product_tpl -->
	<tr>
		<td>
			<h2><a href="/trade/productview/{product_id}/{category_id}/">{product_name}</a></h2>
			<!-- BEGIN product_image_tpl -->
			<table align="right">
			    <tr>
			        <td>
						<a href="/trade/productview/{product_id}/{category_id}/">
				        <img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" alt="{thumbnail_image_caption}"/></a>
			        </td>
			    </tr>
			    <tr>
			        <td class="pictext">{thumbnail_image_caption}</td>
				</tr>
			</table>
			<!-- END product_image_tpl -->
			<p>{product_intro_text}</p>
			<!-- BEGIN price_tpl -->
			<p class="pris">{product_price}</p><br />
			<!-- END price_tpl -->
		</td>
	</tr>
	<tr>
		<td colspan="2"><hr noshade="noshade" size="1" /></td>
	</tr>
	<!-- END product_tpl -->
</table>
<!-- END product_list_tpl -->