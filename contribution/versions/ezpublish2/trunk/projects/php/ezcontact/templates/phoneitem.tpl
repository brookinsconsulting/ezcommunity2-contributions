<tr>
<td bgcolor="#dcdcdc">
{phone_type_name}:
</td>
<td bgcolor="#dcdcdc">
<a href="javascript:UpdatePhone( '{phone_number}', '{phone_id}', '{phone_type_id}' )">{phone_number}</a>
</td>
<td bgcolor="#dcdcdc">
<a href="#" onClick="verify( 'Slette telefon?', 'index.php4?page={document_root}{script_name}&PhoneAction=DeletePhone&PhoneID={phone_id}&CID={company_id}&PID={person_id}&Action=edit'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('{phone_type_name}','','{document_root}images/slettminimrk.gif',1)"><img name="{phone_type_name}" border="0" src="{document_root}images/slettmini.gif" width="16" height="16"></a>
</td>
</tr>