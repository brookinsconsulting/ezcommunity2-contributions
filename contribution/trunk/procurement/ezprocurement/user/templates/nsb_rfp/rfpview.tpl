<div id="LayerContent" class="LayerContent" style="width: 100%;"> 

	<span>
	<!-- BEGIN rfp_path_header_tpl -->
		<span class="subdiv">/</span>
		<a class="subdiv" style="text-decoration: none; color: rgb(124, 122, 110);" href="{www_dir}{index}/procurement/archive/0/">{intl-top_level}</a>
	<!-- END rfp_path_header_tpl -->
		<span class="subdiv">/</span>
		<a class="subdiv" style="text-decoration: none; color: rgb(124, 122, 110);" href="{www_dir}{index}/procurement/archive/10/">{intl-north_slope_burough_category}</a>
	
	<!-- BEGIN path_item_tpl -->
		<span class="subdiv">/</span>
		<a class="subdiv" style="text-decoration: none; color: rgb(124, 122, 110);" href="{www_dir}{index}/procurement/archive/{category_id}/">{category_name}</a>
	<!-- END path_item_tpl -->
	</span>

	<span>
	<!-- BEGIN rfp_header_tpl -->
	<div> 
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td width="51%" valign="top" style="padding-top: 7px;">
		   <span style=""><a class="subdiv" style="font-size: 13px; font-weight: normal; color: black; text-decoration: none;" href="{rfp_uri}">{rfp_name}</a></span><br /><br />
		
	        <!-- BEGIN rfp_author_list_tpl -->
                <span class="subdiv" style="padding-top: 7px;">{intl-rfp_author}: </span>
		<!-- BEGIN rfp_author_tpl -->
		<div style="text-indent: 4pt;">
		 <!-- BEGIN procurement_holder_organization_tpl -->
		 <a class="subdiv" href="{www_dir}{index}/procurement/company/view/{author_organization_id}" style="text-decoration: none; color: rgb(0,0,0);">{author_organization}</a> : 
                 <!-- END procurement_holder_organization_tpl -->
		 <a class="subdiv" href="{www_dir}{index}/procurement/holder/view/{author_id}" style="text-decoration: none; color: rgb(0,0,0);">{author_text}</a>
		</div>
		<!-- END rfp_author_tpl -->
                <!-- END rfp_author_list_tpl -->

                <!-- BEGIN procurement_become_planholder_tpl -->
                <span style="text-indent: 20pt; position: relative; top: 7px;">
                <a class="subdiv" href="{www_dir}{index}/procurement/join/{procurement_id}">{intl-become_planholder_text}</a>
		</span>
                <!-- END procurement_become_planholder_tpl -->
	  	</td>

                <td width="49%" style="padding-top: 7px;">
	        <!-- BEGIN rfp_project_manager_list_tpl -->
                <span class="subdiv">{intl-project_manager}:</span><br />		
		<!-- BEGIN rfp_project_manager_tpl -->
		<div style="text-indent: 4pt; padding-bottom: 5px;">
		 <!-- BEGIN project_manager_organization_item_tpl -->
		 <a class="subdiv" style="text-decoration: none; color: rgb(0,0,0);" href="{www_dir}{index}/procurement/company/view/{project_manager_organization_id}">{project_manager_organization}</a> : 
                 <!-- END project_manager_organization_item_tpl -->
		 <a class="subdiv" style="text-decoration: none; color: rgb(0,0,0);" href="{www_dir}{index}/procurement/holder/view/{project_manager_id}">{project_manager_text}</a>
		</div>
		<!-- END rfp_project_manager_tpl -->
                <!-- END rfp_project_manager_list_tpl -->

		<!-- BEGIN procurement_number_item_tpl -->
                <span class="subdiv">{intl-procurement_number}:&nbsp;</span> <span>{procurement_number}</span><br />
                <!-- END procurement_number_item_tpl -->

		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<!-- BEGIN rfp_estimate_tpl -->
		<tr>
		  <td width="51%"><span class="subdiv">{intl-rfp_project_estimate}:</span></td>
                  <td width="49%"><span>{intl-rfp_project_estimate_cash_sign} {rfp_project_estimate}</span></td>
		</tr>
		<!-- END rfp_estimate_tpl -->
		<tr>
		  <td><span class="subdiv" style="">{intl-rfp_date}:</span><br /></td>
                  <td><span>{rfp_created}</span></td>
		</tr>
		<tr>
		  <td><span class="subdiv" style="">{intl-rfp_updated_date}:</span><br /></td>
                  <td><span>{rfp_modified}</span></td>
	        </tr>
		<tr><td><span class="subdiv" style="">{intl-rfp_responce_due_date}:</span><br /></td>
                <td><span>{rfp_responce_due_date}</span></td></tr>
		   <!-- BEGIN bid_closed_item_tpl -->
                   <tr><td><span class="subdiv" style="">{intl-bid_closed_title}:</span></td>
                   <td><span>{bid_closed_flag}</span></td></tr>
		   <!-- END bid_closed_item_tpl -->

		   <!-- BEGIN bid_award_date_item_tpl -->
	           <tr><td><span class="subdiv" style="">{intl-bid_award_date}:</span>
		   </td>
                   <td><span><span style="text-decoration:underline;">{bid_award_date}</span></td></tr>
		   <!-- END bid_award_date_item_tpl -->
		</table>

                  </td>
                </tr>
	      </table>
