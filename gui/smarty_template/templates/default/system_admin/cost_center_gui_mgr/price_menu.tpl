<!-- Created By julius 02-09-2017 -->

{{$form_start}}
<div id="new_package">
	<ul>
		<li><a href="#updated_price"><span>Update Services Price Per Ward</span></a></li>
		<li><a href="#View_history"><span>View History</span></a></li>
	</ul>
	<div id="updated_price" style="min-height:400px;">
		<table style="border-collapse: collapse; border: 1px solid rgb(204, 204, 204);" width="60%" align="center">
			<tbody>
			<tr>
				<td class="segPanelHeader" colspan="3"><strong>Search Ward</strong></td>
			</tr>
			<tr>
				<td class="segPanel" align="right" nowrap="nowrap" style="height: 40px;" width="20%"><strong><b>Ward Name</b></strong></td>
				<td class="segPanel" nowrap="nowrap" width="80%">
					<input type="text" id="service_name" class="segInput" size="60" style="background-color: rgb(226, 234, 243); border-width: thin; font: bold 18px Arial; color: rgb(0, 0, 255);" onkeyup="if(this.value.length>=3) startAJAXSearch(this.id,0);">
					<input type="button" id="Search" class="segButton" value="Search" onclick="search()" style="margin-top: -4px;">
				</td>
				
			</tr>
			</tbody>
		</table>
		<div style="height:190px; height:500px;" align="center">
				<div style="height:190px;overflow-y:auto; width:60%; ">
					<table class="segList" border="0" style="width:100%; " cellpadding="0" cellspacing="0" align="center">
					<thead>
						<tr class="nav">
							<th colspan="10">
								<div id="pageFirst" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,FIRST_PAGE)">
									<img title="First" src="../../../images/start.gif" border="0" align="absmiddle"/>
									<span title="First">First</span>
								</div>
								<div id="pagePrev" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,PREV_PAGE)">
									<img title="Previous" src="../../../images/previous.gif" border="0" align="absmiddle"/>
									<span title="Previous">Previous</span>
								</div>
								<div id="pageShow" style="float:left; margin-left:10px">
									<span></span>
								</div>
								<div id="pageLast" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,LAST_PAGE)">
									<span title="Last">Last</span>
									<img title="Last" src="../../../images/end.gif" border="0" align="absmiddle"/>
								</div>
								<div id="pageNext" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,NEXT_PAGE)">
									<span title="Next">Next</span>
									<img title="Next" src="../../../images/next.gif" border="0" align="absmiddle"/>
								</div>
							</th>
						</tr>
					</thead>
					</table>
					<table id="gcosCenter" class="jedList" style="width:100%; " border="0" cellpadding="0" cellspacing="0" align="center">
						<thead>
								<tr>
									<th width="2%" nowrap="nowrap">Ward Name</th>
									<th width="10%">Ward ID</th>
									<th width="10%">PHIC</th>
									<th width="3%">NON - PHIC</th>
									<th width="3%">Update</th>
								</tr>
						</thead>
						<tbody id="guilist-cos">
								<tr><td colspan="5" style="">No GUI added..</td></tr>
						</tbody>
					</table>
					</div>
			
					</div>
	</div>
	<div id="View_history" style="min-height:300px;">
		<div style="height:190px;" align="center">
				<div style="height:190px;overflow-y:auto; width:60%; ">
					<table class="segList" border="0" style="width:100%; " cellpadding="0" cellspacing="0" align="center">
					<thead>
						<tr class="nav">
							<th colspan="10">
								<div id="pageFirst" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,FIRST_PAGE)">
									<img title="First" src="../../../images/start.gif" border="0" align="absmiddle"/>
									<span title="First">First</span>
								</div>
								<div id="pagePrev" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,PREV_PAGE)">
									<img title="Previous" src="../../../images/previous.gif" border="0" align="absmiddle"/>
									<span title="Previous">Previous</span>
								</div>
								<div id="pageShow" style="float:left; margin-left:10px">
									<span></span>
								</div>
								<div id="pageLast" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,LAST_PAGE)">
									<span title="Last">Last</span>
									<img title="Last" src="../../../images/end.gif" border="0" align="absmiddle"/>
								</div>
								<div id="pageNext" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,NEXT_PAGE)">
									<span title="Next">Next</span>
									<img title="Next" src="../../../images/next.gif" border="0" align="absmiddle"/>
								</div>
							</th>
						</tr>
					</thead>
					</table>
					<table id="historyCcenter" class="jedList" style="width:100%; " border="0" cellpadding="0" cellspacing="0" align="center">
						<thead>
								<tr>
									<th width="2%" nowrap="nowrap">Ward Name</th>
									<th width="10%">Ward ID</th>
									<th width="10%">PHIC</th>
									<th width="3%">NON - PHIC</th>
									<th width="3%">Date Create</th>
									<th width="3%">Created By</th>
								</tr>
						</thead>
						<tbody id="guilist-cos">
								<tr><td colspan="4" style="">No GUI added..</td></tr>
						</tbody>
					</table>
					</div>
			
					</div>
	</div>
</div>
<br/>
{{$form_end}}
