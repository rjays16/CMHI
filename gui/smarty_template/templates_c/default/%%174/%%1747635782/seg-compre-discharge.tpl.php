<?php /* Smarty version 2.6.0, created on 2017-01-06 09:10:53
         compiled from clinics/seg-compre-discharge.tpl */ ?>
<style>

.label{
	width: 30%;
	padding-left: 10px;
	text-align: right;
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


#diagnosis{
	background-color: white;
	width: 100%; 
	height: 200px; 
	padding: 5px;
	position: relative;
}

#searchResults{
	margin-left: 118px; 
	outline: thin solid; 
	max-height: 100px; 
	
	background-color: white;
	width: 65%;
	z-index: 1;
	position:absolute; 
	top:28px; 
	left:0;
	display: none;
}

#divDiagnosis{
	width: 100%; 
	height: 180px; 
}

#tableDiagnosis{
	width: 100%;
	margin-top: 10px;
	max-height: 175px;
}

.icd{
	cursor: pointer;
}

.icd:hover{
	background-color: #426FD9;
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

#images-thumb{
	max-height: 63%;
	background-color: #FFF;
	overflow-y:scroll;
	white-space: nowrap;
}

#images{
	max-width: 65%;
	height: 150px;
	outline: 0px none;
}

#btn-addimg{
	width: 100%;
	height: 20px;
}

#info{
	float: left;
	width: 50%;
	height: 20px;
}

.img-wrap{
	position: relative;
    display: inline-block;
    border: 1px #7F9DB9 solid;
    font-size: 0;
    float:left;
    margin-left: 3px;
    margin-top: 2px;
    height: 70px;
    width: 100px;
    cursor: pointer;;
}

.img-wrap .close {
    position: absolute;
    top: 2px;
    right: 2px;
    z-index: 100;
    background-color: #FFF;
    padding: 5px 2px 2px;
    color: #000;
    font-weight: bold;
    cursor: pointer;
    opacity: .2;
    text-align: center;
    font-size: 18px;
    line-height: 10px;
    border-radius: 50%;
}

.img-wrap:hover .close {
    opacity: 1;
}

.img-wrap:hover{
	box-shadow: 2px 2px 2px #888888;
}

</style>

<div id="segcompre">
	<div style="width:90%; margin-top:10px; height: 20%;" align="left">
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
									<td width="30%" nowrap="nowrap"><strong>Patient Type : </strong><?php echo $this->_tpl_vars['sPtype']; ?>
</td>
									<td width="30%" nowrap="nowrap"><strong>Ward/Room : </strong><?php echo $this->_tpl_vars['sWardRoom']; ?>
</td>
								</tr>
								<tr>
									<td align="left" width="30%" nowrap="nowrap"><strong>Patient Name : </strong><?php echo $this->_tpl_vars['sPatientName']; ?>
</td>
									<td nowrap="nowrap"><strong>Age : </strong><?php echo $this->_tpl_vars['sAge']; ?>
</td>
									<td nowrap="nowrap"><strong>Gender : </strong><?php echo $this->_tpl_vars['sGender']; ?>
</td>
								</tr>
								<tr>
									<td colspan = "2" align="left" width="30%" nowrap="nowrap"><strong>Address : </strong><?php echo $this->_tpl_vars['sAddress']; ?>
</td>
									<td nowrap="nowrap"><strong>Civil Status : </strong><?php echo $this->_tpl_vars['sCivStat']; ?>
</td>
								</tr>
								<tr>
									<td nowrap="nowrap"><strong>Weight : </strong><?php echo $this->_tpl_vars['sWeight']; ?>
</td>
									<td nowrap="nowrap"><strong>Height : </strong><?php echo $this->_tpl_vars['sHeight']; ?>
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

