<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
  <tr>
   <!-- BEGIN header_item_tpl -->
    <td align="left"> 
      <h1>{intl-event_edit}</h1>
    </td>
  </tr>
</table>

<!-- BEGIN user_error_tpl -->
	<!-- BEGIN no_user_error_tpl -->
	<p class="error">{intl-no_user_error}</p>
	<!-- END no_user_error_tpl -->

	<!-- BEGIN wrong_user_error_tpl -->
	<p class="error">{intl-wrong_user_error}</p>
	<!-- END wrong_user_error_tpl -->
<!-- END user_error_tpl -->


<!-- BEGIN no_error_tpl -->

<!-- BEGIN title_error_tpl -->
<p class="error">{intl-title_error}</p>
<!-- END title_error_tpl -->

<!-- BEGIN group_error_tpl -->
<p class="error">{intl-group_error}</p>
<!-- END group_error_tpl -->

<!-- BEGIN start_time_error_tpl -->
<p class="error">{intl-start_time_error}</p>
<!-- END start_time_error_tpl -->

<!-- BEGIN stop_time_error_tpl -->
<p class="error">{intl-stop_time_error}</p>
<!-- END stop_time_error_tpl -->

<form method="post" onSubmit="return formCheck(this)" name="EventEdit" action="/groupeventcalendar/eventedit/{action_value}/{event_id}/">

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">

<p class="boxtext">{intl-event_title}:</p>
<input type="text" size="50" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-event_location}:</p>
<input type="text" size="50" name="Location" value="{location_value}"/>

<p class="boxtext">{intl-event_url}:</p>
<input type="text" size="54" name="Url" value="{url_value}"/>

<p class="boxtext">{intl-event_description}:</p>
<textarea name="Description" cols="55" rows="7" wrap="soft">{description_value}</textarea>


<!-- BEGIN group_name_edit_tpl -->
<p class="boxtext">{intl-event_group}:&nbsp;&nbsp;{group_name}</p><br />
<input type="hidden" name="StoreByGroupID" value="{group_id}" />
<!-- END group_name_edit_tpl -->

<!-- BEGIN group_name_new_tpl -->
<p class="boxtext">{intl-event_group}:</p>
<select name="StoreByGroupID">
<option value="">Select</option>
<!-- BEGIN group_item_tpl -->
<option {group_is_selected} value="{group_member_id}">{group_member_name}</option>
<!-- END group_item_tpl -->
</select>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input {is_private} type="checkbox" name="IsPrivate" />&nbsp;<span class="check">{intl-private_event}</span>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input {is_event_alarm_notice} type="checkbox" name="IsEventAlarmNotice" />&nbsp;<span class="check">{intl-event_notification}</span>

<!-- END group_name_new_tpl -->

	</td>
</tr>
</table>

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">

<p class="boxtext">{intl-type}:</p>

<select name="TypeID">
<option></option>
<!-- BEGIN value_tpl -->
<option value="{option_value}" {type_is_selected}>{option_level}{option_name}</option>
<!-- END value_tpl -->

</select>


<p class="boxtext">{intl-category}:</p>

<select name="CategoryID">
<option></option>
<!-- BEGIN category_value_tpl -->
<option value="{option_category_value}" {category_is_selected}>{option_category_level}{category_name}</option>
<!-- END category_value_tpl -->

</select>


	<td valign="top">
	<td>

<p class="boxtext">{intl-priority}:</p>

<select name="Priority">
<option value="0" {0_selected}>{intl-lowest_priority}</option>
<option value="1" {1_selected}>{intl-low_priority}</option>
<option value="2" {2_selected}>{intl-normal_priority}</option>
<option value="3" {3_selected}>{intl-medium_priority}</option>
<option value="4" {4_selected}>{intl-high_priority}</option>
<option value="5" {5_selected}>{intl-highest_priority}</option>
</select>


<p class="boxtext">{intl-status}:</p>

