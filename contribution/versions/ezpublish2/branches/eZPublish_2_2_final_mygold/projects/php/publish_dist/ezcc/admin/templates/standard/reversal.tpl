<h1>{intl-reversal}</h1>

<hr size="4" noshade="noshade" />

<p><a href="/cc/log" target="_blank">{intl-log}</a> {intl-information1} {intl-information2}</p>

<!-- BEGIN error_tpl -->
<!-- BEGIN input_error_tpl -->
<h3 class="error">{intl-missing_input}</h3>
<!-- END input_error_tpl -->
<!-- BEGIN gateway_error_tpl -->
<h3 class="error">{intl-error_text}: {error_text}</h3>
<h3 class="error">{intl-error_code}: {error_code}</h3>
<!-- END gateway_error_tpl -->
<!-- END error_tpl -->

<!-- BEGIN success_tpl -->
<p>{intl-success}</p>
<!-- END success_tpl -->

<form method="post"  action="/cc/cancel/">

<table cellspacing="4" cellpadding="0" border="0">
<tr>
	<th align="left">
	{intl-ref_id}:
	</th>
	<th align="left">
	{intl-amount}:
	</th>
</tr>
<tr>
	<td>
	<input type="text" size="32" name="RefID" value="" />
	</td>
	<td>
	<input type="text" size="10" name="Amount" value="" />
	</td>
</tr>
</table>

<br />

<input type="hidden" name="Action" value="Reversal" />

<input type="submit" value="{intl-start_reversal}" />

</form>