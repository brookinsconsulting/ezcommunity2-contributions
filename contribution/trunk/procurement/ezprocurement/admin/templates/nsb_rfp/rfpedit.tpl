<form method="post" id="rfpeditform" name="RFPEDIT" action="{www_dir}{index}/procurement/edit/{action_value}/{rfp_id}/" >

<h1>{intl-head_line}</h1>

<!-- BEGIN error_message_tpl -->
<h3 class="error">{intl-error_parsing_xml}:</h3>
<textarea class="box" name="InvalidContents" cols="40" rows="5" wrap="soft">{rfp_invalid_contents}</textarea>
<!-- END error_message_tpl -->

<hr noshade="noshade" size="4" />


<p class="boxtext">{intl-rfp_name}:</p>
<input class="box" type="text" name="Name" size="40" value="{rfp_name}" />
<br />

<p class="boxtext">{intl-project_estimate}:</p>
<input class="box" type="text" name="ProjectEstimate" size="40" value="{rfp_project_estimate}" />
<br />

<p class="boxtext">{intl-project_number}:</p>
<input class="box" type="text" name="ProjectNumber" size="40" value="{rfp_project_number}" />
<br />
<br />

<!-- BEGIN urltranslator_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
        <td valign="top">
        <p class="boxtext">{intl-rfp_urltranslator}:</p>
        <input class="halfbox" type="text" name="Urltranslator" size="20" value="{rfp_urltranslator}" />
        <input type="hidden" name="UrltranslatorEnabled" value="1" />
        </td>
        <td valign="top">
        <p class="boxtext">{intl-rfp_url}:</p><span class="halfbox">{rfp_url}{intl-rfp_nourl}</span>	
        </td>
</tr>
</table>
<br />
<!-- END urltranslator_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">

	<span class="boxtext">{intl-category}: <br /></span>
	
	<select name="CategoryID">

	<!-- BEGIN value_tpl -->
	<option value="{option_value}" {selected}>{option_level}{option_name}</option>
	<!-- END value_tpl -->
	
	</select>

<!--
	<p class="boxtext">{intl-rfp_topic}:</p>

	<select name="TopicID">

	<option value="0">{intl-no_topic}</option>
	
	<!-- BEGIN topic_item_tpl -->
	<option value="{topic_id}" {selected}>{topic_name}</option>
	<!-- END topic_item_tpl -->
	
	</select>
-->
	</td>	
	<td>&nbsp;</td>
	<td valign="top">
	<span class="boxtext">{intl-additional_category}:<br /></span>

	<select multiple size="{num_select_categories}" name="CategoryArray[]">
	
	<!-- BEGIN multiple_value_tpl -->
	<option value="{option_value}" {multiple_selected}>{option_level}{option_name}</option>
	<!-- END multiple_value_tpl -->
	
	</select>
	</td>
</tr>
</table>

<span class="boxtext">{intl-contents}: <br /></span>
<textarea class="box" name="Contents[]" cols="40" rows="20" wrap="soft">{rfp_contents_0}</textarea>

<!--
<input type="hidden" name="Keywords[]" value="{rfp_keywords}" />
-->

<table width="78%" cellpaddning="0" cellspacing="0" border="0">
<tr>
	<td valign="top" colspan="0">


<table width="99%" cellpaddning="0" cellspacing="0" border="0">
<tr>
        <td valign="top">


<table width="70%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td colspan="5">
	<p class="boxtext">{intl-publish_date}:</p>
	</td>
</tr>
<tr>
	<td>
	<span class="small">{intl-month}:</span>
	</td>
	<td>
	<span class="small">{intl-day}:</span>
	</td>
	<td>
	<span class="small">{intl-year}:</span>
	</td>

	<td>
	<span class="small">{intl-hour}:</span>
	</td>
	<td>
	<span class="small">{intl-minute}:</span>
	</td>
