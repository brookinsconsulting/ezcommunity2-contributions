<h1>{intl-header}</h1>
<hr noshade="noshade" size="4" />

<!-- BEGIN form_tpl -->
<form action="/trade/productspecialsedit/{special_id}/" method="post">  
  <b>{intl-name}:</b><br />
  <input type="text" name="SpecialName" value="{special_name}" /><br /><br />
  
  <b>{intl-description}:</b><br />    
  <textarea name="Description">{description}</textarea><br /><br />

  <b>{intl-numbers}:</b><br />    
  <input type="text" name="ProductNumbers" value="{product_numbers}" /><br /><br />
  
  <hr noshade="noshade" size="4" />
  <input type="submit" name="Update" value="{intl-update}" />
  
</form>
<!-- END form_tpl -->
<!-- BEGIN result_tpl -->
<form action="/trade/productspecialslist/" method="post">  
  {intl-success}
  <hr noshade="noshade" size="4" />
  <input type="submit" name="Back" value="{intl-back}" />
</form>
<!-- END result_tpl -->
<!-- BEGIN error_tpl -->
<form action="/trade/productspecialsedit/" method="post">  
  {intl-error}
  <hr noshade="noshade" size="4" />
  <input type="submit" name="Back" value="{intl-back}" />
</form>
<!-- END error_tpl -->