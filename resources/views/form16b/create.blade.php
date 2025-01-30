@extends('layouts.app')
@section('content')
    <div class="single-pro-review-area mt-t-30 mg-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="breadcome-heading">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <ul class="breadcome-menu">
                                <li><a href="#">Dashboard</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Process Policy</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            <div class="card">
                                <div class="card-header">
                                    Form 16b for {{$employee->name}} for the financial year {{$financialYear}}

                                </div>
                                <div class="card-body">
                                    <form action="{{ route('form16.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" class="form-control" id="emp_code" name="emp_code" value="{{$employee->emp_code}}">
                                        <input type="hidden" class="form-control" id="financial_year" name="financial_year" value="{{$financialYear}}">
                                        <div class="form-group">
                                            <label>1. Gross Salary</label>
                                            <div class="col-md-offset-1">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>(a) Salary as per provisions contained in sec. 17(1)</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Basic" name="basic1" id="basic1" data-validation="number" data-validation-error-msg="Numeric Value Only" value="{{$totalGrossSalary}}" onkeyup="change()" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>(b) Value of perquisites u/s 17(2) ( as per Form No. 12BA, wherever applicable)</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Value of perquisites u/s 17(2)" name="basic2" id="basic2" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>(c) Profits in lieu of salary under section 17(3)(as per Form No. 12BA, wherever applicable</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Profits in lieu of salary under section 17(3)" name="basic3" id="basic3" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>(d) Total</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Gross" name="gross" id="gross" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback">
                                            <label>2. Less: Allowance to the extent exempt u/s 10</label>
                                            <div class="col-md-offset-1">
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>HRA</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="HRA" name="hra" id="hra" data-validation="number" data-validation-error-msg="Numeric Value Only" value="{{$totalINC_HRA}}" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Coveyance</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Coveyance" name="conveyance" id="coveyance" data-validation="number" data-validation-error-msg="Numeric Value Only" value="$total_conveyance" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Charge Allowance</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Charge Allowance" name="charge_allowance" id="charge_allowance" data-validation="number" data-validation-error-msg="Numeric Value Only" value="$total_charge" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Other</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Other" name="other" id="other" data-validation="number" data-validation-error-msg="Numeric Value Only" value="{{$totalINC_OTHERALLW}}" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Total</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Other" name="total_less" id="total_less" data-validation="number" data-validation-error-msg="Numeric Value Only" onkeyup="change()" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" />
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>3. Balance(1-2)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Balance" name="balance" id="balance" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" readonly />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback">
                                            <label>4. Deductions</label>
                                            <div class="col-md-offset-1">
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>(b) Entertainment Allowance</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Entertainment Allowance" name="entertainment_allowance" id="entertainment_allowance" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>(b) Professional Tax</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Professional Tax" name="professional_tax" id="professional_tax" data-validation="number" data-validation-error-msg="Numeric Value Only" value="{{$totalDED_PTAX}}" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>5. Aggregate of 4(a) and (b)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Aggregate" name="aggregate" id="aggregate" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>6. Income chargeable under the head'Salaries' (3-5)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Income Chargeable" name="income_chargeable" id="income_chargeable" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label>7. Add: Any other income reported by the employee</label>
                                            <div class="col-md-offset-1">
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Interest on SB A/c</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Interest on SB A/c" name="sb_interest" id="sb_interest" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>HBL Interest</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="HBL Interest" name="hbl_interest" id="hbl_interest" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="total_other_income" id="total_other_income"/>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>8. Gross total income(6+7)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Gross Total Income" name="gross_total_income" id="gross_total_income" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label>9. Deductions under Chapter VI-A</label>
                                            <div class="col-md-offset-1">
                                                <div class="form-group has-feedback">
                                                    <label>(A) Section 80C, 80CCC and 80CCD</label>
                                                    <div class="form-group has-feedback">
                                                        <label>(a) Section 80C</label>
                                                        <div class="col-md-offset-1">
                                                            <div class="form-group has-feedback">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>GPF/EPF/CPF</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="GPF/EPF/CPF" name="gpf_epf_cpf" id="gpf_epf_cpf" data-validation="number" data-validation-error-msg="Numeric Value Only" value="{{$totalGPF_EPF_CPF}}" onkeyup="change()"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group has-feedback">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>G.I.S</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="G.I.S" name="gis" id="gis" data-validation="number" data-validation-error-msg="Numeric Value Only" value="{{$totalDED_GSLI}}" onkeyup="change()"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group has-feedback">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>Life Insurance Premium</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="Life Insurance Premium" name="life_insurance_premium" id="life_insurance_premium" data-validation="number" data-validation-error-msg="Numeric Value Only" value="$salary_saving_deduction" onkeyup="change()"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group has-feedback">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>Public Provident Fund</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="Public Provident Fund" name="public_provident_fund" id="public_provident_fund" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group has-feedback">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>Repayment of HBL(Principal)</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="Repayment of HBL(Principal)" name="repayment_of_hbl_principal" id="repayment_of_hbl_principal" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group has-feedback">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>Tuition Fee of Children(Max 2 children)</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="Tuition Fee of Children(Max 2 children)" name="tuition_fee_of_children" id="tuition_fee_of_children" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group has-feedback">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>Others</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="Other" name="other_deduction" id="other_deduction" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-feedback">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>(b) Section 80CCC</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" placeholder="Section 80CCC" name="section_80ccc" id="section_80ccc" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-feedback">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>(c) Section 80CCD</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" placeholder="Section 80CCD" name="section_80ccd" id="section_80ccd" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-feedback">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Aggregate amount deductible under the three sections, i.e., 80C, 80CCC, 80CCD</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" placeholder="Section 80CCD" name="total_deduction_9" id="total_deduction_9" data-validation="number" data-validation-error-msg="Numeric Value Only" readonly />
                                                                Note: Aggregate amount deductible under section 80C shall not exceed 1.5 lakh rupees.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback">
                                            <label>(B)Other Sections(e.g 80E, 80G, 80TTA, etc.) under Chapter VI-A</label>
                                            <div class="col-md-offset-1">
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>(i) Medical Insurance Premium (Sec 80D)</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Medical Insurance Premium" name="medical_insurance_premium" id="medical_insurance_premium" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>(ii) Medical treatment of handicapped (Sec 80DD)</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Medical treatment of handicapped" name="medical_treatment_of_handicapped" id="medical_treatment_of_handicapped" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>(iii) Interest on Higher Education Loan (Sec 80E)</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Interest on Higher Education Loan" name="interest_on_higher_edu_loan" id="interest_on_higher_edu_loan" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>(iv) Donations (Sec 80G)</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Donations" name="donations" id="donations" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>(i) Interest Income from SB A/c (max Rs. 10,000) (Sec 80TTA)</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" placeholder="Interest Income from SB A/c" name="interest_on_sb_ac_deducation" id="interest_on_sb_ac_deducation" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>10. Aggregate of deductible amount under Chapter VI-A</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Aggregate of deductible amount under Chapter VI-A" name="aggregate_of_deductible_amount" id="aggregate_of_deductible_amount" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" readonly "/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>11. Total Income(8-10)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Total Income" name="total_income" id="total_income" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>12. Tax on total income</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Tax on total income" name="tax_on_total_income" id="tax_on_total_income" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>13. Rebate U/s 87A(max Rs. 2000, whose Taxable Income below 5.00 lacs)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Rebate U/s 87A" name="rebate" id="rebate" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>14. Balance Tax Payable</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Balance Tax Payable" name="bal_tax_payable" id="bal_tax_payable" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>15. Education cess(on tax computed at S.No. 14)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Education cess" name="edu_cess" id="edu_cess" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>16. Tax Payable(14+15)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Tax Payable" name="tax_payable" id="tax_payable" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>17. Less: Relief under section 89(attach details)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Less: Relief under section 89" name="less_relief" id="less_relief" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>18. Tax payable(16-17)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Tax payable" name="tax_payable18" id="tax_payable18" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>19. Tax deducted at source u/s 192</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Tax payable" name="tax_deduct" id="tax_deduct" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" onkeyup="change()" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>20. Balance(18-19)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Balance" name="balance_20" id="balance_20" data-validation="number" data-validation-error-msg="Numeric Value Only" value="0" readonly />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">


                                        </div>

                                        <button type="submit" class="btn btn-primary btn-sm">Update & Confirm</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        console.log('ready');
        change();
    });
    function change() {
        console.log('change');
        var basic1 = $('#basic1').val() || 0;
        var basic2 = $('#basic2').val() || 0;
        var basic3 = $('#basic3').val() || 0;
        var gross = parseInt(basic1)+parseInt(basic2)+parseInt(basic3);
        $('#gross').val(gross);

        var hra = $('#hra').val() || 0;
        var coveyance = $('#coveyance').val() ||0;
        var charge_allowance = $('#charge_allowance').val() || 0;
        var other = $('#other').val() || 0;
        var total_less = parseInt(hra)+parseInt(coveyance)+parseInt(charge_allowance)+parseInt(other);
        $('#total_less').val(total_less);

        var balance = gross-total_less;
        $('#balance').val(balance);

        var entertainment_allowance = $('#entertainment_allowance').val() || 0;
        var professional_tax = $('#professional_tax').val() || 0;
        var aggregate = parseInt(entertainment_allowance)+parseInt(professional_tax);
        $('#aggregate').val(aggregate);

        var income_chargeable = parseInt(balance)-parseInt(aggregate);
        $('#income_chargeable').val(income_chargeable);

        var sb_interest = $('#sb_interest').val() || 0;
        var hbl_interest = $('#hbl_interest').val() || 0;
        var total_other_income = parseInt(sb_interest)+parseInt(hbl_interest);
        $('#total_other_income').val(total_other_income);
        var gross_total_income = parseInt(income_chargeable)+parseInt(sb_interest)+parseInt(hbl_interest);
        $('#gross_total_income').val(gross_total_income);
        var total_deduction_A = parseInt(gpf_epf_cpf)+parseInt(gis)+parseInt(life_insurance_premium)+parseInt(public_provident_fund)+parseInt(repayment_of_hbl_principal)+parseInt(tuition_fee_of_children)+parseInt(other_deduction);

        var gpf_epf_cpf = $('#gpf_epf_cpf').val() || 0;
        var gis = $('#gis').val() || 0;
        var life_insurance_premium = $('#life_insurance_premium').val() || 0;
        var public_provident_fund = $('#public_provident_fund').val() || 0;
        var repayment_of_hbl_principal = $('#repayment_of_hbl_principal').val() || 0;
        var tuition_fee_of_children = $('#tuition_fee_of_children').val() || 0;
        var other_deduction = $('#other_deduction').val() || 0;
        var section_80ccc = $('#section_80ccc').val() || 0;
        var section_80ccd = $('#section_80ccd').val() || 0;
        var total_deduction_9 = parseInt(gpf_epf_cpf)+parseInt(gis)+parseInt(life_insurance_premium)+parseInt(public_provident_fund)+parseInt(repayment_of_hbl_principal)+parseInt(tuition_fee_of_children)+parseInt(other_deduction)+parseInt(section_80ccc)+parseInt(section_80ccd);
        $('#total_deduction_9').val(total_deduction_9);



        var medical_insurance_premium = $('#medical_insurance_premium').val() || 0;
        var medical_treatment_of_handicapped = $('#medical_treatment_of_handicapped').val() || 0;
        var interest_on_higher_edu_loan = $('#interest_on_higher_edu_loan').val() || 0;
        var donations = $('#donations').val() || 0;
        var interest_on_sb_ac_deducation = $('#interest_on_sb_ac_deducation').val() || 0;
        var aggregate_of_deductible_amount = parseInt(total_deduction_9)+parseInt(medical_insurance_premium)+parseInt(medical_treatment_of_handicapped)+parseInt(interest_on_higher_edu_loan)+parseInt(donations)+parseInt(interest_on_sb_ac_deducation);

        $('#aggregate_of_deductible_amount').val(aggregate_of_deductible_amount);
        var total_income = parseInt(gross_total_income)-parseInt(aggregate_of_deductible_amount);
        $('#total_income').val(total_income);


        var tax_on_total_income = $('#tax_on_total_income').val() || 0;
        var rebate = $('#rebate').val() || 0;
        var bal_tax_payable = $('#bal_tax_payable').val() || 0;
        var edu_cess = $('#edu_cess').val() || 0;
        var tax_payable = parseInt(bal_tax_payable)+parseInt(edu_cess);
        $('#tax_payable').val(tax_payable);


        var less_relief = $('#less_relief').val() || 0;
        var tax_payable18 = parseInt(tax_payable)-parseInt(less_relief);
        $('#tax_payable18').val(tax_payable18);
        var tax_deduct = $('#tax_deduct').val() || 0;
        var balance_20 = parseInt(tax_payable18)-parseInt(tax_deduct);
        $('#balance_20').val(balance_20);

    }

    </script>
@endsection