</tr>
<tr>
	<td valign="top">
	<input type="text" size="2" name="StartMonth" value="{start_month}" />&nbsp;&nbsp;
	</td>
	<td valign="top">
	<input type="text" size="2" name="StartDay" value="{start_day}" />&nbsp;&nbsp;
	</td>
	<td valign="top">
	<input type="text" size="4" name="StartYear" value="{start_year}" />&nbsp;&nbsp;
	</td>
	<td valign="top">
	<input type="text" size="2" name="StartHour" value="{start_hour}" />&nbsp;&nbsp;
	</td>
	<td valign="top">
	<input type="text" size="2" name="StartMinute" value="{start_minute}" />
	</td>
</tr>
</table>

<table width="70%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td colspan="5">
	<p class="boxtext">{intl-responce_due_date}:</p>
	</td>
</tr>
<tr>
	<td>
	<span class="small">{intl-month}:</span>
	</td>
	<td>
	<span class="small">{intl-day}:</span>
	</td>
	<td>
	<span class="small">{intl-year}:</span>
	</td>

	<td>
	<span class="small">{intl-hour}:</span>
	</td>
	<td>
	<span class="small">{intl-minute}:</span>
	</td>
</tr>
<tr>
	<td valign="top">
	<input type="text" size="2" name="StopMonth" value="{stop_month}" />&nbsp;&nbsp;
	</td>
	<td valign="top">
	<input type="text" size="2" name="StopDay" value="{stop_day}" />&nbsp;&nbsp;
	</td>
	<td valign="top">
	<input type="text" size="4" name="StopYear" value="{stop_year}" />&nbsp;&nbsp;
	</td>
	<td valign="top">
	<input type="text" size="2" name="StopHour" value="{stop_hour}" />&nbsp;&nbsp;
	</td>
	<td valign="top">
	<input type="text" size="2" name="StopMinute" value="{stop_minute}" />
	</td>
</tr>
</table>

</td>
<td valign="top" align="right">
	<!-- BEGIN publish_dates_tpl -->
	<p class="boxtext">{intl-created}:</p><span class="p">{created_date}</span>

	<!-- BEGIN published_tpl -->
	<p class="boxtext">{intl-published}:</p><span class="p">{published_date}</span>
	<!-- END published_tpl -->
	<!-- BEGIN un_published_tpl -->
	<p class="boxtext">{intl-un_published}</p>

	<!-- END un_published_tpl -->
  	  <p class="boxtext">{intl-modified}:</p><span class="p">{modified_date}</span>
          <p class="boxtext">{intl-responce_due_date}:</p><span class="p">{responce_due_date}</span>
          <p class="boxtext">{intl-award_date}:</p><span class="p">{award_date}</span>
	<!-- END publish_dates_tpl -->
	</td>
</tr>
</table>


</td>
</tr>

<!-- group information : required -->
<input type="hidden" name="GroupArray[]" value="0" />
<input type="hidden" name="WriteGroupArray[]" value="1,3" />

<!--
<tr>
	<td colspan="2">&nbsp;
	<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">

<tr>
        <td align="top">
	<input type="hidden" name="GroupArray[]" value="0" />

        <p class="boxtext">{intl-groups}:</p>
        <select name="GroupArray[]" size="3" multiple>
	        <option value="0" {all_selected}>{intl-all}</option>
        </select>

        <!-- BEGIN group_item_tpl -->
        <option value="{group_id}" {selected}>{group_name}</option>
        <!-- END group_item_tpl -->

        <option value="1" >Administrators</option>
        <option value="3" selected>Bidders</option>
        <option value="2" >Anonymous</option>
        <option value="4" >Holders</option>

        </td>

        <td>&nbsp;</td>
        <td>

	 <input type="hidden" name="WriteGroupArray[]" value="1,3" />


        <p class="boxtext">{intl-groups_write}:</p>

        <select name="WriteGroupArray[]" size="3" multiple>
        <option value="0" {all_write_selected}>{intl-all}</option>

        <option value="1" selected>Administrators</option>
        <option value="3" selected>Bidders</option>
        <option value="4" >Holders</option>
        <option value="2" >Anonymous</option>

        </select>
        </td>

        <!-- BEGIN category_owner_tpl -->
        <option value="{module_owner_id}" {is_selected}>{module_owner_name}</option>
        <!-- END category_owner_tpl -->

