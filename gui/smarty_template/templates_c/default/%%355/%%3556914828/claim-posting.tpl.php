<?php /* Smarty version 2.6.0, created on 2017-01-12 12:11:26
         compiled from billing/claim-posting.tpl */ ?>
<div align="center" style="font:bold 12px Tahoma; color:#990000; "><?php echo $this->_tpl_vars['sWarning']; ?>
</div><br>
<?php echo $this->_tpl_vars['sFormStart']; ?>

<div id="mainTablediv" align="center">
        <table width="98%" cellpadding="2" cellspacing="2" id="mainTable" style="border-collapse:collapse; border:1px solid #a6b4c9; color:black">
        <tbody>
            <tr>
                <td width="88%" class="jedPanelHeader">
                BENEFITS CLAIM 
                </td>
            </tr>
            <!-- Basic information -->
            <tr>
                <td rowspan="5" align="left" valign="top" class="jedPanel">
                    <table width="100%" border="0" cellpadding="2" cellspacing="0" style="font-size:11px"> 
                        <tr class="jedPanel">                            
                            <td align="right" valign="middle"><strong>Insurance:</strong></td>
                            <td width="38%" valign="middle">
                                <?php echo $this->_tpl_vars['sHCareDesc']; ?>
</td>
                            <td width="10%" align="left" valign="middle">
                                <?php echo $this->_tpl_vars['sSelectHCare']; ?>
</td>
                            <td align="right" valign="middle"><strong>Date:</strong></td>
                            <td valign="middle" align="left"><?php echo $this->_tpl_vars['sDate'];  echo $this->_tpl_vars['sCalendarIcon']; ?>
</td>                               
                        </tr>
                        <tr class="jedPanel">
                            <td  width="67" align="right" valign="top"><strong>Address:</strong></td>
                            <td><?php echo $this->_tpl_vars['sHCareAddress']; ?>
</td>
                        </tr>
                        <tr class="jedPanel">
                            <td align="right" valign="middle"><strong>Ref No.:</strong></td>
                            <td valign="middle" align="left"><?php echo $this->_tpl_vars['sRefNo']; ?>
&nbsp;<?php echo $this->_tpl_vars['sResetRefNo']; ?>
</td>
<!--                            <td width="10%" align="left" valign="middle">&nbsp;</td> -->
                            <td align="right" valign="middle"><strong>Membership:</strong></td>
                            <td colspan="2" valign="middle" align="left"><?php echo $this->_tpl_vars['sCategory']; ?>
</td>                             
                        </tr>
                        <tr class="jedPanel">                            
                            <td align="right" valign="middle"><strong>APV No.:</strong></td>                 
                            <td width="38%" valign="middle">
                                <?php echo $this->_tpl_vars['sAPVNo']; ?>
</td>
<!--                            <td width="10%" align="left" valign="middle">&nbsp;</td>-->
                            <td align="right" valign="middle"><strong>APV Date:</strong></td>
                            <td colspan="2" valign="middle" align="left"><?php echo $this->_tpl_vars['sAPVDate'];  echo $this->_tpl_vars['sAPVCalendarIcon']; ?>
</td>                               
                        </tr> 
                        <tr class="jedPanel">                            
                            <td align="right" valign="middle"><strong>Check No.:</strong></td>                 
                            <td width="38%" valign="middle">
                                <?php echo $this->_tpl_vars['sCheckNo']; ?>
</td>
                            <td align="right" valign="middle"></td>
                            <td colspan="2" valign="middle" align="left"></td>                               
                        </tr>                               
                    </table>            
                </td>    
            </tr>
        </tbody>
  </table>
    <table width="98%">
        <tbody id="tbl_claim_details_header">
            <tr id="tbl_claim_details_hdr_row1" <?php echo $this->_tpl_vars['sNoShowButtons']; ?>
>
                <td align="left" colspan="7">&nbsp;</td>
            </tr>
            <tr id="tbl_claim_details_hdr_row2" <?php echo $this->_tpl_vars['sShowButtons']; ?>
>
                <td align="left" colspan="4"><?php echo $this->_tpl_vars['sBtnAddItem'];  echo $this->_tpl_vars['sBtnDelete']; ?>
</td>
                <td align="right"><strong>Status: </strong><?php echo $this->_tpl_vars['sBtnStatusOpt']; ?>
</td>
                <td align="left"><?php echo $this->_tpl_vars['sBtnPrintStatus']; ?>
</td>
                <td align="right"><?php echo $this->_tpl_vars['sBtnSave']; ?>
</td>
            </tr>            
        </tbody>    
    </table>
    <table id="claim_details" class="segList" border="0" cellpadding="0" cellspacing="0" width="98%">
        <thead id="tbl_claim_details_hdr">
            <tr>                
                <th colspan="2" width="17.5%" align="left">Patient</th>
                <th width="10%">Status</th>
                <th colspan="2" width="25%">Confinement</th>
                <th colspan="2" width="12%">Insurance<br>No.</th>
                <th colspan="2" width="15%">Member</th>
                <th colspan="2" width="12%">Classification</th>
                <th colspan="2" width="12%">Options</th> 
                <th colspan="2" width="6%"></th>                            
            </tr>
        </thead>     
        <tbody id="tbl_claim_details_body">
<?php echo $this->_tpl_vars['sClaims']; ?>
        
        </tbody>
        <thead id="tbl_claim_footer">
            <tr>
                <th colspan="2" width="17.5%" align="center">T O T A L</th>
                <th width="7.5%" align="right"><span style="display:none" id="total_acc">0.00</span></th>
                <th width="7.5%" align="right"><span style="display:none" id="total_med">0.00</span></th>
                <th width="7.5%" align="right"><span style="display:none" id="total_srv">0.00</span></th>
                <th width="7.5%" align="right"><span style="display:none" id="total_ops">0.00</span></th>
                <th width="7.5%" align="right"><span style="display:none" id="total_msc">0.00</span></th>
                <th width="7.5%" align="right"><span style="display:none" id="total_d1">0.00</span></th>
                <th width="7.5%" align="right"><span style="display:none" id="total_d2">0.00</span></th>
                <th width="7.5%" align="right"><span style="display:none" id="total_d3">0.00</span></th>
                <th width="7.5%" align="right"><span style="display:none" id="total_d4">0.00</span></th>
                <th width="10%" align="right"><span id="total_gross">0.00</span></th>
                <th width="10%" align="right"><span id="total_twheld">0.00</span></th>
                <th width="10%" align="right"><span id="total_net">0.00</span></th> 
            </tr>        
        </thead>
    </table>  
    <br />
    <br />
</div>

<?php echo $this->_tpl_vars['jsCalendarSetup']; ?>

<?php echo $this->_tpl_vars['jsAPVCalendarSetup']; ?>

<?php echo $this->_tpl_vars['sHiddenItems']; ?>


<span style="font:bold 15px Arial"><?php echo $this->_tpl_vars['sDebug']; ?>
</span>
<?php echo $this->_tpl_vars['sFormEnd']; ?>

<?php echo $this->_tpl_vars['sTailScripts']; ?>
