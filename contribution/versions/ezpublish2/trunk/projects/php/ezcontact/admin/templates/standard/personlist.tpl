<table width="100%" border="0">
<tr>
	<td valign="bottom">
	    <h1>{intl-person_list_headline}</h1>
	</td>
	<td rowspan="2" align="right">
	    <form action="/contact/person/search/" method="post">
	    	<input type="text" name="SearchText" size="12" value="{search_form_text}" />
		<input type="submit" value="{intl-search}" />
	    </form>
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />
<br />

<!-- BEGIN no_persons_tpl -->
<h2>{intl-no_persons_found}</h2>
<!-- END no_persons_tpl -->

<!-- BEGIN person_table_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-person_name}:</th>
	<th>{intl-state}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN person_item_tpl -->
<tr class="{bg_color}">
	<td>
        <a href="/contact/person/view/{person_id}">{person_lastname}, {person_firstname}&nbsp;</a>
	</td>

	<!-- BEGIN person_state_tpl -->
	<td>
        <a href="/contact/project/person/list/{state_id}">{person_state}</a>
	</td>
	<!-- END person_state_tpl -->

	<!-- BEGIN no_person_state_tpl -->
	<td>
        <a href="/contact/project/person/list/">{intl-no_state}</a>
	</td>
	<!-- END no_person_state_tpl -->

	<td width="1%">
	<a href="/contact/consultation/person/new/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezn{person_id}-red','','/images/addminimrk.gif',1)"><img name="ezn{person_id}-red" border="0" src="/images/addmini.gif" width="16" height="16" align="top"></a>
	</td>

	<td width="1%">
	<a href="/contact/person/edit/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{person_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezc{person_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>

	<td width="1%">
	<a href="/contact/person/delete/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{person_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezc{person_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>	

</tr>
<!-- END person_item_tpl -->
</table>

<!-- BEGIN person_list_tpl -->
<table>
<tr>
	<!-- BEGIN person_list_previous_tpl -->
	<td>
	<a href="/contact/person/{action}/{item_previous_index}/{search_text}">{intl-previous}</a>
	</td>
	<!-- END person_list_previous_tpl -->

	<!-- BEGIN person_list_previous_inactive_tpl -->
	<td>
	{intl-previous}
	</td>
	<!-- END person_list_previous_inactive_tpl -->

	<!-- BEGIN person_list_item_tpl -->
	<td>
	<a href="/contact/person/{action}/{item_index}/{search_text}">{item_name}</a>
	</td>
	<!-- END person_list_item_tpl -->

	<!-- BEGIN person_list_next_tpl -->
	<td>
	<a href="/contact/person/{action}/{item_next_index}/{search_text}">{intl-next}</a>
	</td>
	<!-- END person_list_next_tpl -->

	<!-- BEGIN person_list_next_inactive_tpl -->
	<td>
	{intl-next}
	</td>
	<!-- END person_list_next_inactive_tpl -->

</tr>
</table>
<!-- END person_list_tpl -->

<!-- END person_table_tpl -->

<form method="post" action="/contact/person/new">

<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" value="{intl-new_person}">
</form>