</tr>
</table>
<br />

-->



<!-- BEGIN bid_list_tpl -->
<tr>
  <td colspan="2">
   <div style="padding-top: 10px; adding-bottom: 4px;">
	<span class="boxtext" style="font-size: 13px;">{intl-bid_header}:</span><br />
	<select id="BidList" onchange="fillBid()">

	<!-- BEGIN bid_list_options_tpl -->
	<option value="{bid_id}">{bid_list_name}</option>
	<!-- END bid_list_options_tpl -->
	</select>
    </div>
</td></tr>
<tr>
  <td colspan="2">

	<table width="100%" style="border: 1px solid black" cellpadding="2" cellspacing="2" border="0">
	<tr><td width="100">
	Company:
	</td><td><select id="BidCompany" onchange="setPersons(document.getElementById('BidCompany').options[document.getElementById('BidCompany').selectedIndex].value, 0);" />
	<!-- BEGIN bid_company_tpl -->
	<option value="{bid_company_id}">{bid_company_name}</option>
	<!-- END bid_company_tpl -->
	</select>
	</td></tr>
	<tr><td>
	Person:
	</td><td><select id="BidPerson" /><option></option>
	</select></td>
	</tr>
    <tr>
	<td>
	Rank: </td>
	<td>
	<select id="BidRank" />
	<!-- BEGIN bid_rank_tpl -->
	<option value="{bid_rank_id}">{bid_rank_name}</option>
	<!-- END bid_rank_tpl -->
	</select></td></tr>
	<tr><td>
	Amount: </td><td><input type="text" id="BidAmount" size="6" value="{bid_amount}" /></td>
	</tr><tr><td>
	Winner: </td><td><input type="checkbox" id="IsWinner" /> </td>
	</tr>
	<tr><td>
	Delete Bid: </td><td><input type="checkbox" id="IsDeleted" onchange="toggleBids()" /> </td>
	</tr>
	</table>
<tr>
  <td colspan="2">
	<div style="padding: 4px; cursor: pointer; text-align: center; border: 1px solid black; background-color: #999; color: black; font-size: 12px;" onclick="changeBid()" onmouseover="this.style.backgroundColor='#efefde'; this.style.fontWeight='bold';" onmouseout="this.style.backgroundColor='#999'; this.style.fontWeight='normal';">Modify Bid</div>
</tr></td>
<tr>
  <td colspan="2">
 	<div style="padding: 4px; cursor: pointer; text-align: center; border: 1px solid black; background-color: #999; color: black; font-size: 12px;" onclick="addBid()" onmouseover="this.style.backgroundColor='#efefde'; this.style.fontWeight='bold';" onmouseout="this.style.backgroundColor='#999'; this.style.fontWeight='normal';">Add Bid</div>

	<!-- BEGIN bid_hidden_tpl -->
	<input type="hidden" name="BidInfo{bid_id}" id="BidInfo{bid_id}" value="{bid_hidden_info}" />
	<!-- END bid_hidden_tpl -->
	<input type="hidden" name="NewInfo" id="NewInfo" value="" />
	<!-- END bid_list_tpl -->
</td></tr>
<tr>
<td colspan="2" style="padding-top: 5px; padding-bottom: 3px;">
<table width="80%" align="center" border="0">
<tr>
<td>
      	<span class="boxtext" style="padding-botom: 7px;">{intl-rfp_author}:<br />{selected}</span>
       	<select multiple size="10" name="ContentsWriterID[]">
	<!-- BEGIN author_item_tpl -->
    	  <option value="{author_id}" {mario}>{option_level}{author_name}</option>
      	<!-- END author_item_tpl -->
        </select>