<div id="tabs" style="width:85%;margin-top:10px;">
	<ul>
		<li style ="width:150px;margin-left:30px"><a href="#tab-compre">Comprehensive Report</a></li>
		<li style ="width:150px;"><a href="#tab-discharge">Discharge Information</a></li>
		<li style ="width:150px;"><a href="#tab-prescription">List of Prescriptions</a></li>
		<li style ="width:150px;"><a href="#tab-lab-res">Laboratory Results</a></li>
		<li style ="width:150px;"><a href="#tab-rad-res">Radiology Results</a></li>
		<li style ="width:150px;"><a href="#tab-pe">PE Exam</a></li>
		<li style ="width:150px;"><a href="#tab-history">History</a></li>
	</ul>

	<div id="tab-compre" class="tabs">
			<div class="dashlet" style="margin-top:5px">
				<table width="100%" cellpadding="5px" cellspacing="0" border="0" class="dashletHeader" style="font: bold 12px Tahoma; margin-top: 20px">
					<tbody>
						<tr>
							<td class="label">Images: </td>
							<td class="data"><div id="images">
												<?php echo $this->_tpl_vars['sImages']; ?>

												<div id="btn-addimg">
													<div id="info"></div>
													<?php echo $this->_tpl_vars['sAddImg']; ?>

												</div>
											</div>
							</td>
						</tr>
						<tr>
							<td class="label">Chief Complaints: </td>
							<td class="data"><?php echo $this->_tpl_vars['sChiefComplaint']; ?>
</td>
						</tr>
						<tr>
							<td class="label">History of Present Illness: </td>
							<td class="data"><?php echo $this->_tpl_vars['sHistoryIllness']; ?>
</td>
						</tr>
						<tr>
							<td class="label">PE/Review of Systems: </td>
							<td class="data"></td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:5.8em;">General Survey: </p></td>
							<td class="data"><?php echo $this->_tpl_vars['sGeneralSurvey']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Vital Signs: </p></td>
							<td class="data">
								<table>
									<tr>
										<td>Heart Rate: (b/m)</td>
										<td><?php echo $this->_tpl_vars['sHeartRate']; ?>
</td>
										<td>Respiratory Rate: (br/m)</td>
										<td><?php echo $this->_tpl_vars['sRespRate']; ?>
</td>
									</tr>
									<tr>
										<td>BP: (mm Hg)</td>
										<td><?php echo $this->_tpl_vars['sBPSys']; ?>
/<?php echo $this->_tpl_vars['sBPDia']; ?>
</td>
										<td>Temp: (C)</td>
										<td><?php echo $this->_tpl_vars['sTemp']; ?>
</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Skin:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sSkin']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Head and Neck:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sHeadNeck']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Eye:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sEye']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Ear:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sEar']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Chest/Back:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sChestLungs']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Lungs:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sLungsC']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">CVS:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sCVS']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Abdomen:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sAbdomen']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Extremities:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sExtremities']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Neuro:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sNeuro']; ?>
</td>
						</tr>
						<tr>
							<td class="label">Past Medical History:</td>
							<td class="data"><?php echo $this->_tpl_vars['sMedHist']; ?>
</td>
						</tr>
						<tr>
							<td class="label">Family History:</td>
							<td class="data"><?php echo $this->_tpl_vars['sFamHist']; ?>
</td>
						</tr>
						<tr>
							<td class="label">Personal/Social History:</td>
							<td class="data"><?php echo $this->_tpl_vars['sPerSoHist']; ?>
</td>
						</tr>
						<tr>
							<td class="label">Immunization History:</td>
							<td class="data"><?php echo $this->_tpl_vars['sImmuHist']; ?>
</td>
						</tr>
						<tr>
							<td class="label">Obstetrical History:</td>
							<td class="data"><?php echo $this->_tpl_vars['sObsHist']; ?>
</td>
						</tr>
						<tr>
							<td class="label">Admitting Impression: </td>
							<td class="data"><?php echo $this->_tpl_vars['sDiagnosis']; ?>
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

	<div id="tab-discharge" class="tabs">
		<div class="dashlet" style="margin-top:5px">
			<table width="100%" cellpadding="5px" cellspacing="0" border="0" class="dashletHeader" style="font: bold 12px Tahoma; margin-top: 20px">
				<tbody>
					<tr>
						<td class="label">Brief History: </td>
						<td class="data"><?php echo $this->_tpl_vars['sHistoryIllnessR']; ?>
</td>
					</tr>
					<tr>
						<td class="label">PE/Review of Systems: </td>
						<td class="data"></td>
					</tr>
					<tr>
						<td class="label"><p style="text-indent:5.8em;">General Survey: </p></td>
						<td class="data"><?php echo $this->_tpl_vars['sGeneralSurveyR']; ?>