<select name="Status">
  <option value="0" {0_status_selected}>{intl-tentative_status}</option>
  <option value="1" {1_status_selected}>{intl-confirmed_status}</option>
  <option value="2" {2_status_selected}>{intl-cancelled_status}</option>
</select>

	</td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
	<p class="boxtext">{intl-event_day}:</p>

	<select name="Day">
	<option></option>
	<!-- BEGIN day_tpl -->
		<option value="{day_id}" {selected}>{day_name}</option>
	<!-- END day_tpl -->
	</select>

	</td>
	<td valign="top">
	<p class="boxtext">{intl-event_month}:</p>

	<select name="Month">
	<option></option>
	<!-- BEGIN month_tpl -->
		<option value="{month_id}" {selected}>{month_name}</option>
	<!-- END month_tpl -->
	</select>

	</td>
	<td width="40%" valign="top">
	<p class="boxtext">{intl-event_year}:</p>
	<!--
	<input type="text" name="Year" value="{year_value}" />
	-->

	<select name="Year">
	   <option></option>
	<!-- BEGIN year_tpl -->
		<option value="{year_value}" {is_year_selected}>{year_value}</option>
	<!-- END year_tpl -->
	</select>
	</td>
</tr>
</table>

<br />

<input {is_all_day} type="checkbox" name="IsAllDay" onChange="resetTimeSelect();" />&nbsp;<span class="check">{intl-all_day_event}</span>


<!-- BEGIN html_form_datetime_select_tpl -->
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
	<td valign="top">
	<p class="boxtext">{intl-event_start}:</p>
		<select name="Start_Hour" onChange="resetAllDayCheck();">
		<option></option>
		<!-- BEGIN start_hour_item_tpl -->
		<option value="{start_hour}" {is_start_hour_selected}>{start_hour}</option>
		<!-- END start_hour_item_tpl -->
		</select>
		<i> {intl-hour} </i>
		&nbsp;&nbsp;
		<select name="Start_Minute" onChange="resetAllDayCheck();">
		<option></option>
		<!-- BEGIN start_minute_item_tpl -->
		<option value="{start_minute}" {is_start_minute_selected}>{start_minute}</option>
		<!-- END start_minute_item_tpl -->
		</select>
		<i>{intl-minute}</i>
	</td>
	<td>&nbsp;</td>
	<td valign="top">
	<p class="boxtext">{intl-event_stop}:</p>
		<select name="Stop_Hour" onChange="resetAllDayCheck();">
		<option></option>
		<!-- BEGIN stop_hour_item_tpl -->
		<option value="{stop_hour}" {is_stop_hour_selected}>{stop_hour}</option>
		<!-- END stop_hour_item_tpl -->
		</select>
		<i> {intl-hour} </i>
		&nbsp;&nbsp;
		<select name="Stop_Minute" onChange="resetAllDayCheck();">
		<option></option>
		<!-- BEGIN stop_minute_item_tpl -->
		<option value="{stop_minute}" {is_stop_minute_selected}>{stop_minute}</option>
		<!-- END stop_minute_item_tpl -->
		</select>
		<i>{intl-minute}</i>
	</td>
</tr>
<tr> 
	<td>
		<!-- BEGIN start_ampm_radio_tpl -->
                <input type="radio" name="Start_AM_PM" value="am" {start_am}>&nbsp;&nbsp;am&nbsp;&nbsp;
                <input type="radio" name="Start_AM_PM" value="pm" {start_pm}>&nbsp;&nbsp;pm
                <!-- END start_ampm_radio_tpl -->
        </td>
	<td>&nbsp;</td>
        <td>
                <!-- BEGIN stop_ampm_radio_tpl -->
                <input type="radio" name="Stop_AM_PM" value="am" {stop_am}>&nbsp;&nbsp;am&nbsp;&nbsp;
                <input type="radio" name="Stop_AM_PM" value="pm" {stop_pm}>&nbsp;&nbsp;pm
                <!-- END stop_ampm_radio_tpl -->
        </td>
</tr>
</table>
<!-- END html_form_datetime_select_tpl -->

