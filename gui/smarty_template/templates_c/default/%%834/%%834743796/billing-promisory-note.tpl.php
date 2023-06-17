<?php /* Smarty version 2.6.0, created on 2017-01-23 15:54:39
         compiled from billing_new/billing-promisory-note.tpl */ ?>
<div id="loadingBox" style="display:none;" align="center">
    <strong>Please wait ...</strong><br>
    <img id="imgLoading" src="../../images/ajax_bar.gif" />
</div>
<div align="center" style="font:bold 12px Tahoma; color:#990000; "><?php echo $this->_tpl_vars['sWarning']; ?>
</div><br />
<div id="mainTablediv" align="center">
        <table width="50%" cellpadding="2" cellspacing="2" id="mainTable" style="border-collapse:collapse; border:1px solid #a6b4c9; color:black">
            <thead>
                <tr>
                    <th id="billcol_01" colspan="2" rowspan="2" align="left" class="jedPanelHeader" style="border-right:none">PROMISORY NOTE&nbsp;&nbsp;</th>
                    <th id="billcol_02" colspan="2" rowspan="2" align="center" class="jedPanelHeader" style="border-right:none;border-left:none"><span id="remaindays" style="display:none"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="coverdays" style="display:none"></span><span id="savethis" style="display:none"></span></th>
                   <th class="jedPanelHeader" style="border-left:none" align="right"><div id="categ_col" style="display:none"><?php echo $this->_tpl_vars['sMembershipCategory']; ?>
&nbsp;MEMBERSHIP CATEGORY:&nbsp;&nbsp;&nbsp;<span id="mcategdesc" name="mcategdesc"></span></div></th>
                </tr>
            </thead>
        </table>
        <table width="50%" cellpadding="2" cellspacing="2" id="mainTable2" style="border-collapse:collapse; border:1px solid #a6b4c9; color:black">
            <tbody>
                <!-- Basic information -->
                <tr>
                    <td colspan="2" rowspan="5" align="left" valign="top" class="jedPanel">
                        <table width="100%" border="0" cellpadding="2" cellspacing="0" style="font-size:11px">
                            <tr class="jedPanel">
                                    <td width="5%" align="right"><strong>HRN:</strong></td>
                                    <td width="50px" align="left"><?php echo $this->_tpl_vars['sPid']; ?>
</td>
                                    <td colspan="2" width="14%" align="right"><strong>Case No:</strong></td>
                                    <td width="25%" align="left" valign="middle"><?php echo $this->_tpl_vars['sEncounter']; ?>
</td>
                            </tr>
                            <tr class="jedPanel">
                                    <td align="right" valign="middle"><strong>Name:</strong></td>
                                    <td colspan="2" width="50px" valign="middle">
                                        <?php echo $this->_tpl_vars['sPatientName']; ?>

                                        <span style="vertical-align:bottom"><?php echo $this->_tpl_vars['sSelectPatient']; ?>
</span>
                                    </td>
                                    <td width="10%" align="right" valign="middle"><strong>Case Date:</strong></td>
                                    <td colspan="2" width="20%" valign="middle" align="left"><?php echo $this->_tpl_vars['sAdmissionDate']; ?>
</td>
                            </tr>
                            <tr class="jedPanel">
                                    <td width="*" align="right" valign="top"><strong>Address:</strong></td>
                                    <td rowspan="2" width="50px"><?php echo $this->_tpl_vars['sPatientAddress']; ?>
</td>
                                    <td width="50px" align="left">&nbsp;</td>
                                    <td width="20%" align="right" valign="middle"><strong>Confinement Type:</strong></td>
                                    <td colspan="2" width="20%" valign="middle" align="left"><?php echo $this->_tpl_vars['sConfineType']; ?>
</td>
                            </tr>

                            <tr class="jedPanel">
                                <td width="*" align="right">&nbsp;</td>
                                <td width="50px" align="left">&nbsp;</td>
                                <td width="20%" align="right" valign="middle"><strong>Billing Date:</strong></td>
                                <td colspan="2" width="20%" valign="middle" align="left"><?php echo $this->_tpl_vars['sDate']; ?>
</td>
                            </tr>

                            <tr class="jedPanel">
                                <td width="*" align="right"><strong>Age :</strong></td>
                                <td width="50px" align="left"><?php echo $this->_tpl_vars['sAge']; ?>
</td>
                                <td align="right" width="20%" colspan="2" valign="top"><strong>Total Bill:</strong></td>
                                <td valign="top" colspan="2"><?php echo $this->_tpl_vars['sTotalBill']; ?>
</td>
                            </tr>
                         </table>
                    </td>
                </tr>
            </tbody>
    </table>
     <table width="50%" cellpadding="2" cellspacing="2" id="mainTable" style="border-collapse:collapse; border:1px solid #a6b4c9; color:black; margin-top: 10px;">
          <tbody>
                <tr>
                   <td colspan="2" rowspan="5" align="left" valign="top" class="jedPanel">
                        <table width="100%" border="0" cellpadding="2" cellspacing="0" style="font-size:11px">
                            <tr class="jedPanel">
                                <td width="200px" align="left">&nbsp;</td>
                                <td width="10%" align="right"><strong>Due Date: </strong></td>
                                <td><?php echo $this->_tpl_vars['sDueDate'];  echo $this->_tpl_vars['sCalendarIcon']; ?>
</td>
                            </tr>
                             <tr class="jedPanel">
                                <td width="200px" align="left">&nbsp;</td>
                                <td width="20%" align="right"><strong>Type of Payment: </strong></td>
                                <td><?php echo $this->_tpl_vars['sTypeofPayment']; ?>
</td>
                            </tr>
                            <tr class="jedPanel">
                                <td width="200px" align="left">&nbsp;</td>
                                <td width="20%" align="right"><strong>Amount Payable: </strong></td>
                                <td><?php echo $this->_tpl_vars['sAmount']; ?>
</td>
                            </tr>
                             <tr class="jedPanel">
                                <td width="200px" align="left">&nbsp;</td>
                                <td width="20%" align="right"><strong>Remarks: </strong></td>
                                <td><?php echo $this->_tpl_vars['sRemarks']; ?>
</td>
                            </tr>
                            <tr class="jedPanel">
                               <td width="200px" align="left">&nbsp;</td>
                                <td width="20%" align="right">&nbsp;</td>
                                <td align="left"><?php echo $this->_tpl_vars['sBtnSave'];  echo $this->_tpl_vars['sBtnPrint']; ?>
</td>
                            </tr>
                        </table>
                    </td>
                </tr>
          </tbody>
        </table>
</div>

<span style="font:bold 15px Arial"><?php echo $this->_tpl_vars['sDebug']; ?>
</span>
<?php echo $this->_tpl_vars['sFormEnd']; ?>

<?php echo $this->_tpl_vars['sTailScripts']; ?>

<?php echo $this->_tpl_vars['sSaveinputs']; ?>
