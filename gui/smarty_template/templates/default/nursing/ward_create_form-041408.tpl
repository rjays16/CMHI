{{* ward_create_form.tpl  Form template for creating new ward 2004-06-28 Elpidio Latorilla *}}
{{* Note: the input elements are written in raw form here to give you the chance to redimension them. *}}
{{* Note: In redimensioning the input elements, be very careful not to change their names nor value tags. *}}
{{* Note: Never change the "maxlength" value *}}

<p>

<ul>
{{$sMascotImg}} {{$sStationExists}} {{$LDEnterAllFields}}
<p>
</p>
<!--
<form action="nursing-station-new.php" method="post" name="newstat" onSubmit="return check(this)">
<form action="nursing-station-new.php" method="post" name="newstat" id="newstat" onSubmit="return false;">
-->
<form action="nursing-station-new.php" method="post" name="newstat" id="newstat" onSubmit="return checkWardForm();">
<table>
  <tbody>
    <tr>
      <td class="adm_item">{{$LDAccomodationType}}</td>
      <td class="adm_input">{{$sAccTypeRadio}}</td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDStation}}</td>
      <td class="adm_input">{{$segName}}</td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDWard_ID}}</td>
      <td class="adm_input">{{$segWardID}} {{$LDNoSpecChars}}</td>
    </tr>
	<tr class="charityOnly">
	  <td class="adm_item">{{$LDDept}}</td>
	  <td class="adm_input">{{$sDeptSelectBox}} {{$sSelectIcon}} {{$LDPlsSelect}}</td>
	</tr>
    <tr>
      <td class="adm_item">{{$LDDescription}}</td>
      <td class="adm_input">{{$segDescription}}</td>
    </tr>
	<tr>
		<td class="adm_item">{{$LDWardRate}}</td>
		<td class="adm_input">{{$segWardRate}} {{$segRoomNxtNr}} {{$segRoomStartNr}} {{$segRoomEndNr}}</td>
	</tr>
	<tr class="charityOnly">
		<td class="adm_item">{{$LDRoomNr}}</td>
		<td class="adm_input">{{$segRoomNr}}</td>
	</tr>
	<!--added by VAN 04-10-08 --->
	<!--
	<tr>
		<td class="adm_item">{{$LDRoom1Nr}}</td>
		<td class="adm_input">{{$segRoomStartNr}}</td>
	</tr>
	<tr>
		<td class="adm_item">{{$LDRoom2Nr}}</td>
		<td class="adm_input">{{$segRoomEndNr}}</td>
	</tr>
	-->
	<!-------------------->
	<!-----edited by VAN 04-11-08 --------->
	<!--
	<tr>
		<td class="adm_item">{{$LDRoomInfo}}</td>
		<td class="adm_input">{{$segRoomInfo}}</td>
	</tr>
	<tr>
		<td class="adm_item">{{$LDNoOfBeds}}</td>
		<td class="adm_input">{{$segNrOfBeds}}</td>
	</tr>
	-->
	<tr class="charityOnly">
		<td class="adm_item">{{$LDRoomInfo}}</td>
		<td class="adm_input">{{$segRoomInfo}}</td>
	</tr>
	<tr class="charityOnly">
		<td class="adm_item">{{$LDNoOfBeds}}</td>
		<td class="adm_input">{{$segNrOfBeds}}</td>
	</tr>
<!--
    <tr>
      <td class="adm_item">{{$LDRoom1Nr}}</td>
      <td class="adm_input"><input type="text" name="room_nr_start" size=4 maxlength=4 value="{{$room_nr_start}}" /></td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDRoom2Nr}}</td>
      <td class="adm_input"><input type="text" name="room_nr_end" size=4 maxlength=4 value="{{$room_nr_end}}" /></td>
    </tr>
-->
    <tr>
      <td class="adm_item">{{$LDRoomPrefix}}</td>
      <td class="adm_input">{{$segRoomPrefix}}</td>
    </tr>
	
	<tr class="paywardOnly">
		<td colspan="2">{{$segAddRoom}}</td>
	</tr>
	<tr class="paywardOnly">
		<td colspan="2">
			<table id="room-list" class="segList" border="0" width="100%" cellpadding="1" cellspacing="1" style="border:1px solid #666666;border-bottom:0px;">
				<thead>
					<tr class="reg_list_titlebar">
						<td><font face="verdana,arial" size="2" >&nbsp;<b> Room No. </b></font></td>
						<td><font face="verdana,arial" size="2" >&nbsp;<b> No. of Beds </b></font></td>
						<td><font face="verdana,arial" size="2" > <b>&nbsp; Room's short description &nbsp;</b></font></td>
						<td><font face="verdana,arial" size="2" > <b>&nbsp; &nbsp;</b></font></td>
					</tr>
				</thead>
				<tbody>
<!--
					<tr class="reg_list_titlebar">
						<td><font face="verdana,arial" size="2" >
							<input type="hidden" name="rooms[]" id="rooms0" value="3000">
							3000
							</font>
						</td>
						<td align="center">
							<input type="text" name="beds[]" id="beds0" size="8" maxlength="3" value="5">
						</td>
						<td>
							<input type="text" name="info[]" id="info0" size=50 maxlength=100 value="hello">
						</td>
						<td>{{$segDeleteRoom}}</td>
					</tr>
-->
					<tr>
					<!-- List of beds -->
						<td colspan="4" align="center" bgcolor="#FFFFFF" style="color:#FF0000; font-family:'Arial', Courier, mono; font-style:Bold; font-weight:bold; font-size:12px;">
							List of rooms is currently empty...
						</td>
					</tr>
				</tbody>
			</table>		
		</td>
	</tr>
  </tbody>
</table>
<br>
<!--{{$sSaveButton}}-->
<table>
	<tr>
		<td>{{$sSaveButton}}</td>
		<td>{{$sCancel}}</td>
	</tr>
</table>
{{$segInitialization}}
</form>
<form action="nursing-station-new.php?mode=update" method="post" name="viewstat" id="viewstat" onSubmit="">
	{{$sFormModeUpdate}}
</form>
<p>
<!--{{$sCancel}}-->
</p>
</ul>