</td>
<td>
        <span class="boxtext" style="padding-bottom: 5px;">{intl-project_manager}:<br />{selected}</span>
        <select multiple size="10" name="ProjectManager[]">
        <!-- BEGIN project_manager_item_tpl -->
          <option value="{person_id}" {pm_sel}>{option_level}{person_name}</option>
        <!-- END project_manager_item_tpl -->
        </select>
</td>
</tr>
</table>
</td>
</tr>

<!--
<tr>
        <td colspan="4"><input type="hidden" name="LogMessage[]" value="" /></td>
</tr>
-->

        <!-- BEGIN rfp_pending_tpl -->

<!--
<tr>

	<td>
	<p>{intl-rfp_is_pending}</p>
	</td>
	<td colspan="3">&nbsp;</td>
</tr>
-->

        <!-- END rfp_pending_tpl -->
<tr>
	<td colspan="2">
	<input type="checkbox" name="IsPublished" {rfp_is_published} />
	<span class="boxtext">{intl-rfp_is_published}</span><br />
	</td>

<!--
        <td colspan="2">
&nbsp;

	<input type="checkbox" name="Discuss" {discuss_rfp} />
	<span class="boxtext">{intl-discuss_rfp}</span><br />

	</td>
-->

</tr>
</table>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td>
    <input class="okbutton" type="submit" value="{intl-ok}" />
   </form>
  </td>
  <form method="post" action="{www_dir}{index}/procurement/edit/filelist/{rfp_id}/" >
  <td style="padding-left: 15px;">
	<input type="hidden" name="ItemToAdd" value="File" />	

<!--
        <select name="ItemToAdd">
        <option value="File">{intl-files}</option>
        <option value="Image">{intl-pictures}</option>
        <option value="Media">{intl-media}</option>
        <option value="File">{intl-files}</option>
        <option value="Attribute">{intl-attributes}</option>
        <option value="Form">{intl-forms}</option>
        </select>
-->

    </td>
    <td>
        <input class="stdbutton" type="submit" name="AddItem" value="{intl-add_item}" />
    </td>

<!--
    <td>&nbsp;&nbsp;&nbsp;</td>
-->

    <td>
        <input class="stdbutton" type="submit" name="Preview" value="{intl-preview}" />
    <td>
<tr>


</form>

</tr>
</table>

<hr noshade="noshade" size="4" />

<span>
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<form method="post" action="{www_dir}{index}/procurement/edit/cancel/{rfp_id}/">
	<td>
	<input class="okbutton" type="submit" value="{intl-cancel}" />	
	</td>
	</form>
</tr>
</table>

<script language="JavaScript">
var newNum = 0;
var compArr = new Array();
var personArr = new Array();
<!-- BEGIN js_company_tpl -->
compArr[{bid_company_id}] = {company_iteration};
personArr[{bid_company_id}] = new Array();
<!-- END js_company_tpl -->


<!-- BEGIN js_person_tpl -->

<!-- BEGIN js_person_pre_tpl -->
personArr[{person_company_id}][{person_iteration}] = new Array();
<!-- END js_person_pre_tpl -->
personArr[{person_company_id}][{person_iteration}][{bid_person_id}] = {person_iteration};
personArr[{person_company_id}][{person_iteration}]['value'] = {bid_person_id};
personArr[{person_company_id}][{person_iteration}]['text'] = "{bid_person_name}";
<!-- END js_person_tpl -->

var rankArr = new Array();
<!-- BEGIN js_rank_tpl -->
rankArr[{bid_rank_id}] = {rank_iteration};
<!-- END js_rank_tpl -->


/*
 	# Element Positioning
 	# BidId = 0
 	# BidCompany = 1
 	# BidPerson = 2
 	# BidRank = 3
 	# BidAmount = 4
 	# BidWinner = 5
 	# Deleted = 6
*/
fillBid();

