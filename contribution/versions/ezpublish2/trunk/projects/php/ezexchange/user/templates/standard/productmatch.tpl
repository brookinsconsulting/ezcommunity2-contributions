<form method="post" action="/exchange/product/view/{product_id}/{category_id}" enctype="multipart/form-data">

<h1>{intl-product_match}</h1>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN match_offer_tpl -->
<h2>{intl-offer_match}{intl-site}</h2>
<p>{intl-your_offer}:</p>
<!-- END match_offer_tpl -->
<!-- BEGIN match_quote_tpl -->
<h2>{intl-quote_match}{intl-site}</h2>
<p>{intl-your_quote}:</p>
<!-- END match_quote_tpl -->
<p><b>{intl-product}:</b> {product_name}</p>
<p><b>{intl-quantity}:</b> {quantity}</p>
<p><b>{intl-remain_quantity}:</b> {remain_quantity}</p>
<p><b>{intl-price}:</b> {price}</p>
<!-- BEGIN match_type_all_tpl -->
<p><b>{intl-type}:</b> {intl-all_type}</p>
<!-- END match_type_all_tpl -->
<!-- BEGIN match_type_any_tpl -->
<p><b>{intl-type}:</b> {intl-any_type}</p>
<!-- END match_type_any_tpl -->

<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="Back" value="{intl-back}">
</form>
