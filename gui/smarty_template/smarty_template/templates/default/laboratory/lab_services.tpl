{{* test_params.tpl  Test parameter administration template 2004-06-27 Elpidio Latorilla  *}}
<!--<div align="center" style="font:bold 12px Tahoma; color:#990000; ">{{$sMessage}}</div><br />-->
<form {{$sFormAction}} method=post onSubmit="return chkselect(this)" name="paramselect">
<br>
<div>
</div>
<ul>
<table cellpadding="1" cellspacing="0">
	<tr height="32">
		<td valign="top">
			<table>
			  <tbody>
				 <tr>
					<td colspan="3" class="prompt">{{$LDSelectParamGroup}}</td>
				 </tr>
				 <tr>
					<!--<td>Select service group</td>-->
					<td><span>{{$sFilter}}</span>	</td>
					<td>{{$sParamGroupSelect}}</td>
					<td>&nbsp;{{$sSubmitSelect}}</td>
				 </tr>
			  </tbody>
			</table>
		</td>
	</tr>
</table>

<table cellspacing="0 "cellpadding="0" border="0" class="frame">
  <tbody>
    <tr>
      <td id="sparamgroup" style="color:white; background-color: red; font-weight:bold;">
		&nbsp;
		{{$sParamGroup}}
	  </td>
    </tr>
    <tr>
      <td bgcolor="#ffffff">
	    	<table id="serviceTable" border="0" cellpadding="1" cellspacing="1" width="600" style="border:1px solid #666666;border-bottom:0px">
					<thead>
						<tr class="reg_list_titlebar" style="font-weight:bold;padding:0px">
							<td valign="middle" align="center" width="15%" rowspan="2">Code</td>
							<td valign="middle" align="center" width="35%" rowspan="2">Services</td>
							<td valign="middle" align="center" width="30%" colspan="2">Price</td>
							<td valign="middle" width="10%" align="center" rowspan="2">Edit</td>
							<td valign="middle" width="10%" align="center" rowspan="2">Delete</td>
						</tr>
						<tr class="reg_list_titlebar" style="font-weight:bold;padding:0px">
							<td valign="middle" width="15%" align="center">Cash</td>
							<td valign="middle" width="15%" align="center">Charge</td>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				<table border="0" cellpadding="2" cellspacing="1" width="600" style="border:1px solid #666666;border-top:0px;margin-top:-1px">
					<tr class="reg_list_titlebar" >
						<td valign="middle" align="center" width="15%">
							<input id="srvcode" name="srvcode" type="text" value="" size="5" style="width:100%">
						</td>
						<td valign="middle" align="center" width="35%">
							<input id="srvname" name="srvname" type="text" value="" size="30" style="width:100%">
						</td>
						<td valign="middle" align="center" width="15%">
							<input id="srvcash" name="srvcash"type="text" value="" size="6" style="width:100%">
						</td>
						<td valign="middle" align="center" width="15%">
							<input id="srvcharge" name="srvcharge" type="text" value="" size="6" style="width:100%">
						</td>
						<td valign="middle" align="center" width="20%">
							<input type="button" value="Add Service" style="cursor:pointer" onclick="validate('srvcode','srvname','srvcash','srvcharge')">
						</td>
					</tr>
				</table>
		  </td>
    </tr>
  </tbody>
</table>
</form>
</ul>