function fillBid()
{
var startId = document.getElementById('BidList').options[document.getElementById('BidList').selectedIndex].value;
if (startId.substr(0,3) == 'new')
{
 fillNewBid();
 return false;
}
var startVal = 'BidInfo' + startId;
var val = document.getElementById(startVal).value;

var valArr = val.split("|");
if (valArr[6] == 1)
{
 document.getElementById('IsDeleted').checked = true;
 disableBids();
}
else
{
 document.getElementById('IsDeleted').checked = false;
 enableBids();
}
document.getElementById('BidCompany').selectedIndex = compArr[valArr[1]];
setPersons(document.getElementById('BidCompany').options[document.getElementById('BidCompany').selectedIndex].value, valArr[2]);
document.getElementById('BidRank').selectedIndex = rankArr[valArr[3]];
document.getElementById('BidAmount').value = valArr[4];
if (valArr[5] == 1)
 document.getElementById('IsWinner').checked = true;
else
 document.getElementById('IsWinner').checked = false;
}

function setPersons( compID, personID )
{

 for (i=0; i<document.getElementById('BidPerson').options.length; i++)
 {
  document.getElementById('BidPerson').options[i] = null;
 }

 if (personArr[compID].length == 0)
 {
  document.getElementById('BidPerson').options[0] = new Option("No Person Associated", 0);
  return false;
 }
 for (i=0; i<personArr[compID].length; i++)
 {
  newOption = new Option();
  document.getElementById('BidPerson').options[i] = new Option(personArr[compID][i]['text'], personArr[compID][i]['value']);
  if (document.getElementById('BidPerson').options[i].value == personID)
   document.getElementById('BidPerson').selectedIndex = personArr[compID][i][personID];
 }
}

function changeBid()
{

var startId = document.getElementById('BidList').options[document.getElementById('BidList').selectedIndex].value;
if (startId.substr(0,3) == 'new')
{
addNewBid();
return false;
}
var startVal = 'BidInfo' + startId;
hiddenfrm = document.getElementById(startVal);
changeCompany();
if (document.getElementById('IsWinner').checked == true)
 var winner = 1;
else
 var winner = 0;

if (document.getElementById('IsDeleted').checked == true)
 var deleted = 1;
else
 var deleted = 0;

var ret = startId + '|'
+ document.getElementById('BidCompany').options[document.getElementById('BidCompany').selectedIndex].value + '|'
+ document.getElementById('BidPerson').options[document.getElementById('BidPerson').selectedIndex].value + '|'
+ document.getElementById('BidRank').options[document.getElementById('BidRank').selectedIndex].value + '|'
+ document.getElementById('BidAmount').value + '|' + winner + '|' + deleted;
hiddenfrm.value = ret;
}

function disableBids()
{
 document.getElementById('BidPerson').disabled=true;
 document.getElementById('BidCompany').disabled=true;
 document.getElementById('BidRank').disabled=true;
 document.getElementById('BidAmount').disabled=true;
 document.getElementById('IsWinner').disabled=true;
}

function enableBids()
{
 document.getElementById('BidPerson').disabled=false;
 document.getElementById('BidCompany').disabled=false;
 document.getElementById('BidRank').disabled=false;
 document.getElementById('BidAmount').disabled=false;
 document.getElementById('IsWinner').disabled=false;
}
function toggleBids()
{
 if (document.getElementById('IsDeleted').checked == true)
  disableBids();
 else
  enableBids();
}

function changeCompany()
{
 document.getElementById('BidList').options[document.getElementById('BidList').selectedIndex].text = document.getElementById('BidRank').options[document.getElementById('BidRank').selectedIndex].text + ' - ' + document.getElementById('BidCompany').options[document.getElementById('BidCompany').selectedIndex].text;
}
function addBid()
{
 i = document.getElementById('BidList').options.length;
 document.getElementById('BidList').options[i] = new Option('New', 'new');
 document.getElementById('BidList').selectedIndex = i;
 resetForm();
}

