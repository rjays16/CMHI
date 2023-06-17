<style>

.label{
	width: 30%;
	padding-left: 10px;
}

.data{
	width: 70%;
}

.dashletHeader tr td{
	vertical-align: center;
}

.dashletHeader tr{
	margin-top: 20px;
}

.p{
	text-indent: 2em;
}

#segcompre{
	height: 500px;
}

#order-list-header{
	background-color: #536EAC;
	color: white;
	font-weight: bold;
	text-align: center;
}

#info{
	float: left;
	width: 50%;
	height: 20px;
}


.top{
	width: 93%;
	height: 9px;
	border: 1px #000 solid;
	cursor: pointer;
}

.bottom{
	width: 93%;
	height: 9px;
	border: 1px #000 solid;
	cursor: pointer;
	float:left;
}

.left{
	width: 27%;
	height: 9px;
	border: 1px #000 solid;
	cursor: pointer;
	float:left;
}

.right{
	width: 27%;
	height: 9px;
	border: 1px #000 solid;
	cursor: pointer;
	float:left;
}

.center{
	width: 32%;
	height: 9px;
	border: 1px #000 solid;
	cursor: pointer;
	float:left;
}

.tooth{
	width: 6%;
	height: 30px;
}

.toothContainer{
	width: 100%;
	height: 100%;
}
</style>

<div id="segcompre">
	<div style="width:90%; margin-top:10px; height: 17%;" align="left">
			<table border="0" cellspacing="2" cellpadding="3" align="center" width="100%">
				<tbody>
					<tr>
						<td class="segPanelHeader" width="*" colspan="2">Patient Details</td>
					</tr>
					<tr>
						<td class="segPanel" align="left" valign="top">
							<table  width="100%" class="transaction_details_table" cellpadding="0" cellspacing="0" style="font:normal 12px Arial; padding:4px" >
								<tr>
									<td align="left" width="30%" nowrap="nowrap"><strong>HRN : </strong>{{$sPatientID}}</td>
									<td nowrap="nowrap"><strong>Age : </strong>{{$sAge}}</td>
									<td width="30%" nowrap="nowrap"><strong>Date of Birth: </strong>{{$sBirthdate}}</td>
								</tr>
								<tr>
									<td align="left" width="30%" nowrap="nowrap"><strong>Patient Name : </strong>{{$sPatientName}}</td>
									<td nowrap="nowrap"><strong>Address : </strong>{{$sAddress}}</td>
									<td width="30%" nowrap="nowrap"><strong>Gender: </strong>{{$sGender}}</td>
								</tr>
								<tr>
									<td align="left" width="30%" nowrap="nowrap"><strong>Contact : </strong>{{$sContact}}</td>
								</tr>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		<div>
	</div>
	</div>

	<div id="tab-compre" style="padding:1%;">
		PHYSICAL AND MEDICAL HISTORY
			<div class="dashlet" style="margin-top:5px; width:90%;">
				<table width="100%" cellpadding="5px" cellspacing="0" border="0" class="dashletHeader" style="font: bold 12px Tahoma; margin-top: 20px">
					<tbody>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Tongue:</p></td>
							<td class="data">{{$sTongue}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Palate:</p></td>
							<td class="data">{{$sPalate}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Tonsils:</p></td>
							<td class="data">{{$sTonsils}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Lips:</p></td>
							<td class="data">{{$sLips}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Floor of Mouth:</p></td>
							<td class="data">{{$sFloorMouth}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Cheeks:</p></td>
							<td class="data">{{$sCheeks}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Allergies:</p></td>
							<td class="data">{{$sAllergies}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Heart Disease:</p></td>
							<td class="data">{{$sHeartDisease}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Blood Dyscracia:</p></td>
							<td class="data">{{$sBloodDys}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Diabetes:</p></td>
							<td class="data">{{$sDiabetes}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Kidney:</p></td>
							<td class="data">{{$sKidney}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Liver:</p></td>
							<td class="data">{{$sLiver}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Others:</p></td>
							<td class="data">{{$sOthers}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Hygiene:</p></td>
							<td class="data">{{$sHygiene}}</td>
						</tr>
						<tr height="100px">
							<td class="label"><p style="text-indent:9.8em;">Tooth #:</p></td>
							<td class="data">
								<table>
									<tr>
										<td>{{$sTooth}}</td>
										<td>{{$sOps}}</td>
										<td>{{$sCon}}</td>
										<td width="20%">
											<div class="toothContainer">
												<table>
													<tr><td align="center" colspan="3">{{$sTooth0}}</td></tr>
													<tr>
														<td>{{$sTooth1}}</td>
														<td>{{$sTooth2}}</td>
														<td>{{$sTooth3}}</td>
													</tr>
													<tr><td align="center" colspan="3">{{$sTooth4}}</td></tr>
												</table>
											</div>
										</td>
										<td>{{$sSaveTooth}}</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Diagnosis:</p></td>
							<td class="data">{{$sDiagnosis}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Tooth Count:</p></td>
							<td class="data">{{$sToothCount}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Details of service/s rendered:</p></td>
							<td class="data">{{$sDetailsServices}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Operator:</p></td>
							<td class="data">{{$sOperator}}</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Checked By:</p></td>
							<td class="data">{{$sCheckedBy}}</td>
						</tr>
						<tr>
							<td class="label"></td>
							<td class="data">{{$sPrintBtnCmp}}{{$sSaveBtnCmp}}{{$sCancelBtnCmp}}</td>
						</tr>
					</tbody>
				</table>
			</div>
	</div>
</div>
</div>