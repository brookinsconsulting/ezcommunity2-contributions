<tr>
	<td class="menuhead" bgcolor="#c82828">{intl-contact_headline}</td>
</tr>

<tr>
	<td>
	<a class="menu" href="/contact/companycategory/list">
	<img src="/images/dot.gif" width="12" heigth="10" border="0">{intl-company_list}
	</a>
	</td>
</tr>

<tr>
	<td>
	<a class="menu" href="/contact/person/list">
	<img src="/images/dot.gif" width="12" heigth="10" border="0">{intl-person_list}
	</a>
	</td>
</tr>

<tr>
	<td>
	<a class="menu" href="/contact/consultation/list">
	<img src="/images/dot.gif" width="12" heigth="10" border="0">{intl-consultation_list}
	</a>
	</td>
</tr>

<!-- BEGIN last_consultations_item_tpl -->
<tr>
	<td class="menuhead" bgcolor="#c82828">{intl-consultation_headline}</td>
</tr>

<!-- BEGIN consultation_item_tpl -->
<tr>
    <td>
	<a class="menu" href="/contact/consultation/view/{consultation_id}">
	<!-- BEGIN consultation_person_item_tpl -->
	<img src="/images/dot.gif" width="12" heigth="10" border="0">{contact_lastname}, {contact_firstname}: {consultation_desc}
	<!-- END consultation_person_item_tpl -->
	<!-- BEGIN consultation_company_item_tpl -->
	<img src="/images/dot.gif" width="12" heigth="10" border="0">{contact_name}: {consultation_desc}
	<!-- END consultation_company_item_tpl -->
	</a>
    </td>
</tr>
<!-- END consultation_item_tpl -->

<!-- END last_consultations_item_tpl -->
