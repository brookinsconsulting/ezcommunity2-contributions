<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
    	<td align="left" valign="bottom">
     	   <h1>{intl-head_line}</h1>
     	</td>
   		<td align="right">
			<form action="/trade/search/" method="post">
	      		<input type="text" name="Query" />
	      		<input type="submit" name="search" value="{intl-search_button}" />
	        </form>
	    </td>
	</tr>
</table>
<hr noshade size="1" />
<h2>Ihre Suche nach "{query_string}" ergab:</h2>
<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<!-- BEGIN product_tpl -->
	<tr>
		<td>
			<a href="/trade/productview/{product_id}/{category_id}/"><h2>{product_name}</h2></a><br>
		    <!-- BEGIN image_tpl -->
    		<table align="right">
		    	<tr>
        			<td>
			        	<img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
			        </td>
			    </tr>
			    <tr>
        			<td>
		        		{thumbnail_image_caption}
        			</td>
			    </tr>
    		</table>
		    <!-- END image_tpl -->
	    	{product_intro_text}
			<br />
			<!-- BEGIN price_tpl -->
			<p class "pris">
			{product_price}
			</p>
			<!-- END price_tpl -->
		</td>
	</tr>
	<tr>
		<td><hr noshade size="1" /></td>
	</tr>
	<!-- END product_tpl -->
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
	<td>
	    <!-- BEGIN type_list_tpl -->
	    <br />
	    <table cellpadding="0" cellspacing="0" border="0">
		<tr>
		    <!-- BEGIN type_list_previous_tpl -->
		    <td>
			&lt;&lt;&nbsp;<a class="path" href="/trade/search/move/{url_text}/{item_previous_index}">{intl-previous}</a>&nbsp;|
		    </td>
		    <!-- END type_list_previous_tpl -->
		    
		    <!-- BEGIN type_list_previous_inactive_tpl -->
		    <td class="inactive">
			{intl-previous}&nbsp;
		    </td>
		    <!-- END type_list_previous_inactive_tpl -->

		    <!-- BEGIN type_list_item_list_tpl -->

		    <!-- BEGIN type_list_item_tpl -->
		    <td>
			&nbsp;<a class="path" href="/trade/search/move/{url_text}/{item_index}">{type_item_name}</a>&nbsp;|
		    </td>
		    <!-- END type_list_item_tpl -->

		    <!-- BEGIN type_list_inactive_item_tpl -->
		    <td class="inactive">
			&nbsp;{type_item_name}&nbsp;|
		    </td>
		    <!-- END type_list_inactive_item_tpl -->

		    <!-- END type_list_item_list_tpl -->

		    <!-- BEGIN type_list_next_tpl -->
		    <td>
			&nbsp;<a class="path" href="/trade/search/move/{url_text}/{item_next_index}">{intl-next}</a>&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_tpl -->

		    <td class="inactive">
			{intl-next}&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_inactive_tpl -->
		</tr>
	    </table>
	    <!-- END type_list_tpl -->
	</td>
    </tr>
</table>