</td>
					</tr>
					<tr>
						<td class="label"></td>
						<td class="data">
							<table width="100%">
								<tr>
									<td>Heart Rate: (b/m)</td>
									<td><?php echo $this->_tpl_vars['sHeartRateR']; ?>
</td>
									<td>Respiratory Rate: (br/m)</td>
									<td><?php echo $this->_tpl_vars['sRespRateR']; ?>
</td>
								</tr>
								<tr>
									<td>BP: (mm Hg)</td>
									<td><?php echo $this->_tpl_vars['sBPSysR']; ?>
/<?php echo $this->_tpl_vars['sBPDiaR']; ?>
</td>
									<td>Temp: (C)</td>
									<td><?php echo $this->_tpl_vars['sTempR']; ?>
</td>
								</tr>
							</table>
							<table style="width: 100%;">
								<tr>
									<td>Skin: </td>
									<td><?php echo $this->_tpl_vars['sSkinR']; ?>
</td>
									<td>Head and Neck: </td>
									<td><?php echo $this->_tpl_vars['sHeadNeckR']; ?>
</td>
								</tr>
								<tr>
									<td>Eye: </td>
									<td><?php echo $this->_tpl_vars['sEyeR']; ?>
</td>
									<td>Ear: </td>
									<td><?php echo $this->_tpl_vars['sEarR']; ?>
</td>
								</tr>
								<tr>
									<td>Chest/Back: </td>
									<td><?php echo $this->_tpl_vars['sChestLungsR']; ?>
</td>
									<td>Lungs: </td>
									<td><?php echo $this->_tpl_vars['sLungsR']; ?>
</td>
								</tr>
								<tr>
									<td>Abdomen : </td>
									<td><?php echo $this->_tpl_vars['sAbdomenR']; ?>
</td>
									<td>Extremities: </td>
									<td><?php echo $this->_tpl_vars['sExtremitiesR']; ?>
</td>
								</tr>
								<tr>
									<td>Neuro Exam : </td>
									<td><?php echo $this->_tpl_vars['sNeuroR']; ?>
</td>
									<td>CVS: </td>
									<td><?php echo $this->_tpl_vars['sHeadNeckR']; ?>
</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="label">Medication: </td>
						<td class="data"><?php echo $this->_tpl_vars['sMedication']; ?>
</td>
					</tr>
					<tr>
						<td class="label">Procedure: </td>
						<td class="data"><?php echo $this->_tpl_vars['sProcedure']; ?>
</td>
					</tr>
					<tr>
						<td class="label">Course in the Ward: </td>
						<td class="data"><?php echo $this->_tpl_vars['sCourseWard']; ?>
</td>
					</tr>
					<tr>
						<td class="label">Case Rate Diagnosis: </td>
						<td class="data"><?php echo $this->_tpl_vars['sCaseRateDiagnosis']; ?>
</td>
					</tr>
					<tr>
						<td class="label">Case Rate Procedure: </td>
						<td class="data"><?php echo $this->_tpl_vars['sCaseRateProcedures']; ?>
</td>
					</tr>
					<tr>
						<td class="label" style="color: red">*Diagnosis</td>
						<td class="data"><?php echo $this->_tpl_vars['sNotes']; ?>
</td>
					</tr>
					<tr>
						<td class="label">Final Diagnosis: </td>
						<td class="data">
							<div id="diagnosis">
								Search Diagnosis: <input class="SegInput" type = "text" name="searchDiagnosis" id = "searchDiagnosis" size="50" onkeyup="searchICD();">
								<div id="searchResults" onmouseleave="hideSearchResults();"></div>
								<div id="divDiagnosis">
									<table id = "tableDiagnosis">
										
									</table>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="label">No. of Infections: </td>
						<td class="data"><?php echo $this->_tpl_vars['sNoInfections']; ?>
</td>
					</tr>
					<tr>
						<td class="label">Recommendations: </td>
						<td class="data"><?php echo $this->_tpl_vars['sReco']; ?>
</td>
					</tr>
					<tr>
						<td class="label">Notes: </td>
						<td class="data"><?php echo $this->_tpl_vars['sNote']; ?>
</td>
					</tr>
					<tr>
						<td class="label">Condition on discharged: </td>
						<td class="data"><?php echo $this->_tpl_vars['sCond']; ?>
</td>
					</tr>
					<tr>
						<td class="label">Attending Doctor: </td>
						<td class="data"><?php echo $this->_tpl_vars['sAdmitDoc']; ?>