<!--
	     <span style="position: relative; top: 15%;  margin-bottom: 10px;">
	     </span>

	     <span style="border: solid white; position: absolute; left: 55%; top: 4%; width: 340px; margin-bottom: 200px; clear: left; clear: bottom;">
		</span>
-->
	</div>

	<!-- END rfp_header_tpl -->
	<div class="subdiv" style="color: black; padding-top: 15px; padding-bottom: 15px;">{rfp_body}</div>

		<span style="">
		<!-- BEGIN attached_file_list_tpl -->
		<h1>{intl-attached_files}</h1>
		<!-- BEGIN attached_file_tpl -->
		<div class="{td_class}" style="position:relative; left:0px; right:0px; padding: 3px; z-index:1">
		   <span class="{td_class}" style="color: #7c7a6e; font-size: 12px;">

		     <span style="Z-index:2;"><a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}">{file_name}</a></span>

<span class="{td_class}" style="font-size: 9px; padding: 7px; position:absolute; right:0px; Z-index:0;">
                      <a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}"> {file_size}&nbsp;{file_unit}</a>
                     </span>

                     <span style="font-size: 11px"><br />{file_description}</span>
		   </span>
		</div>
		<!-- END attached_file_tpl -->
		<!-- END attached_file_list_tpl -->
		</span>
		<br />

		<!-- BEGIN bid_list_tpl -->
                <h1>{intl-planholder_bids}</h1>
                <!-- BEGIN bid_tpl -->
                <div class="{td_class}" style="position:relative; left:0px; right:0px; padding: 3px; z-index:1">
                   <span class="{td_class}" style="color: #7c7a6e; font-size: 12px;">
                     <span style="padding-left: 3px; Z-index:2;"> 

	                <!-- BEGIN bid_winner_tpl -->
                        <span style="font-size: 11px; color: green;">{bid_iswinner}</span> :
                        <!-- END bid_winner_tpl -->
                        <!-- BEGIN bid_rank_tpl -->
                        <span style="font-size: 11px; {rank_color}">{bid_rank_alpha}</span> :
                        <!-- END bid_rank_tpl -->



<!--
			<span style="font-size: 11px"> {bid_date}</span> : 
-->

<span>			<a href="{www_dir}{index}/procurement/company/view/{bid_company_id}/" style="text-decoration: none;">{bid_company_name}</a></span> :  <a href="{www_dir}{index}/procurement/holder/view/{bidder_id}/" style="text-decoration: none;">{bidder_name}</a>


			<span class="{td_class}" style="font-size: 9px; padding: 1px; padding-right: 4px;  position:absolute; right:0px; Z-index:0;">
                         <span>{intl-bid_currency_symbol}&nbsp;{bid_amount}</span>
                        </span>

		</span>
<!--
                     <span style="font-size: 11px"><br /></span>
-->


                   </span>
                </div>
                <!-- END bid_tpl -->
                <!-- END bid_list_tpl -->


	</span>

</div>