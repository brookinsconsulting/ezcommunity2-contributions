<h1>{intl-log}</h1>

<form method="post"  action="/cc/log/">
<hr noshade size="4" />
<br />


	    <select name="LogSelect">
	    <option value="unhandled">{intl-unhandled}</option>
	    <option value="cutover">{intl-cutovered}</option>
	    <option value="cancel">{intl-canceled}</option>
	    <option value="invalid">{intl-invalid}</option>
	    </select>
	    &nbsp;<input type="submit" name="Show" value="{intl-show}" />

    <table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
		<tr> 
			<th>{intl-type}:</th>
			<th>{intl-order_id}:</th>
			<th>{intl-date}:</th>
			<th>{intl-time}:</th>
			<th>{intl-amount}:</th>
			<th>{intl-blz}:</th>			
			<th>{intl-account}:</th>
			<th>{intl-rc_text}:</th>
			<th>{intl-action}:</th>
		</tr>
		<!-- BEGIN log_item_tpl -->
		<tr> 
			<td>
			<input type="hidden" name="CardType[]" value="{log_type}" />
			<input type="hidden" name="PreOrderID[]" value="{log_order}" />
			<input type="hidden" name="TA_ID[]" value="{log_id}" />
			<input type="hidden" name="Date[]" value="{log_date}" />
			<input type="hidden" name="Time[]" value="{log_time}" />
			<input type="hidden" name="FileAmount[]" value="{log_amount}" />
			<input type="hidden" name="RCCode[]" value="{log_rc_code}" />
			<input type="hidden" name="RCText[]" value="{log_rc_text}" />
			<input type="hidden" name="CardTypeID[]" value="{card_type}" />
			<input type="hidden" name="RefID[]" value="{log_id}" />
			<input type="hidden" name="Amount[]" value="{amount}" />
			{log_type}</td>
			<td>{log_order}</td>
			<td>{log_date}</td>
			<td>{log_time}</td>
			<td>{log_amount}</td>
			<td>{log_blz}</td>			
			<td>{log_accountnr}</td>			
			<td>{log_rc_text}</td>
			<td>
			  <!-- BEGIN button_item_tpl -->
			  <input type="submit" name="CancelButton{cancel_id}" value="{intl-cancel}" />
			  <!-- END button_item_tpl -->
			  &nbsp;
			</td>
		</tr>
		<!-- END log_item_tpl -->
		<input type="hidden" name="CheckCancelArray" value="{check_cancel_array}" />
	</table>

<!-- BEGIN message_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN error_msg_tpl -->
<tr>
       <td>
       <p>{intl-error_text}: {error_text}</p>
       <p>{intl-error_code}: {error_code}</p>
       </td>
</tr>
<!-- END error_msg_tpl -->
<!-- BEGIN cutover_success_tpl -->
<tr>
       <td>
       <p>{intl-cutover_success}</p>
       </td>
</tr>
<!-- END cutover_success_tpl -->
<!-- BEGIN cancel_success_tpl -->
<tr>
       <td>
       <p>{intl-cancel_success}</p>
       </td>
</tr>
<!-- END cancel_success_tpl -->
</table>
<!-- END message_list_tpl -->

</form>

<br />