<!-- BEGIN dhtml_form_datetime_select_tpl -->

<br />
<a href="" onclick="return showCalendar('sel2', '%a, %b %e, %Y [%I:%M %p]', '12');">GUI Calendar</a>
<!-- END dhtml_form_datetime_select_tpl -->


<!-- start recurring_event stuff -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td><p class="boxtext">{intl-recurring_event}:</p></td>
</tr>
<tr>
	<td valign="top">
		<input {is_recurring} type="checkbox" name="IsRecurring" onChange="toggleRecurringEventForm()" />&nbsp;<span class="check">{intl-make_recurring}</span>
	</td>
</tr>
</table>
<div id="gcalRecurringFormWrap">
 {intl-repeat_int} <input type="text" size="4" name="RecurFreq" value="{recur_freq}" /> 
 <select name="RecurType" onChange="toggleRecurTypeLayer();">
   <option value="rtDay" {rtselect_day}>{intl-event_day}</option>
   <option value="rtWeek" {rtselect_week}>{intl-event_week}</option>
   <option value="rtMonth" {rtselect_month}>{intl-event_month}</option>
   <option value="rtYear" {rtselect_year}>{intl-event_year}</option>
 </select>
 
 <div id="gcalRecurringWeekly">
  <span class="check">{intl-mon}</span>&nbsp;<input {recurring_daily_monday} type="checkbox" name="RecurringDailyMon" />
  <span class="check">{intl-tue}</span>&nbsp;<input {recurring_daily_tuesday} type="checkbox" name="RecurringDailyTue" />
  <span class="check">{intl-wed}</span>&nbsp;<input {recurring_daily_tuesday} type="checkbox" name="RecurringDailyWed" />
  <span class="check">{intl-thu}</span>&nbsp;<input {recurring_daily_tuesday} type="checkbox" name="RecurringDailyThu" />
  <span class="check">{intl-fri}</span>&nbsp;<input {recurring_daily_tuesday} type="checkbox" name="RecurringDailyFri" />
  <span class="check">{intl-sat}</span>&nbsp;<input {recurring_daily_tuesday} type="checkbox" name="RecurringDailySat" />
  <span class="check">{intl-sun}</span>&nbsp;<input {recurring_daily_tuesday} type="checkbox" name="RecurringDailySun" />
 </div>
 <div id="gcalRecurringMonthly">
   <input type="radio" name="RecurTypeMonth" value="daily" {start_daily} />&nbsp;&nbsp;
   {intl-on_the} {today_date} {intl-of_the_month}.
   <br />
   <input type="radio" name="RecurTypeMonth" value="strdayname" {start_StrDayName} />&nbsp;&nbsp;
   {week_number_str} {today_day_name} {intl-of_the_month}.
   <br />
   <input type="radio" name="RecurTypeMonth" value="numdayname" {start_NumDayName} />&nbsp;&nbsp;
   {day_number_str} {today_day_name} {intl-of_the_month}.
 </div>
 <br /><br />
 <input type="radio" name="repeatOptions" value="forever" /> {intl-repeat_forever}
 <br />
 <input type="radio" name="repeatOptions" value="numTimes" /> {intl-repeat_number} <input type="text" size="10" name="numberOfTimes" />
 <br />
 <input type="radio" name="repeatOptions" value="UntilDate" /> {intl-repeat_until} <input type="text" size="20" name="untilDate" />
 <br />
 <br />
{intl-repeat_exceptions} <br />
 <a href="#" style="font-size: 9px;">{intl-repeat_exception_add}</a> <a href="#" style="font-size: 9px;">{intl-repeat_exception_remove}</a><br /><br />
 <input type="text" size=7 name="untilDate" /> <br /><br />
 <select name="select" multiple>
 <option>08/19/1983</option>
 </select>
</div>
<!-- End recurring event stuff -->

<br />


