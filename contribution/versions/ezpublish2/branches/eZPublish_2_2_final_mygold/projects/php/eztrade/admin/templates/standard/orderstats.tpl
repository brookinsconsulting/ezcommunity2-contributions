<h1>{intl-head_line}</h1>
<hr noshade="noshade" size="4" />
<form action="/trade/orderstats/" method="post">
<table cellspacing="0" cellpadding="4" border="0">
  <tr>
    <td>
      <table cellspacing="0" cellpadding="0" border="0">
	<tr>
	  <th colspan="3">
            {intl-start_date}:
          </th>
	  <th>&nbsp;&nbsp;</th>
	  <th colspan="3">
            {intl-end_date}:
          </th>
        </tr>
	<tr>
	  <td class="small">
            {intl-day}:<br />
            <select name="StartDay" class="small">
              <!-- BEGIN start_day_tpl -->
    	      <option {selected} value="{start_day_value}">{start_day_name}</option>
	      <!-- END start_day_tpl -->
    	    </select>
	  </td>
	  <td class="small">
    	    {intl-month}:<br />
    	    <select name="StartMonth" class="small">
    	      <!-- BEGIN start_month_tpl -->
	      <option {selected} value="{start_month_value}">{start_month_name}</option>
	      <!-- END start_month_tpl -->
            </select>
          </td>
          <td class="small">
            {intl-year}:<br />
            <select name="StartYear" class="small">
              <!-- BEGIN start_year_tpl -->
	      <option {selected} value="{start_year_value}">{start_year_name}</option>
	      <!-- END start_year_tpl -->
            </select>
          </td>
          <td>&nbsp;</td>
          <td class="small">
            {intl-day}:<br />
            <select name="EndDay" class="small">
              <!-- BEGIN end_day_tpl -->
	      <option {selected} value="{end_day_value}">{end_day_name}</option>
	      <!-- END end_day_tpl -->
            </select>
          </td>
          <td class="small">
            {intl-month}:<br />
            <select name="EndMonth" class="small">
              <!-- BEGIN end_month_tpl -->
	      <option {selected} value="{end_month_value}">{end_month_name}</option>
	      <!-- END end_month_tpl -->
            </select>
          </td>
          <td class="small">
            {intl-year}:<br />
            <select name="EndYear" class="small">
              <!-- BEGIN end_year_tpl -->
	      <option {selected} value="{end_year_value}">{end_year_name}</option>
	      <!-- END end_year_tpl -->
            </select>	     
          </td>
          <td class="small">
            &nbsp;<br />
            <input type="submit" name="send" value="{intl-ok}" class="small" />
          </td>
        </tr>
      </table>
    </td>
    <td>
      <table>
        <tr>
          <!-- BEGIN get_by_month_tpl -->
          <td class="small"><a class="small" href="/trade/orderstats/?ByMonth={by_month_link}">{by_month}</a></td>
          {tr}
          <!-- END get_by_month_tpl -->
        </tr>
      </table>
    </td>
    <td class="small">
      <input class="small" type="checkbox" name="ShowCumulated" value="1" {checked} />&nbsp;{intl-show_cumulated}
    </td>
  </tr>
</table>
</form>

<hr noshade="noshade" size="4" />

<!-- BEGIN std_output_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
  <tr>
    <th width="2%">{intl-count}</th>
    <th width="24%">{intl-id}</th>
    <th width="24%">{intl-date}</th>
    <th width="24%">{intl-name}</th>
    <th width="24%" class="right">{intl-amount}</th>
    <th width="2%" class="right">{intl-edit}</th>
  </tr>
  <!-- BEGIN row_tpl -->  
  <tr>
    <td class="{td_class}">{count}</td>  
    <td class="{td_class}">{id}</td>
    <td class="{td_class}">{date}</td>
    <td class="{td_class}"><a href="/trade/customerview/{user_id}/">{surname}&nbsp;{lastname}</a></td>    
    <td class="{td_class}" align="right">{price}</td>
    <td class="{td_class}" align="right">
      <a href="/trade/orderedit/{id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('O_{id}','','/admin/images/ezpublish/redigerminimrk.gif',1)"><img name="O_{id}" border="0" src="/admin/images/ezpublish/redigermini.gif" width="16" height="16" align="top"></a>
    </td>
  </tr>
  <!-- END row_tpl -->
  <!-- BEGIN no_data_tpl -->
  <tr>
    <td colspan="6">{intl-no_data}</td>
  </tr>
  <!-- END no_data_tpl -->
  <tr>
    <td colspan="4" align="right"><b>{intl-total}:</b></td>
    <td align="right"><b>{order_sum}</b></td>
    <td>&nbsp;</td>
  </tr>
</table>
<!-- END std_output_tpl -->

<!-- BEGIN cumu_output_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
  <tr>
    <th width="1%">{intl-date}</th>
    <th width="1%">{intl-amount}</th>    
    <th width="98%">{intl-percentage}</th>    
  </tr>
  <!-- BEGIN cumulated_tpl -->
  <tr>
    <td class="{td_class}">
      {period}
    </td>
    <td align="right" class="{td_class}">
      {amount}
    </td>
    <td class="{td_class}">
      <table width="100%">
        <tr>
	  <td bgcolor="yellow" width="{width}%"><span class="small">{width}</span></td>
	  <td>&nbsp;</td>
	</tr>
      </table>
    </td>
  </tr>
  <!-- END cumulated_tpl -->
  <tr>
    <td align="right"><b>{intl-total}:</b></td>
    <td align="right"><b>{order_sum}</b></td>
    <td>&nbsp;</td>
  </tr>  
  
</table>
<!-- END cumu_output_tpl -->