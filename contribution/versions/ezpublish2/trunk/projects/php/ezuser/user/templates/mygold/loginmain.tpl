<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
	<th align="center">{intl-head_line}</th>
  </tr>
  <tr>
  	<td class="spacer2">&nbsp;</td>
  </tr>
  <tr> 
	<td> 
	  <form method="post" action="/user/login/login/">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
		  <tr> 
			<td class="spacer" width="25%">&nbsp;</td>
			<td class="spacer">&nbsp;</td>
		  </tr>
		  <tr> 
			<td>&nbsp;{intl-username}:&nbsp;</td>
			<td><input type="text" style="width: 95%" size="5" name="Username"/></td>
		  </tr>
		  <tr> 
			<td>&nbsp;{intl-password}:&nbsp;</td>
			<td><input type="password" style="width: 95%" size="5" name="Password" /></td>
		  </tr>
		</table> 
		<table align="center" border="0">
		  <tr> 
			<td align="center"> 
			  <input type="submit" class="okbutton" value="&nbsp;{intl-ok}&nbsp;" name="submit" />
			  <input type="hidden" name="RedirectURL" value="{redirect_url}" />
			</td>
		  </tr>
		  <!-- BEGIN standard_creation_tpl -->
		  <tr> 
			<td align="center">
			  <a class="small" href="/user/forgot/">{intl-forgot}</a> 
			  <br />
			  <a class="small" href="{user_edit_url}">{intl-register}</a>
			</td>
		  </tr>
		<!-- END standard_creation_tpl -->
		<!-- BEGIN extra_creation_tpl -->
		{extra_userbox}
		<!-- END extra_creation_tpl -->

		</table>
	  </form>
	</td>
  </tr>
  <tr> 
	<td class="spacer2">&nbsp;</td>
  </tr>
  <tr> 
	<td class="bgspacer"><img src="/images/shim.gif" alt="" width="1" height="2" /></td>
  </tr>
  <tr> 
	<td class="spacer5">&nbsp;</td>
  </tr>
</table>