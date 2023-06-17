<?php /* Smarty version 2.6.0, created on 2017-01-09 14:46:25
         compiled from clinics/seg-dental.tpl */ ?>
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
									<td align="left" width="30%" nowrap="nowrap"><strong>HRN : </strong><?php echo $this->_tpl_vars['sPatientID']; ?>
</td>
									<td nowrap="nowrap"><strong>Age : </strong><?php echo $this->_tpl_vars['sAge']; ?>
</td>
									<td width="30%" nowrap="nowrap"><strong>Date of Birth: </strong><?php echo $this->_tpl_vars['sBirthdate']; ?>
</td>
								</tr>
								<tr>
									<td align="left" width="30%" nowrap="nowrap"><strong>Patient Name : </strong><?php echo $this->_tpl_vars['sPatientName']; ?>
</td>
									<td nowrap="nowrap"><strong>Address : </strong><?php echo $this->_tpl_vars['sAddress']; ?>
</td>
									<td width="30%" nowrap="nowrap"><strong>Gender: </strong><?php echo $this->_tpl_vars['sGender']; ?>
</td>
								</tr>
								<tr>
									<td align="left" width="30%" nowrap="nowrap"><strong>Contact : </strong><?php echo $this->_tpl_vars['sContact']; ?>
</td>
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
							<td class="data"><?php echo $this->_tpl_vars['sTongue']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Palate:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sPalate']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Tonsils:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sTonsils']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Lips:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sLips']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Floor of Mouth:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sFloorMouth']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Cheeks:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sCheeks']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Allergies:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sAllergies']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Heart Disease:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sHeartDisease']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Blood Dyscracia:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sBloodDys']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Diabetes:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sDiabetes']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Kidney:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sKidney']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Liver:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sLiver']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Others:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sOthers']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Hygiene:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sHygiene']; ?>
</td>
						</tr>
						<tr height="100px">
							<td class="label"><p style="text-indent:9.8em;">Tooth #:</p></td>
							<td class="data">
								<table>
									<tr>
										<td><?php echo $this->_tpl_vars['sTooth']; ?>
</td>
										<td><?php echo $this->_tpl_vars['sOps']; ?>
</td>
										<td><?php echo $this->_tpl_vars['sCon']; ?>
</td>
										<td width="20%">
											<div class="toothContainer">
												<table>
													<tr><td align="center" colspan="3"><?php echo $this->_tpl_vars['sTooth0']; ?>
</td></tr>
													<tr>
														<td><?php echo $this->_tpl_vars['sTooth1']; ?>
</td>
														<td><?php echo $this->_tpl_vars['sTooth2']; ?>
</td>
														<td><?php echo $this->_tpl_vars['sTooth3']; ?>
</td>
													</tr>
													<tr><td align="center" colspan="3"><?php echo $this->_tpl_vars['sTooth4']; ?>
</td></tr>
												</table>
											</div>
										</td>
										<td><?php echo $this->_tpl_vars['sSaveTooth']; ?>
</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Diagnosis:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sDiagnosis']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Tooth Count:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sToothCount']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Details of service/s rendered:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sDetailsServices']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Operator:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sOperator']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Checked By:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sCheckedBy']; ?>
</td>
						</tr>
						<tr>
							<td class="label"></td>
							<td class="data"><?php echo $this->_tpl_vars['sPrintBtnCmp'];  echo $this->_tpl_vars['sSaveBtnCmp'];  echo $this->_tpl_vars['sCancelBtnCmp']; ?>
</td>
						</tr>
					</tbody>
				</table>
			</div>
	</div>
</div>
</div>