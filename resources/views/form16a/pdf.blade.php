<!DOCTYPE html>
<html lang="en">
<head>
	<title>AHSEC</title> <!-- Fonts -->
	<style>
		@page{ margin-top: 10px; margin-bottom: 0px; }
		body { font-family: 'Book Antiqua';font-size: 0.70em !important; }
		ol { font-family: 'Book Antiqua';font-size: 0.68em !important; }
		.table-small-font { font-family: 'Book Antiqua';font-size: 0.68em !important; }
		hr{ border-top: #000; } .data{ font-weight: bold; }
		.datas{ margin-left: 20px; }
		.table { border: 1px solid #000 !important; width: 100%;border-collapse: collapse;}
		.table td{ border: 1px solid #000 !important;}
		.table th{ border: 1px solid #000 !important;}
		.center{text-align: center;}
		.cellpadding{padding: 7px;}
	</style>
</head>
<body>
	<table class="table" align="center">
		<tbody>
			<tr>
				<td class="center" colspan="6">FORM No. 16</td>
			</tr>
			<tr>
				<td class="center" colspan="6">[See rule 31(1)(a)]</td>
			</tr>
			<tr>
				<td class="center" colspan="6">PART A</td>
			</tr>
			<tr>
				<td class="center" colspan="6">Certificate under Section 203 of the Income-tax Act,1961 for tax deduct at source on salary</td>
			</tr>
			<tr>
				<td class="center" colspan="3">Certificate No. {{$emp->id}}</td>
				<td class="center" colspan="3">Last updated on {{$emp->updated_at->format('d-M-Y')}}</td>
			</tr>
			<tr>
				<td class="center" colspan="3">Name and address of the Employer</td>
				<td class="center" colspan="3">Name and address of the Employee</td>
			</tr>
			<tr>
				<td class="cellpadding" colspan="3">ASSAM HIGHER SECONDARY EDUCATION COUNCIL GUWAHATI, BAMUNIMAIDAM,<br>GUWAHATI-781021</td>
				<td class="cellpadding" colspan="3">{{$emp_name}}<br>ASSAM HIGHER SECONDARY EDUCATION COUNCIL GUWAHATI, BAMUNIMAIDAM,<br>GUWAHATI-781021</td>
			</tr>
			<tr>
				<td class="center">PAN of the Deductor</td>
				<td class="center">TAN of the Deductor</td>
				<td class="center" colspan="2">PAN of the Employee</td>
				<td class="center" colspan="2">Employee Reference No. provided by Employeer (if available)</td>
			</tr>
			<tr>
				<td class="center">AAAGA0351B</td>
				<td class="center">SHLA0057B</td>
				<td class="center" colspan="2">{{$pan_no}}</td>
				<td class="center" colspan="2">{{$emp_id}}</td>
			</tr>
			<tr>
				<td class="center" colspan="3">CIT(TDS)</td>
				<td class="center">Assessment Year</td>
				<td class="center" colspan="2">Period with Employee</td>
			</tr>
			<tr>
				<td class="center" colspan="3">The Commissioner of Income Tax(TDS) Saikia Commercial Complex,Shillong Road GUwahati-731005</td>
				<td class="center">{{$year[1]}}-{{$year[1]+1}}</td>
				<td class="center">From<hr>01-Apr-{{$year[0]}}</td>
				<td class="center">To<hr>31-Mar-{{$year[1]}}</td>
			</tr>
			<tr>
				<td class="center" colspan="6">Summary of amount paid/credited and tax deducted at source thereon in respect of the employee</td>
			</tr>
			<tr>
				<td class="center">Quarter(s)</td>
				<td class="center">Receipt Number of original quarterly statements of TDS under sub-section (3) of Section 200</td>
				<td class="center">Amount paid/credited</td>
				<td class="center">Amount of tax deducted (Rs.)</td>
				<td class="center" colspan="2">Amount of tax deposited/remitted (Rs.)</td>
			</tr>
			<tr>
				<td class="center">Q4</td>
				<td class="center">{{$emp->receipt_no}}</td>
				<td class="center">{{$emp->gross}}</td>
				<td class="center">{{$emp->tax_deduct}}</td>
				<td class="center" colspan="2">{{$emp->tax_deduct}}</td>
			</tr>
			<tr>
				<td class="center">Total (Rs.)</td>
				<td class="center"></td>
				<td class="center">{{$emp->gross}}</td>
				<td class="center">{{$emp->tax_deduct}}</td>
				<td class="center" colspan="2">{{$emp->tax_deduct}}</td>
			</tr>
			<tr>
				<td class="center" colspan="6">I. DETAILS OF TAX DEDUCTED AND DEPOSITED IN THE CENTRAL GOVERNMENT ACCOUNT THROUGH BOOK ADJUSTMENT<br>(The deductor to provide payment wise details of tax deducted and deposited with respect to the deductee)</td>
			</tr>
			<tr>
				<td class="center" rowspan="2">Sl. No.</td>
				<td class="center" rowspan="2">Tax Deposited in respect of the deductee(Rs)</td>
				<td class="center" colspan="4">Book Identification Number(BIN)</td>
			</tr>
			<tr>
				<td>Receipt Number of From No. 24G</td>
				<td>DDO serial number in Form no.</td>
				<td>Date of transfer voucher(dd/mm/yyy)</td>
				<td>Status of matching with Form no.24G</td>
			</tr>
			<tr>
				<td class="center">Total(Rs.)</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td class="center" colspan="6">II. DETAILS OF TAX DEDUCTED AND DEPOSITED IN THE CENTRAL GOVERNMENT ACCOUNT THROUGH CHALLAN<br>(The deductor to provide payment wise details of tax deducted and deposited with respect to the deductee)</td>
			</tr>
			<tr>
				<td class="center" rowspan="2">Sl. No.</td>
				<td class="center" rowspan="2">tax Deposited in respect of the deductee(Rs.)</td>
				<td class="center" colspan="4">Challan Identification Number(CIN)</td>
			</tr>
			<tr>
				<td class="center">BSR Code of the Bank Branch</td>
				<td class="center">Date on which Tax deposited(dd/mm/yyy)</td>
				<td class="center">Challan Serial Number</td>
				<td class="center">Status of matching with OLTAS*</td>
			</tr>
			<tr>
				<td class="center">1</td>
				<td class="center">{{$emp->tax_deduct}}</td>
				<td class="center"></td>
				<td class="center"></td>
				<td class="center"></td>
				<td class="center">F</td>
			</tr>
			<tr>
				<td class="center">Total(Rs.)</td>
				<td class="center">{{$emp->tax_deduct}}</td>
				<td colspan="4"></td>
			</tr>
			<tr>
				<td class="center" colspan="6">Verification</td>
			</tr>
			<tr>
				<td class="cellpadding" colspan="6">I, {{$secretary_details->emp_f_name}} {{$secretary_details->emp_m_name}} {{$secretary_details->emp_l_name}}, <!-- son/daughter of Lankeswar Gogoi --> working in the capacity of {{$secretary_post_details->fld_PostName}} (designation) do hereby certify that a sum of Rs. 18220.00|Rs. Eighteen Thousand Two Hundred and Twenty Only(in words)| has been deducted and a sum of Rs. 18220.00|Rs. Eighteen Thousand Two Hundred and Twenty Only| has been deposited to the credit of the Central Government. I further certify that the information given above is true, complete and correct and is based on the books of account, documents, TDS statement, TDS deposited and other available records.</td>
			</tr>
			<tr>
				<td class="cellpadding">Place</td>
				<td class="center">GUWAHATI</td>
				<td rowspan="2" colspan="4" valign="bottom" class="center"><small>(Signature of person responsible for deduction of Tax)</small></td>
			</tr>
			<tr>
				<td class="cellpadding">Date</td>
				<td class="center">{{date('d-M-Y')}}</td>
			</tr>
			<tr>
				<td colspan="3" class="cellpadding">Designation: {{$secretary_post_details->fld_PostName}}</td>
				<td colspan="3" class="cellpadding">Full Name: {{$secretary_details->emp_f_name}} {{$secretary_details->emp_m_name}} {{$secretary_details->emp_l_name}}</td>
			</tr>
		</tbody>
	</table>
	<br>
	<section>
		<b>Notes:</b>
		<ol>
			<li>Part B(Annexure) of the certificate in Form No. 16 shall be issued by the employer.</li>
			<li>If an assessee is employed under one employer during the yer, Part 'A' of the certificate in Form No. 16 issued for the quater ending on 31st March of the financial year shall contain hte details of tax deducted and deposited for all the financial year.</li>
			<li>If an assessee is employed under more than one employer during the year, each of the employers shall issue part A of the certificate in Form No. 16 pertaining to the period for which such assessee was employeed with each of the employers. Part B (Annexure) of the certificate in Form 16 may be issued by each of the employers or the last employer at the option of the assessee.</li>
			<li>To update PAN details in  Income Tax Department database, apply for 'PAN change request' through NSDL or UTITSL.</li>
		</ol>
	</section>
	<br>
	<b>Legend used in Form 16</b>
	*Status of matching with OLTAS
	<table class="table table-small-font" align="center">
		<thead>
			<tr>
				<th>Legend</th>
				<th>Description</th>
				<th>Definition</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>U</td>
				<td>Unmatched</td>
				<td>Deductors have not deposited taxes or have furnished incorrect particulars of tax payment. Final credit will be reflected only when payment details in bank match with details of deposit in TDS/TCS statement</td>
			</tr>
			<tr>
				<td>P</td>
				<td>Provisional</td>
				<td>Provisional tax credit is effected only for TDS/TCS Statement filed by Government deductors. "P" status will be changed to Final (F) on verification of payment details submitted by Pay and Accounts Officer(PAO)</td>
			</tr>
			<tr>
				<td>F</td>
				<td>Final</td>
				<td>In case of non-government deducors, payment details of TDS/TCS deposited in bank by deductor have matched with the payment details mentioned in the TDS/TCS statement filed by the deductors. In case of government deductors, details of TDS/TCS booked in Goventment account have been verified by Py & Accounts Officer(PAO)</td>
			</tr>
			<tr>
				<td>O</td>
				<td>Overbooked</td>
				<td>Payment details of TDS/TCS deposited in bank by deductor have matched with details mentioned in TDS/TCS statement but the amount is over claimed in the statement. Final(F) credit will be reflected  only when deducor reduces claimed amount in the statement or makes new payment for excess amount claimed in the statement</td>
			</tr>
		</tbody>
	</table>
</body>
</html>
