<h1>{special_name}</h1>

<hr noshade="noshade" size="1" />

<p>{special_description}</p>

<hr noshade="noshade" size="1" />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <!-- BEGIN product_tpl -->
  <tr>
    <td colspan="2">
      <a href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/"><h2>{product_name}</h2></a><br />
    </td>
  </tr>
  <tr>
    <td colspan="2" class="spacer2">&nbsp;</td>
  </tr>
  <tr>
    <td width="99%" valign="top">
      {product_intro_text}
    </td>
    <td>
      <!-- BEGIN image_tpl -->
      <table align="right">
        <tr>
          <td>
            <table border="0" cellspacing="1" cellpadding="0" bgcolor="#003366">
              <tr>
	        <td><a href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/"><img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" /></a></td>
	      </tr>
	    </table>  
	  </td>
	</tr>
	<tr>
          <td>
	    {thumbnail_image_caption}
          </td>
	</tr>
      </table>
      <!-- END image_tpl -->
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <!-- BEGIN price_tpl -->
      <p class "pris">
	{product_price}
      </p>
      <!-- END price_tpl -->
    </td>
  </tr>
  <tr>
    <td colspan="2"><hr noshade="noshade" size="1" /></td>
  </tr>
  <!-- END product_tpl -->
</table>