</td>
					</tr>
					<tr>
						<td class="label"></td>
						<td class="data"><?php echo $this->_tpl_vars['sPrintBtnDisc'];  echo $this->_tpl_vars['sSaveBtnDisc'];  echo $this->_tpl_vars['sCancelBtnDisc']; ?>
</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div id="tab-prescription" class="tabs">
		<?php echo $this->_tpl_vars['sListPrescription']; ?>

	</div>

	<div id="tab-lab-res" class="tabs">
		<table width="80%" class="segList">
			<thead>
				<tr id="order-list-header">
					<td>Results Received</td>
					<td colspan="2">Service(s) Requested</td>
				</tr>
			</thead>
			<tbody>
				<?php echo $this->_tpl_vars['sListLabRes']; ?>

			</tbody>
		</table>
	</div>

	<div id="tab-rad-res" class="tabs">
		<table width="80%" class="segList">
			<thead>
				<tr id="order-list-header">
					<td>Results Received</td>
					<td colspan="3">Service(s) Requested</td>
				</tr>
			</thead>
			<tbody>
				<?php echo $this->_tpl_vars['sListRadRes']; ?>

			</tbody>
		</table>
	</div>

	<div id="tab-history" class="tabs">
		<table width="80%" class='segList' id='order-list'>
			<thead>
				<tr id='order-list-header'>
					<td width='8%'>Date</td>
					<td width='15%'>Encounter #</td>
					<td width='*'>Prescriptions</td>
					<td width='20%'>Chief Complaint</td>
					<td width='20%'>Diagnosis</td>
					<td width='10%'>Important Info</td>
				</tr>
			</thead>
			<tbody>
				<?php echo $this->_tpl_vars['sTblHistory']; ?>

			</tbody>
		</table>
	</div>

	<div id="tab-pe" class="tabs">
		<div class="dashlet" style="margin-top:5px">
				<table width="100%" cellpadding="5px" cellspacing="0" border="0" class="dashletHeader" style="font: bold 12px Tahoma; margin-top: 20px">
					<tbody>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Vital Signs: </p></td>
							<td class="data">
								<table>
									<tr>
										<td>Height: </td>
										<td><?php echo $this->_tpl_vars['sHeightFt']; ?>
 ft. <?php echo $this->_tpl_vars['sHeightIn']; ?>
 in. </td>
										<td>Weight: </td>
										<td><?php echo $this->_tpl_vars['sWeightKg']; ?>
 kg.</td>
									</tr>
									<tr>
										<td>BP: (mm Hg)</td>
										<td><?php echo $this->_tpl_vars['sBPSysP']; ?>
/<?php echo $this->_tpl_vars['sBPDiaP']; ?>
</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Build:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sBuild']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Deformity:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sDeformity']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Skin:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sSkinP']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Head and Neck:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sHeadNeckP']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Chest/Back:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sChestLungsP']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Lungs:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sLungsP']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Eye:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sEyeP']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Vision:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sVision']; ?>
</td>
						</tr>
						<tr>
							<td class="label"><p style="text-indent:9.8em;">Ears:</p></td>
							<td class="data"><?php echo $this->_tpl_vars['sEarP']; ?>
</td>
						</tr>
						<tr>
							<td class="label">Heart:</td>
							<td class="data"><?php echo $this->_tpl_vars['sHeart']; ?>
</td>
						</tr>
						<tr>
							<td class="label">Abdomen:</td>
							<td class="data"><?php echo $this->_tpl_vars['sAbdomenP']; ?>
</td>
						</tr>
						<tr>
							<td class="label">Previous Hospitalization:</td>
							<td class="data"><?php echo $this->_tpl_vars['sPrevHosp']; ?>
</td>
						</tr>
						<tr>
							<td class="label">Remarks:</td>
							<td class="data"><?php echo $this->_tpl_vars['sRemarks']; ?>
</td>
						</tr>
						<tr>
							<td class="label"></td>
							<td class="data"><?php echo $this->_tpl_vars['sPrintBtnPE'];  echo $this->_tpl_vars['sSaveBtnPE'];  echo $this->_tpl_vars['sCancelBtnPE']; ?>
</td>
						</tr>
					</tbody>
				</table>
			</div>
	</div>
</div>
</div>