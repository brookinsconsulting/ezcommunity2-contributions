<form action="{www_dir}{index}/trade/voucherinformation/{url_arg}" method="post">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">

<h1>{intl-description}</h1>

<!-- BEGIN email_tpl -->
<tr>
<th>{intl-email}</th>
</tr>
<tr>
      <td>
      <input type="text" name="Email" value="{email_var}" />
      </td>
</tr>
<th>{intl-text}</th>
<tr>
      <td>
      <textarea cols="60" name="Description" rows="8">{email_text}</textarea>
      </td>
</tr>
<!-- END email_tpl -->

<!-- BEGIN smail_tpl -->
<tr>
<th>{intl-name}</th>
</tr>
<tr>
      <td>
      <input type="text" name="Name" value="{name_value}" />
      </td>
</tr>
</tr>
<tr>
      <td>
      <p class="boxtext">{intl-street}:</p>
      <input type="text" size="20" name="Street1" value="{street1_value}"/><br />
      <input type="text" size="20" name="Street2" value="{street2_value}"/>
      </td>
</tr>
<tr>
      <td>
      <p class="boxtext">{intl-zip}:</p>
      <input type="text" size="20" name="Zip" value="{zip_value}"/>
      </td>
</tr>
<tr>
      <td>
      <p class="boxtext">{intl-place}:</p>
      <input type="text" size="20" name="Place" value="{place_value}"/>
      </td>
</tr>
<tr>
      <td>
      <!-- BEGIN country_tpl -->
      <p class="boxtext">{intl-country}:</p>
      <select name="CountryID[]" size="5">
      <!-- BEGIN country_option_tpl -->
      <option {is_selected} value="{country_id}">{country_name}</option>
      <!-- END country_option_tpl -->
      </select>
      <!-- END country_tpl -->
      </td>
</tr>

<th>{intl-text}</th>
<tr>
      <td>
      <textarea name="Description" cols="60" rows="8">{email_text}</textarea>
      </td>
</tr>
<!-- END smail_tpl -->
</table>


<input type="hidden" name="MailType" value="{mail_type}" />
<input type="hidden" name="ProductID" value="{product_id}" />

<!-- BEGIN next_tpl -->
<input type="submit" name="Next" value="{intl-next}" />
<!-- END next_tpl -->

<!-- BEGIN ok_tpl -->
<input type="submit" name="OK" value="{intl-ok}" />
<!-- END ok_tpl -->


</form>