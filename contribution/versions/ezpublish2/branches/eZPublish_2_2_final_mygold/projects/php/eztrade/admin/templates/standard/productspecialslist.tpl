<form action="/trade/productspecialslist/" method="post">
<h1>{intl-specialslist}</h1>

<hr noshade="noshade" size="4" />

<table width="100%" cellpadding="0" cellspacing="4" border="0">
  <tr>
    <th>{intl-special_name}</th>
    <th>{intl-product_numbers}</th>    
    <th>{intl-action}</th>    
    <th>&nbsp;</th>
  </tr>
  <!-- BEGIN product_special_list_tpl -->
  <tr>
    <td width="48%" class="{td_class}">{special_name}</td>
    <td width="48%" class="{td_class}">
      <!-- BEGIN product_numbers_tpl -->
      <a href="{www_dir}{index}/trade/productedit/productpreview/{product_number}/">{product_number}</a>&nbsp;
      <!-- END product_numbers_tpl -->      
    </td>
    <td width="1%" class="{td_class}"><a class="small" href="{www_dir}{index}/trade/productspecialsedit/{special_id}/">{intl-edit}</a>&nbsp;</td>    
    <td width="1%" class="{td_class}"><input type="checkbox" name="DeleteArray[]" value="{special_id}"></td>
  </tr>
  <!-- END product_special_list_tpl -->
  
  <!-- BEGIN no_specials_tpl -->
  <tr>
    <td colspan="4">{intl-no_specials}</td>
  </tr>
  <!-- END no_specials_tpl -->
</table>

<hr noshade="noshade" size="4" />
<input type="submit" name="Delete" value="{intl-delete_selected}" />
</form>



