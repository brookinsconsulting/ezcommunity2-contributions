<tr>
<td bgcolor="#dddddd">
<a href="javascript:UpdateAddress( '{address_street1}', '{address_street2}', '{address_zip}', '{address_id}', '{address_type_id}' )">{address_street1}<br> {address_street2} {address_zip}</a> 
</td>
<td bgcolor="#dddddd">
{address_type_name}
</td>
<td bgcolor="#dddddd">
<a href="#" onClick="verify( 'Slette adresse?', 'index.php4?page={document_root}{script_name}&AddressAction=DeleteAddress&AddressID={address_id}&Action=edit&CID={company_id}&PID={person_id}'); return false;">slett</a>
</td>
</tr>