<h1>{intl-consultation_list_headline} {contact_name}</h1>
<hr noshade="noshade" size="4" />
<br />

<!-- BEGIN no_consultations_item_tpl -->
<p>{intl-consultation_no_consultations}:</p>
<!-- END no_consultations_item_tpl -->

<!-- BEGIN consultation_table_item_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="30%">{intl-consultation_date}:</th>
	<th>{intl-consultation_short_description}:</th>
	<th>{intl-consultation_status}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN consultation_item_tpl -->
<tr class="{bg_color}">
	<td class="small">
        {consultation_date}
	</td>
	<td>
        <a href="/contact/consultation/view/{consultation_id}">{consultation_short_description}</a>
	</td>
	<td>
        <a href="/contact/consultation/type/list/{consultation_status_id}">{consultation_status}</a>
	</td>

	<td width="1%">
	<a href="/contact/consultation/edit/{consultation_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{consultation_id}-red','','/admin/images/redigerminimrk.gif',1)"><img name="ezc{consultation_id}-red" border="0" src="/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>

	<td width="1%">
	<a href="/contact/consultation/delete/{consultation_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{consultation_id}-slett','','/admin/images/slettminimrk.gif',1)"><img name="ezc{consultation_id}-slett" border="0" src="/admin/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>	

</tr>
<!-- END consultation_item_tpl -->
</table>

<hr noshade="noshade" size="4" />

<table>
<tr>
<td>
<!-- BEGIN new_person_consultation_item_tpl -->
<form method="post" action="/contact/consultation/person/new/{person_id}">
<!-- END new_person_consultation_item_tpl -->
<!-- BEGIN new_company_consultation_item_tpl -->
<form method="post" action="/contact/consultation/company/new/{company_id}">
<!-- END new_company_consultation_item_tpl -->
<input class="stdbutton" type="submit" value="{intl-new_consultation}">
</form>
</td>
<td>
<form method="post" action="/contact/consultation/list">
<input class="stdbutton" type="submit" value="{intl-back}">
</form>
</td>
</tr>
</table>

<!-- END consultation_table_item_tpl -->
