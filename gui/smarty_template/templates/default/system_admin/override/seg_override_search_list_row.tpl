{{* reg_search_list_row.tpl  *}}
{{* This is the row for the resulting list of the person/patient search module *}}
{{* If you rearrange the row columns, be sure to synchronize it with the title row at reg_search_main.tpl *}}

<tr  {{if $toggle}} class="wardlistrow2" {{else}} class="wardlistrow1" {{/if}}>
	<td>&nbsp;{{$sPID}}</td>
	<td>&nbsp;{{$sPersonnelNr}}</td>
	<td>&nbsp;{{$sCaseNr}} {{$sOutpatientIcon}} <font size=1 color="red">{{$LDAmbulant}}</font></td>

	<td>&nbsp;{{$sSex}}</td>
	<td>&nbsp;{{$sAge}}</td>
	<td>&nbsp;{{$sLastName}}</td>
	<td>&nbsp;{{$sFirstName}} {{$sCrossIcon}}</td>
	<td>&nbsp;{{$sMiddleName}}</td>

	<td>&nbsp;{{$sJobPosition}}</td>

	<td>&nbsp;{{$sAdmissionDate}}</td>
	<td>&nbsp;{{$sDepartment}}</td>

	<td align="center">&nbsp;{{$sOptions}} {{$sHiddenBarcode}}</td>
</tr>
