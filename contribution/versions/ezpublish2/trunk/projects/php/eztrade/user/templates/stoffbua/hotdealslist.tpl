<!-- <h1>{intl-hot_deals}</h1> -->

<hr noshade="noshade" size="4" />
<!-- BEGIN category_list_tpl -->


<!-- BEGIN product_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="2" border="0">

<!-- BEGIN product_tpl -->
{begin_tr}
	<td>

	<a href="/trade/productview/{product_id}/{category_id}/"><h2>{product_name}</h2></a>

<p>{product_intro_text}</p>

<!-- BEGIN price_tpl -->
<p class="pris">{product_price}</p>
<!-- END price_tpl -->

	</td>
{end_tr}
<!-- END product_tpl -->

</table>

<!-- END product_list_tpl -->

