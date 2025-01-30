<!DOCTYPE html>
<html lang="en">
<head>
	<title>AHSEC</title> <!-- Fonts -->
	<style>
		@page{ margin-top: 10px; margin-bottom: 0px; }
		body { font-family: 'Book Antiqua';font-size: 0.7em !important; }
		hr{ border-top: #000; }
		.data{ font-weight: bold; }
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
				<td class="center" colspan="5">FORM No. 16</td>
			</tr>
			<tr>
				<td class="center" colspan="5">Certificate under section 203 of the Income-tax Act, 1961 for tax deducted at source on Salary</td>
			</tr>
			<tr>
				<td class="center" colspan="2">Name and address of the Employer</td>
				<td class="center" colspan="3">Name and address of the Employee</td>
			</tr>
			<tr>
				<td class="center" colspan="2">ASSAM HIGHER SECONDARY EDUCATION COUNCIL<br><small>Bamunimaidam, Guwahati-781021</small></td>
				<td class="center" colspan="3">{{$emp_name}}<br><small>{{$post_name}}</small></td>
			</tr>
			<tr>
				<td class="center">PAN of Deductor</td>
				<td class="center">TAN of the Deductor</td>
				<td class="center" colspan="3">PAN of the Employee</td>
			</tr>
			<tr>
				<td class="center">PANNOTREQD</td>
				<td class="center">SHLA00575B</td>
				<td class="center" colspan="3">{{$pan_no}}</td>
			</tr>
			<tr>
				<td class="center" colspan="2">CIT(TDS)</td>
				<td class="center" rowspan="2">Assessment Year</td>
				<td class="center" rowspan="2" colspan="2">Period</td>
			</tr>
			<tr>
				<td rowspan="2">Address</td>
				<td>Aayakar Bhawan</td>
			</tr>
			<tr>
				<td>G.S. Road, Christianbasti</td>
				<td class="center" rowspan="2">{{$year[1]}}-{{$year[1]+1}}</td>
				<td>From</td>
				<td>To</td>
			</tr>
			<tr>
				<td>City: Guwahati</td>
				<td>Pin Code: 781005</td>
				<td>01/04/{{$year[0]}}</td>
				<td>31/03/{{$year[1]}}</td>
			</tr>
		</tbody>
	</table>
	<div class="center">
		<h4>PART B (Annexure)</h4>
	</div>
	<table class="table" align="center">
		<thead>
			<tr>
				<th colspan="3">Details of Salary paid and any other income and tax deduct</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="3">1. Gross Salary</td>
			</tr>
			<tr>
				<td class="cellpadding">(a) Salary as per provisions contained in sec. 17(1)</td>
				<td class="cellpadding">Rs. {{$emp->basic1}}</td>
				<td></td>
			</tr>
			<tr>
				<td class="cellpadding">(b) Value of perquisites u/s 17(2) (as per Form No. 12BA, wherever applicable)</td>
				<td class="cellpadding">Rs. {{$emp->basic2}}</td>
				<td></td>
			</tr>
			<tr>
				<td class="cellpadding">(c) Profits in lieu of salary under section 17(3) (as per Form No. 12BA, wherever applicable</td>
				<td class="cellpadding">Rs. {{$emp->basic3}}</td>
				<td></td>
			</tr>
			<tr>
				<td class="cellpadding">(d) Total</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->gross}}</td>
			</tr>
			<tr>
				<td colspan="3">2. Less:allowance to the extent exempt u/s 10</td>
			</tr>
			<tr>
				<td class="cellpadding">
					<table class="table">
						<tr>
							<td class="center">Allowance</td>
							<td class="center">Rs.</td>
						</tr>
						<tr>
							<td>HRA</td>
							<td>Rs. {{$emp->hra}}</td>
						</tr>
						<tr>
							<td>Conveyance</td>
							<td>Rs. {{$emp->conveyance}}</td>
						</tr>
						<tr>
							<td>Charge Allowance</td>
							<td>Rs. {{$emp->charge_allowance}}</td>
						</tr>
						<tr>
							<td>Other Allowance</td>
							<td>Rs. {{$emp->other}}</td>
						</tr>
					</table>
				</td>
				<td class="cellpadding">Rs. {{$emp->total_less}}</td>
				<td></td>
			</tr>
			<tr>
				<td>3. Balance (1-2)</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->balance}}</td>
			</tr>
			<tr>
				<td colspan="3">4. Deductions</td>
			</tr>
			<tr>
				<td class="cellpadding">(a) Entertainment allowance</td>
				<td class="cellpadding">{{$emp->entertainment_allowance}}</td>
				<td></td>
			</tr>
			<tr>
				<td class="cellpadding">(b) Tax on employment</td>
				<td class="cellpadding">{{$emp->professional_tax}}</td>
				<td></td>
			</tr>
			<tr>
				<td>5. Aggregate of 4(a) and 4(b)</td>
				<td class="cellpadding">{{$emp->aggregate}}</td>
				<td></td>
			</tr>
			<tr>
				<td>6. Income chargeable under the head 'Salaries' (3-5)</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->income_chargeable}}</td>
			</tr>
			<tr>
				<td colspan="3">7. Add: Any other income reported by employee</td>
			</tr>
			<tr>
				<td class="cellpadding">
					<table class="table">
						<tr>
							<td class="center">Income</td>
							<td class="center">Rs.</td>
						</tr>
						<tr>
							<td>Interest on SB A/c</td>
							<td>Rs. {{$emp->sb_interest}}</td>
						</tr>
						<tr>
							<td>HBL interest</td>
							<td>Rs. {{$emp->hbl_interest}}</td>
						</tr>
					</table>
				</td>
				<td></td>
				<td class="cellpadding">Rs {{$emp->total_other_income}}</td>
			</tr>
			<tr>
				<td>8. Gross total income (6+7)</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->gross_total_income}}</td>
			</tr>
			<tr>
				<td>9. Deductions under Chapter VIA</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td class="cellpadding">(A) Sections 80C,80CCC and 80CCD</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td class="cellpadding">(a) Sections 80C</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td class="cellpadding">
					<table class="table">
						<tr>
							<td>(i) GPF/EPF/CPF</td>
							<td>Rs. {{$emp->gpf_epf_cpf}}</td>
						</tr>
						<tr>
							<td>(ii) G.I.S</td>
							<td>Rs. {{$emp->gis}}</td>
						</tr>
						<tr>
							<td>(iii) Life Insurance Premium</td>
							<td>Rs. {{$emp->life_insurance_premium}}</td>
						</tr>
						<tr>
							<td>(iv) Public Provident Fund</td>
							<td>Rs. {{$emp->public_provident_fund}}</td>
						</tr>
						<tr>
							<td>(v) Repayment of HBL(principal)</td>
							<td>Rs. {{$emp->repayment_of_hbl_principal}}</td>
						</tr>
						<tr>
							<td>(vi) Tuition Fee of Children (Max 2 children)</td>
							<td>Rs. {{$emp->tuition_fee_of_children}}</td>
						</tr>
						<tr>
							<td>(vii) Other</td>
							<td>Rs. {{$emp->other_deduction}}</td>
						</tr>
					</table>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td class="cellpadding">(b) Section 80CCC</td>
				<td>{{$emp->section_80ccc}}</td>
				<td></td>
			</tr>
			<tr>
				<td class="cellpadding">(c) Section 80CCD</td>
				<td>{{$emp->section_80ccd}}</td>
				<td></td>
			</tr>
			<tr>
				<td>Aggregate amount deductible under the three sections, i.e., 80C, 80CCC and 80CCD<br><b>Note:</b> <i>Aggregate amount deductible under section 80C shall not exceed 1.5 lakh rupees.</i></td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->total_deduction_9}}</td>
			</tr>
			<tr>
				<td class="cellpadding">(B) Other sections (e.g. 80E, 80G, 80TTA, etc.) under Chapter VI-A</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td class="cellpadding">
					<table class="table">
						<tr>
							<td>Medical Insurance Premium(Sec. 80D)</td>
							<td>Rs. {{$emp->medical_insurance_premium}}</td>
						</tr>
						<tr>
							<td>Medical treatment of handicapped dependent(Sec. 80DD)</td>
							<td>Rs. {{$emp->medical_treatment_of_handicapped}}</td>
						</tr>
						<tr>
							<td>Interest on Higher Education Loan(Sec. 80E)</td>
							<td>Rs. {{$emp->interest_on_higher_edu_loan}}</td>
						</tr>
						<tr>
							<td>Donations(Sec. 80G)</td>
							<td>Rs. {{$emp->donations}}</td>
						</tr>
						<tr>
							<td>Interest Income from SB A/c(max Rs 10,000)(Sec. 80TTA)</td>
							<td>Rs. {{$emp->interest_on_sb_ac_deducation}}</td>
						</tr>
					</table>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>10. Aggregate of deductible amount under Chapter VIA</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->aggregate_of_deductible_amount}}</td>
			</tr>
			<tr>
				<td>11. Total Income (8-10)</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->total_income}}</td>
			</tr>
			<tr>
				<td>12. Tax on Total Income</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->tax_on_total_income}}</td>
			</tr>
			<tr>
				<td>13. Rebate U/s 87A(max Rs. 2000, whose Taxable Income below 5.00 lacs)</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->rebate}}</td>
			</tr>
			<tr>
				<td>14. Balance Tax Payable</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->bal_tax_payable}}</td>
			</tr>
			<tr>
				<td>15. Education cess(on tax computed at S.No. 14)</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->edu_cess}}</td>
			</tr>
			<tr>
				<td>16. Tax Payable(14+15)</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->tax_payable}}</td>
			</tr>
			<tr>
				<td>17. Less: Relief under section 89(attach details)</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->less_relief}}</td>
			</tr>
			<tr>
				<td>18. Tax payable(16-17)</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->tax_payable18}}</td>
			</tr>
			<tr>
				<td>19. Tax deducted at source u/s 192</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->tax_deduct}}</td>
			</tr>
			<tr>
				<td>20. Balance(18-19)</td>
				<td></td>
				<td class="cellpadding">Rs. {{$emp->balance_20}}</td>
			</tr>
			<tr>
				<td class="center" colspan="3">Verification</td>
			</tr>
			<tr>
				<td class="cellpadding" colspan="3">I, {{$secretary_details->emp_f_name}} {{$secretary_details->emp_m_name}} {{$secretary_details->emp_l_name}} <!-- son/daughter of Kula Ram Das --> working in the capacity of {{$secretary_post_details->fld_PostName}}(designation) do hereby certify that a sum of Rs. {{$emp->tax_deduct}} has been deducted and deposited to the credit of the Central Government. I further certify that the information given above is true, complete and correct and based on th books of account, documents, TDS statements, TDS deposited and other available records.</td>
			</tr>
			<tr>
				<td>Place:  Guwahati</td>
				<td colspan="2" rowspan="2" valign="bottom" class="center">
					<small>(Signature of person responsible for deduct of tax)</small>
				</td>
			</tr>
			<tr>
				<td>Date: {{date('d-m-Y')}}</td>
			</tr>
			<tr>
				<td>Designation: {{$secretary_post_details->fld_PostName}}</td>
				<td colspan="2">Fullname: {{$secretary_details->emp_f_name}} {{$secretary_details->emp_m_name}} {{$secretary_details->emp_l_name}}</td>
			</tr>
		</tbody>
	</table>
</body>
</html>
