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
<p><b>{intl-price}:</b> {price}</p>
<p><b>{intl-type}:</b> {type}</p>

<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="Back" value="{intl-back}">
</form>
