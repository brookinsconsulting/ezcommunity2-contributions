<h1>{product_name} - {product_price}</h1>
<hr noshade="noshade" size="1" />

<p>{intl-description}</p>
<br />

<form action="{www_dir}{index}/trade/voucherinformation/{product_id}" method="post">
<!-- BEGIN email_tpl -->
<b>{intl-email}:</b><br />
<input type="text" name="Email" value="{email_var}" />
<br /><br />

<b>{intl-text}:</b><br />
<textarea cols="60" name="Description" rows="8">{email_text}</textarea>
<!-- END email_tpl -->

<!-- BEGIN smail_tpl -->
<b>{intl-name}:</b><br />
<input type="text" name="Name" value="{name_value}" />

<b>{intl-street}:</b><br />
<input type="text" size="20" name="Street1" value="{street1_value}"/><br />
<input type="text" size="20" name="Street2" value="{street2_value}"/><br />
<br /><br />

<b>{intl-zip}:</b>
<input type="text" size="20" name="Zip" value="{zip_value}"/>
<br /><br />

<p class="boxtext">{intl-place}:</p><br />
<input type="text" size="20" name="Place" value="{place_value}"/>
<br /><br />

<!-- BEGIN country_tpl -->
<b>{intl-country}:</b>
<select name="CountryID[]" size="5">
  <!-- BEGIN country_option_tpl -->
  <option {is_selected} value="{country_id}">{country_name}</option>
  <!-- END country_option_tpl -->
</select>
<!-- END country_tpl -->
<br /><br />

<b>{intl-text}:</b><br />
<textarea name="Description" cols="40"" rows="8">{email_text}</textarea>
<!-- END smail_tpl -->

<hr noshade="noshade" size="1" />

<input type="hidden" name="MailMethod" value="{mail_method}" />
<input type="hidden" name="ProductID" value="{product_id}" />
<input type="hidden" name="PriceRange" value="{price_range}" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />


</form>