<script language="JavaScript">
<!--hide this script from non-javascript-enabled browsers
toggleRecurTypeLayer();
toggleRecurringEventForm();
function toggleRecurTypeLayer() {
var frm = document.forms.EventEdit;
if ( frm.IsRecurring.checked == true ) { 
 hideDiv('gcalRecurringWeekly', 'gcalRecurringMonthly');
 var field = document.EventEdit.RecurType
 var option = field.options[field.selectedIndex].value;
 if (option == "rtWeek") { showDiv('gcalRecurringWeekly') }
 else if (option == "rtMonth") { showDiv('gcalRecurringMonthly') }
 }
}

function hideDiv() {
var arga = hideDiv.arguments;
var argb = arga.length;

if (document.getElementById) {
	 for (var i=0; i < argb; i++) {
		 document.getElementById(arga[i]).style.display = "none";
	 }
  }
else if (document.all) {
	for (var i=0; i < argb; i++) {
document.all[arga[i]].style.display = "none";
	 }
  }
else if (document.layers) {
	for (var i=0; i < argb; i++) {
document.layers[arga[i]].display = "none";
	 }
  }
}

function showDiv() {
var arga = showDiv.arguments;
var argb = arga.length;

if (document.getElementById) {
			for (var i=0; i < argb; i++) {
document.getElementById(arga[i]).style.display = "block";
	 }
  }
else if (document.all) {
			for (var i=0; i < argb; i++) {
document.all[arga[i]].style.display = "block";	
	 }
  }
else if (document.layers) {
		for (var i=0; i < argb; i++) {
document.layers[arga[i]].display = "block";
	 }
  }
}

function toggleRecurringEventForm() {
    var field = document.forms.EventEdit;
    if ( field.IsRecurring.checked == true ) {
        showDiv('gcalRecurringFormWrap');
	} else {
	hideDiv('gcalRecurringFormWrap');
    }
}
function resetAllDayCheck() {
    var field = document.forms.EventEdit;
    if ( field.IsAllDay.checked == true ) {
	if (! isEmpty(field.Start_Hour.selectedIndex) || ! isEmpty(field.Start_Minute.selectedIndex) || ! isEmpty(field.Stop_Hour.selectedIndex) || ! isEmpty(field.Stop_Minute.selectedIndex)) {
            if ( confirm( "By selecting a specific time frame, you cannot have All Day Event selected.  Are you sure you wish to specify a time frame?  If yes press OK, if now press CANCEL" ) ) {
	        field.IsAllDay.checked = false;
	    }
	    else
	    {
                field.Start_Hour.selectedIndex = "";
                field.Start_Minute.selectedIndex = "";
	        field.Stop_Hour.selectedIndex = "";
	        field.Stop_Minute.selectedIndex = "";
	    }
        }
    }
}

function resetTimeSelect() {
    var field = document.forms.EventEdit;
    if (! isEmpty(field.Start_Hour.selectedIndex) || ! isEmpty(field.Start_Minute.selectedIndex) || ! isEmpty(field.Stop_Hour.selectedIndex) || ! isEmpty(field.Stop_Minute.selectedIndex)) {
        if ( field.IsAllDay.checked == true ) {
	    if ( confirm( "By selecting All Day Event, you cannot specify a time frame.  Are you sure you wish to select All Day Event?  If yes press OK, if now press CANCEL" ) ) {
                field.Start_Hour.selectedIndex = "";
                field.Start_Minute.selectedIndex = "";
	        field.Stop_Hour.selectedIndex = "";
	        field.Stop_Minute.selectedIndex = "";
	    }
	    else
	    {
	        field.IsAllDay.checked = false;
	    }
        }
    }
}

function isEmpty(inputStr) {
    if (inputStr == null || inputStr == "") {
        return true;
    }
    return false;
}

// utility functions for date calculations

function isLeapYear(intYear) 
{
    if (intYear % 100 == 0) 
    {
        if (intYear % 400 == 0) { return true; }
    }
    else 
    {
        if ((intYear % 4) == 0) { return true; }
    }
    return false;
}

