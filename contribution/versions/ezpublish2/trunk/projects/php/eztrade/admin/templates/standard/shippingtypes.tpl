<h1>{intl-shipping_types}</h1>

<hr noshade="noshade" size="4" />

<form action="/trade/shippingtypes/" method="post">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th >
	&nbsp;
	</th>

 	<!-- BEGIN type_item_tpl -->
	<th colspan="2">

	 <input type="hidden" name="TypeID[]" value="{type_id}" />
	 <input type="radio" name="DefaultTypeID" {default_checked} value="{type_id}" />

	 <input type="text" size="15" name="TypeName[]" value="{shipping_type_name}" />

	 <input type="checkbox" name="DeleteType[]" value="{type_id}" />

	</th>
 	<!-- END type_item_tpl -->
<tr>

<!-- BEGIN group_item_tpl -->
<tr>
	<th class="{td_class}">
	 <input type="hidden" name="GroupID[]" value="{group_id}" />
	 <input type="text" size="15" name="GroupName[]" value="{shipping_group_name}" />
	 <input type="checkbox" name="DeleteGroup[]" value="{group_id}" />

	</th>
       <!-- BEGIN type_group_item_tpl -->
	<td class="{td_class}">
	  <input type="hidden" name="ValueGroupID[]" value="{value_group_id}" />
	  <input type="hidden" name="ValueTypeID[]" value="{value_type_id}" />

	  <input type="text" size="6" name="StartValue[]" value="{start_value}" />
	</td>
	<td class="{td_class}">
	  <input type="text" size="6" name="AddValue[]" value="{add_value}" />
	</td>
       <!-- END type_group_item_tpl -->
</tr>
<!-- END group_item_tpl -->
</table>

<hr noshade size="4" />


<input type="submit" name="Store" value="{intl-store}" />

<input type="submit" name="AddType" value="{intl-add_shipping_type}" />
<input type="submit" name="AddGroup" value="{intl-add_shipping_group}" />

<input type="submit" name="Delete" value="{intl-delete_selected}" />

<hr noshade size="4" />

</form>