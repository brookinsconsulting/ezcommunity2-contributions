<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-search}</h1>
     </td>
     <td align="right">
	 <form action="/link/search/" method="post">
	       <input type="text" name="QueryString">
	       <input type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>


<hr noshade size="4" />

<h2>Search for: "{query_string}"</h2>
<br>

<!-- BEGIN empty_result_tpl -->
<h3 class="error">{intl-empty_result}</h3>
<!-- END empty_result_tpl -->


<br>
<h3>{intl-result} ({intl-maxhit} {hit_count})</h3> 
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN search_item_tpl -->
<tr>

	<td bgcolor="{bg_color}">
	<a href="/link/gotolink/?LID={link_id}&Url={link_url}&Action=addhit">{link_title}</a> ( {intl-max} {link_hits} )<br>
        {link_description}<br><br>
	<td class="{td_class}" width="1%" bgcolor="{bg_color}">
	<a href="/link/linkedit/edit/{link_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('el{link_id}-red','','/ezlink/images/redigerminimrk.gif',1)"><img name="el{link_id}-red" border="0" src="/ezlink/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td class="{td_class}" width="1%" bgcolor="{bg_color}">
	<a href="/link/linkedit/delete/{link_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('el{link_id}-slett','','/ezlink/images/slettminimrk.gif',1)"><img name="el{link_id}-slett" border="0" src="/ezlink/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>

        
     </td>

</tr>
<!-- END search_item_tpl -->
</table>


<!-- BEGIN previous_tpl -->
<a href="/link/search/?Offset={prev_offset}&URLQueryString={url_query_string}">
prev
</a>
<!-- END previous_tpl -->

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<!-- BEGIN next_tpl -->
<a href="/link/search/?Offset={next_offset}&URLQueryString={url_query_string}">
next
</a>
<!-- END next_tpl -->
