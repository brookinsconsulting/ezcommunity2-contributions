<form method="post" action="/exchange/product/view/{product_id}/{category_id}" enctype="multipart/form-data">

<h2>{intl-product_match}</h2>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN match_offer_tpl -->
<p>{intl-offer_match}{intl-site}</p>
<br />
{intl-your_offer}:
<!-- END match_offer_tpl -->
<!-- BEGIN match_quote_tpl -->
<p>{intl-quote_match}{intl-site}</p>
<br />
{intl-your_quote}:
<!-- END match_quote_tpl -->
<p>{intl-product}: {product_name}</p>
<p>{intl-quantity}: {quantity}</p>
<p>{intl-price}: {price}</p>
<p>{intl-type}: {type}</p>

<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="Back" value="{intl-back}">
</form>
