<tr>
<td bgcolor="#dddddd">
<a href="javascript:UpdatePhone( '{phone_number}', '{phone_id}', '{phone_type_id}' )">{phone_number}</a> 
</td>
<td bgcolor="#dddddd">
{phone_type_name}
</td>
<td bgcolor="#dddddd">
<a href="#" onClick="verify( 'Slette telefon?', 'index.php4?page={document_root}{script_name}&PhoneAction=DeletePhone&PhoneID={phone_id}&CID={company_id}&PID={person_id}&Action=edit'); return false;">slett</a>
</td>
</tr>