function isDayValidForThisMonthAndYear(intDay,intMonth,intYear) 
{
    if ((intMonth == 4 || intMonth == 6 || intMonth == 9 || intMonth == 11) && intDay > 30 ) 
    {
        return false;
    }

    if (intMonth == 2) 
    {
        if (isLeapYear(intYear)) 
        {
            if (intDay > 29) 
                {
                return false;
                }
        }
        else 
        {
            if (intDay > 28) 
            {
                return false;
            }
        }
    }
    return true;
}




// form validation function

function formCheck(form) 
{

///////////////////////////////////////////////////////////////////////////////////////
// broswer sniffer
///////////////////////////////////////////////////////////////////////////////////////

// Ultimate client-side JavaScript client sniff. Version 3.02
// (C) Netscape Communications 1999-2001.  Permission granted to reuse and distribute.
// Revised 17 May 99 to add is_nav5up and is_ie5up (see below).
// Revised 20 Dec 00 to add is_gecko and change is_nav5up to is_nav6up
//                      also added support for IE5.5 Opera4&5 HotJava3 AOLTV
// Revised 22 Feb 01 to correct Javascript Detection for IE 5.x, Opera 4, 
//                      correct Opera 5 detection
//                      add support for winME and win2k
//                      synch with browser-type-oo.js
// Revised 26 Mar 01 to correct Opera detection

// Everything you always wanted to know about your JavaScript client
// but were afraid to ask. Creates "is_" variables indicating:
// (1) browser vendor:
//     is_nav, is_ie, is_opera, is_hotjava, is_webtv, is_TVNavigator, is_AOLTV
// (2) browser version number:
//     is_major (integer indicating major version number: 2, 3, 4 ...)
//     is_minor (float   indicating full  version number: 2.02, 3.01, 4.04 ...)
// (3) browser vendor AND major version number
//     is_nav2, is_nav3, is_nav4, is_nav4up, is_nav6, is_nav6up, is_gecko, is_ie3,
//     is_ie4, is_ie4up, is_ie5, is_ie5up, is_ie5_5, is_ie5_5up, is_hotjava3, is_hotjava3up,
//     is_opera2, is_opera3, is_opera4, is_opera5, is_opera5up
// (4) JavaScript version number:
//     is_js (float indicating full JavaScript version number: 1, 1.1, 1.2 ...)
// (5) OS platform and version:
//     is_win, is_win16, is_win32, is_win31, is_win95, is_winnt, is_win98, is_winme, is_win2k
//     is_os2
//     is_mac, is_mac68k, is_macppc
//     is_unix
//     is_sun, is_sun4, is_sun5, is_suni86
//     is_irix, is_irix5, is_irix6
//     is_hpux, is_hpux9, is_hpux10
//     is_aix, is_aix1, is_aix2, is_aix3, is_aix4
//     is_linux, is_sco, is_unixware, is_mpras, is_reliant
//     is_dec, is_sinix, is_freebsd, is_bsd
//     is_vms
//
// See http://www.it97.de/JavaScript/JS_tutorial/bstat/navobj.html and
// http://www.it97.de/JavaScript/JS_tutorial/bstat/Browseraol.html
// for detailed lists of userAgent strings.
//
// Note: you don't want your Nav4 or IE4 code to "turn off" or
// stop working when new versions of browsers are released, so
// in conditional code forks, use is_ie5up ("IE 5.0 or greater") 
// is_opera5up ("Opera 5.0 or greater") instead of is_ie5 or is_opera5
// to check version in code which you want to work on future
// versions.

    // convert all characters to lowercase to simplify testing
    var agt=navigator.userAgent.toLowerCase();

    // *** BROWSER VERSION ***
    // Note: On IE5, these return 4, so use is_ie5up to detect IE5.
    var is_major = parseInt(navigator.appVersion);
    var is_minor = parseFloat(navigator.appVersion);

    // Note: Opera and WebTV spoof Navigator.  We do strict client detection.
    // If you want to allow spoofing, take out the tests for opera and webtv.
    var is_nav  = ((agt.indexOf('mozilla')!=-1) && (agt.indexOf('spoofer')==-1)
                && (agt.indexOf('compatible') == -1) && (agt.indexOf('opera')==-1)
                && (agt.indexOf('webtv')==-1) && (agt.indexOf('hotjava')==-1));
    var is_nav2 = (is_nav && (is_major == 2));
    var is_nav3 = (is_nav && (is_major == 3));
    var is_nav4 = (is_nav && (is_major == 4));
    var is_nav4up = (is_nav && (is_major >= 4));
    var is_navonly      = (is_nav && ((agt.indexOf(";nav") != -1) ||
                          (agt.indexOf("; nav") != -1)) );
    var is_nav6 = (is_nav && (is_major == 5));
    var is_nav6up = (is_nav && (is_major >= 5));
    var is_gecko = (agt.indexOf('gecko') != -1);


    var is_ie     = ((agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1));
    var is_ie3    = (is_ie && (is_major < 4));
    var is_ie4    = (is_ie && (is_major == 4) && (agt.indexOf("msie 5")==-1) );
    var is_ie4up  = (is_ie && (is_major >= 4));
    var is_ie5    = (is_ie && (is_major == 4) && (agt.indexOf("msie 5.0")!=-1) );
    var is_ie5_5  = (is_ie && (is_major == 4) && (agt.indexOf("msie 5.5") !=-1));
    var is_ie5up  = (is_ie && !is_ie3 && !is_ie4);
    var is_ie5_5up =(is_ie && !is_ie3 && !is_ie4 && !is_ie5);

    // KNOWN BUG: On AOL4, returns false if IE3 is embedded browser
    // or if this is the first browser window opened.  Thus the
    // variables is_aol, is_aol3, and is_aol4 aren't 100% reliable.
    var is_aol   = (agt.indexOf("aol") != -1);
    var is_aol3  = (is_aol && is_ie3);
    var is_aol4  = (is_aol && is_ie4);
    var is_aol5  = (agt.indexOf("aol 5") != -1);
    var is_aol6  = (agt.indexOf("aol 6") != -1);

    var is_opera = (agt.indexOf("opera") != -1);
    var is_opera2 = (agt.indexOf("opera 2") != -1 || agt.indexOf("opera/2") != -1);
    var is_opera3 = (agt.indexOf("opera 3") != -1 || agt.indexOf("opera/3") != -1);
    var is_opera4 = (agt.indexOf("opera 4") != -1 || agt.indexOf("opera/4") != -1);
    var is_opera5 = (agt.indexOf("opera 5") != -1 || agt.indexOf("opera/5") != -1);
    var is_opera5up = (is_opera && !is_opera2 && !is_opera3 && !is_opera4);

    var is_webtv = (agt.indexOf("webtv") != -1); 

    var is_TVNavigator = ((agt.indexOf("navio") != -1) || (agt.indexOf("navio_aoltv") != -1)); 
    var is_AOLTV = is_TVNavigator;

    var is_hotjava = (agt.indexOf("hotjava") != -1);
    var is_hotjava3 = (is_hotjava && (is_major == 3));
    var is_hotjava3up = (is_hotjava && (is_major >= 3));

    // *** JAVASCRIPT VERSION CHECK ***
    var is_js;
    if (is_nav2 || is_ie3) is_js = 1.0;
    else if (is_nav3) is_js = 1.1;
    else if (is_opera5up) is_js = 1.3;
    else if (is_opera) is_js = 1.1;
    else if ((is_nav4 && (is_minor <= 4.05)) || is_ie4) is_js = 1.2;
    else if ((is_nav4 && (is_minor > 4.05)) || is_ie5) is_js = 1.3;
    else if (is_hotjava3up) is_js = 1.4;
    else if (is_nav6 || is_gecko) is_js = 1.5;
    // NOTE: In the future, update this code when newer versions of JS
    // are released. For now, we try to provide some upward compatibility
    // so that future versions of Nav and IE will show they are at
    // *least* JS 1.x capable. Always check for JS version compatibility
    // with > or >=.
    else if (is_nav6up) is_js = 1.5;
    // NOTE: ie5up on mac is 1.4
    else if (is_ie5up) is_js = 1.3

    // HACK: no idea for other browsers; always check for JS version with > or >=
    else is_js = 0.0;

    // *** PLATFORM ***
    var is_win   = ( (agt.indexOf("win")!=-1) || (agt.indexOf("16bit")!=-1) );
    // NOTE: On Opera 3.0, the userAgent string includes "Windows 95/NT4" on all
    //        Win32, so you can't distinguish between Win95 and WinNT.
    var is_win95 = ((agt.indexOf("win95")!=-1) || (agt.indexOf("windows 95")!=-1));

    // is this a 16 bit compiled version?
    var is_win16 = ((agt.indexOf("win16")!=-1) || 
               (agt.indexOf("16bit")!=-1) || (agt.indexOf("windows 3.1")!=-1) || 
               (agt.indexOf("windows 16-bit")!=-1) );  

    var is_win31 = ((agt.indexOf("windows 3.1")!=-1) || (agt.indexOf("win16")!=-1) ||
                    (agt.indexOf("windows 16-bit")!=-1));

    var is_winme = ((agt.indexOf("win 9x 4.90")!=-1));
    var is_win2k = ((agt.indexOf("windows nt 5.0")!=-1));

    // NOTE: Reliable detection of Win98 may not be possible. It appears that:
    //       - On Nav 4.x and before you'll get plain "Windows" in userAgent.
    //       - On Mercury client, the 32-bit version will return "Win98", but
    //         the 16-bit version running on Win98 will still return "Win95".
    var is_win98 = ((agt.indexOf("win98")!=-1) || (agt.indexOf("windows 98")!=-1));
    var is_winnt = ((agt.indexOf("winnt")!=-1) || (agt.indexOf("windows nt")!=-1));
    var is_win32 = (is_win95 || is_winnt || is_win98 || 
                    ((is_major >= 4) && (navigator.platform == "Win32")) ||
                    (agt.indexOf("win32")!=-1) || (agt.indexOf("32bit")!=-1));

    var is_os2   = ((agt.indexOf("os/2")!=-1) || 
                    (navigator.appVersion.indexOf("OS/2")!=-1) ||   
                    (agt.indexOf("ibm-webexplorer")!=-1));

    var is_mac    = (agt.indexOf("mac")!=-1);
    // hack ie5 js version for mac
    if (is_mac && is_ie5up) is_js = 1.4;
    var is_mac68k = (is_mac && ((agt.indexOf("68k")!=-1) || 
                               (agt.indexOf("68000")!=-1)));
    var is_macppc = (is_mac && ((agt.indexOf("ppc")!=-1) || 
                                (agt.indexOf("powerpc")!=-1)));

    var is_sun   = (agt.indexOf("sunos")!=-1);
    var is_sun4  = (agt.indexOf("sunos 4")!=-1);
    var is_sun5  = (agt.indexOf("sunos 5")!=-1);
    var is_suni86= (is_sun && (agt.indexOf("i86")!=-1));
    var is_irix  = (agt.indexOf("irix") !=-1);    // SGI
    var is_irix5 = (agt.indexOf("irix 5") !=-1);
    var is_irix6 = ((agt.indexOf("irix 6") !=-1) || (agt.indexOf("irix6") !=-1));
    var is_hpux  = (agt.indexOf("hp-ux")!=-1);
    var is_hpux9 = (is_hpux && (agt.indexOf("09.")!=-1));
    var is_hpux10= (is_hpux && (agt.indexOf("10.")!=-1));
    var is_aix   = (agt.indexOf("aix") !=-1);      // IBM
    var is_aix1  = (agt.indexOf("aix 1") !=-1);    
    var is_aix2  = (agt.indexOf("aix 2") !=-1);    
    var is_aix3  = (agt.indexOf("aix 3") !=-1);    
    var is_aix4  = (agt.indexOf("aix 4") !=-1);    
    var is_linux = (agt.indexOf("inux")!=-1);
    var is_sco   = (agt.indexOf("sco")!=-1) || (agt.indexOf("unix_sv")!=-1);
    var is_unixware = (agt.indexOf("unix_system_v")!=-1); 
    var is_mpras    = (agt.indexOf("ncr")!=-1); 
    var is_reliant  = (agt.indexOf("reliantunix")!=-1);
    var is_dec   = ((agt.indexOf("dec")!=-1) || (agt.indexOf("osf1")!=-1) || 
           (agt.indexOf("dec_alpha")!=-1) || (agt.indexOf("alphaserver")!=-1) || 
           (agt.indexOf("ultrix")!=-1) || (agt.indexOf("alphastation")!=-1)); 
    var is_sinix = (agt.indexOf("sinix")!=-1);
    var is_freebsd = (agt.indexOf("freebsd")!=-1);
    var is_bsd = (agt.indexOf("bsd")!=-1);
    var is_unix  = ((agt.indexOf("x11")!=-1) || is_sun || is_irix || is_hpux || 
                 is_sco ||is_unixware || is_mpras || is_reliant || 
                 is_dec || is_sinix || is_aix || is_linux || is_bsd || is_freebsd);

    var is_vms   = ((agt.indexOf("vax")!=-1) || (agt.indexOf("openvms")!=-1));

///////////////////////////////////////////////////////////////////////////////////////
// begin form validation
///////////////////////////////////////////////////////////////////////////////////////

    // if this client is running an unusable version of JavaScript, skip this validation
    if (is_js < 1.1)
    {
        return true;
    }

    // read in values from the form
    var strSuppliedDay = form.Day.options[form.Day.selectedIndex].text;
    var strSuppliedMonth = form.Month.options[form.Month.selectedIndex].text;
    var strSuppliedYear = form.Year.options[form.Year.selectedIndex].text;


    // convert supplied date strings to IETF date format
    //var strSuppliedDate = "" + strSuppliedDay + " " + strSuppliedMonth + " " + strSuppliedYear + " 00:00:00 CST";
    var strSuppliedDate = "" + strSuppliedDay + " " + strSuppliedMonth + " " + strSuppliedYear + " 23:59:59 CST";

    // get today's date in GMTmilliseconds format
    var Today = new Date();

    // convert supplied date to GMTmilliseconds format
    var SuppliedDate = new Date(strSuppliedDate);

    // if the resulting date object is not a number, there's something wrong with it
    if (isNaN(SuppliedDate)) 
    {
        alert("INVALID CANCEL BY DATE: please supply a valid 'cancel by' date.");
        return false;
    }

    // Compare Today to SuppliedDate
    // If SuppliedDate is less than Today, alert the user, and return false
    if (SuppliedDate <= Today)
    {

        alert("INVALID EVENT DATE: please supply a date that occurs in the future.");
        return false;
    }

    // check to see if the supplied day value is valid for the supplied month and year
    if (!isDayValidForThisMonthAndYear(parseInt(strSuppliedDay), form.Month.selectedIndex, parseInt(strSuppliedYear)))
    {
        alert("INVALID EVENT DATE: are you sure there are " + strSuppliedDay + " days in " + strSuppliedMonth + "?");
        return false;
    }

    // if we made it this far, the date must be good
    return true;

}

// stop hiding -->
</script>


<hr noshade size="4" />
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td>
        <input class="okbutton" type="submit" name="Submit" value="{intl-ok}" />
        <input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
        <input type="hidden" name="Action" value="{action_value}" />
        <input type="hidden" name="eventID" value="{event_id}" />
    </td>
    <td align="right">
        <input type="hidden" name="eventArrayID[]" value={event_id}>
        <input class="stdbutton" type="submit" name="DeleteEvents" value="{intl-delete_events}">
    </td>
</tr>
</table>

<hr noshade size="4" />

</form>
<!-- END no_error_tpl -->
