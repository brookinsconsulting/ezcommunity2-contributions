<tr>
<td bgcolor="#dcdcdc">
{address_type_name}:
</td>
<td bgcolor="#dcdcdc">
<a href="javascript:UpdateAddress( '{address_street1}', '{address_street2}', '{address_zip}', '{address_id}', '{address_type_id}' )">{address_street1}<br> {address_street2} {address_zip}</a> 
</td>
<td bgcolor="#dcdcdc">
<a href="#" onClick="verify( 'Slette adresse?', 'index.php4?page={document_root}{script_name}&AddressAction=DeleteAddress&AddressID={address_id}&Action=edit&CID={company_id}&PID={person_id}'); return false;"><img src="{document_root}images/slettmini.gif" width="16" height="16" border="0"></a>
</td>
</tr>