function addNewBid()
{
var newSplit = document.getElementById('BidList').options[document.getElementById('BidList').selectedIndex].value.split(":");
if (!isNaN(newSplit[1])) // if the value has a number on the end
{
 modifyNewBid(newSplit[1]);
 return false;
}
document.getElementById('BidList').options[document.getElementById('BidList').selectedIndex].value = 'new:'+ newNum;
newNum++;
 if (document.getElementById('IsWinner').checked == true)
 var winner = 1;
else
 var winner = 0;

if (document.getElementById('IsDeleted').checked == true)
 var deleted = 1;
else
 var deleted = 0;
var valString = 'new' + '|' + document.getElementById('BidCompany').options[document.getElementById('BidCompany').selectedIndex].value + '|'
+ document.getElementById('BidPerson').options[document.getElementById('BidPerson').selectedIndex].value + '|'
+ document.getElementById('BidRank').options[document.getElementById('BidRank').selectedIndex].value + '|'
+ document.getElementById('BidAmount').value + '|' + winner + '|'
+ deleted;

 if (document.getElementById('NewInfo').value == '')
  document.getElementById('NewInfo').value = valString;
 else
  document.getElementById('NewInfo').value = document.getElementById('NewInfo').value + '***' + valString;
 changeCompany();
}

function fillNewBid()
{
 var newSplit = document.getElementById('BidList').options[document.getElementById('BidList').selectedIndex].value.split(":");
if (!isNaN(newSplit[1])) // if the value has a number on the end
{
 setNewBid(newSplit[1]);
 return false;
 }
 setPersons(document.getElementById('BidCompany').options[document.getElementById('BidCompany').selectedIndex].value, 0);
 resetForm();
 return false;
}

function setNewBid(newId)
{
 newInfoArr = document.getElementById('NewInfo').value.split("***");
 var val = newInfoArr[newId];

 var valArr = val.split("|");
 if (valArr[6] == 1)
 {
  document.getElementById('IsDeleted').checked = true;
  disableBids();
 }
 else
 {
  document.getElementById('IsDeleted').checked = false;
  enableBids();
 }
 document.getElementById('BidCompany').selectedIndex = compArr[valArr[1]];
setPersons(document.getElementById('BidCompany').options[document.getElementById('BidCompany').selectedIndex].value, valArr[2]);
 document.getElementById('BidRank').selectedIndex = rankArr[valArr[3]];
 document.getElementById('BidAmount').value = valArr[4];
 if (valArr[5] == 1)
  document.getElementById('IsWinner').checked = true;
 else
  document.getElementById('IsWinner').checked = false;
}

function modifyNewBid(newId)
{
 newInfoArr = document.getElementById('NewInfo').value.split("***");
  if (document.getElementById('IsWinner').checked == true)
 var winner = 1;
else
 var winner = 0;

if (document.getElementById('IsDeleted').checked == true)
 var deleted = 1;
else
 var deleted = 0;
 newInfoArr[newId] = 'new' + '|' + document.getElementById('BidCompany').options[document.getElementById('BidCompany').selectedIndex].value + '|'
+ document.getElementById('BidPerson').options[document.getElementById('BidPerson').selectedIndex].value + '|'
+ document.getElementById('BidRank').options[document.getElementById('BidRank').selectedIndex].value + '|'
+ document.getElementById('BidAmount').value + '|' + winner + '|'
+ deleted;
document.getElementById('NewInfo').value = newInfoArr.join("***");
changeCompany();
}

function resetForm()
{
document.getElementById('BidAmount').value = '';
document.getElementById('BidCompany').selectedIndex = 0;
document.getElementById('BidPerson').selectedIndex = 0;
document.getElementById('BidRank').selectedIndex = 0;
document.getElementById('IsWinner').checked = false;
document.getElementById('IsDeleted').checked = false;
}
</script>


