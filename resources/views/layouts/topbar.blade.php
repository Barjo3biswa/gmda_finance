<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="header-top-menu tabl-d-n">
        <ul class="nav navbar-nav mai-top-nav">
            <li class="nav-item"><a href="{{ route('home') }}" class="nav-link">Dashboard</a>
            </li>
            @if (
                    \App\Helpers\commonHelper::isPermissionExist('salary_head_view') ||
                    \App\Helpers\commonHelper::isPermissionExist('salary_head_create') ||
                    \App\Helpers\commonHelper::isPermissionExist('salary_head_update') ||
                    \App\Helpers\commonHelper::isPermissionExist('salary_management')
                )
                            <li class="nav-item dropdown res-dis-nn">
                                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                                    class="nav-link dropdown-toggle">Master<span class="angle-down-topmenu"><i
                                            class="fa fa-angle-down"></i></span></a>
                                <div role="menu" class="dropdown-menu animated zoomIn">
                                    @if (
                                            \App\Helpers\commonHelper::isPermissionExist('salary_head_view') ||
                                            \App\Helpers\commonHelper::isPermissionExist('salary_head_create') ||
                                            \App\Helpers\commonHelper::isPermissionExist('salary_head_update')
                                        )
                                                        <a href="{{ route('salary-head') }}" class="dropdown-item">Salary Head</a>
                                    @endif
                                    @if (\App\Helpers\commonHelper::isPermissionExist('salary_management'))
                                        <a href="{{ route('emp-amount') }}" class="dropdown-item">Head Wise Amount</a>
                                    @endif
                                    @if (\App\Helpers\commonHelper::isPermissionExist('salary_excel_upload'))
                                        <a href="{{ route('excel-upload') }}" class="dropdown-item">Upload Excel</a>
                                        <a href="{{ route('hold-unhold') }}" class="dropdown-item">Hold/Unhold Salary</a>
                                    @endif
                                </div>
                            </li>
            @endif
            @if (
                    \App\Helpers\commonHelper::isPermissionExist('salary_block_management') ||
                    \App\Helpers\commonHelper::isPermissionExist('salary_process') ||
                    \App\Helpers\commonHelper::isPermissionExist('salary_summery_management') ||
                    \App\Helpers\commonHelper::isPermissionExist('salary_excel_upload')
                )
                            <li class="nav-item dropdown res-dis-nn">
                                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                                    class="nav-link dropdown-toggle">Salary<span class="angle-down-topmenu"><i
                                            class="fa fa-angle-down"></i></span></a>
                                <div role="menu" class="dropdown-menu animated zoomIn">
                                    {{-- @if (\App\Helpers\commonHelper::isPermissionExist('salary_block_management'))
                                    <a href="{{ route('pay-slip-indi') }}" class="dropdown-item">Pay Slip</a>
                                    @endif --}}
                                    @if (\App\Helpers\commonHelper::isPermissionExist('salary_block_management'))
                                        <a href="{{ route('salary-block') }}" class="dropdown-item">Salary Block</a>
                                    @endif
                                    @if (\App\Helpers\commonHelper::isPermissionExist('salary_process'))
                                        <a href="{{ route('salary-process', ['view' => 'process']) }}" class="dropdown-item">Process
                                            Salary</a>
                                    @endif
                                    @if (\App\Helpers\commonHelper::isPermissionExist('salary_summery_management'))
                                        <a href="{{ route('salary-process', ['view' => 'summery']) }}" class="dropdown-item">Salary
                                            Summery</a>

                                        <a href="{{ route('salary-summery-net') }}" class="dropdown-item">Salary
                                            Summery (Net)</a>

                                        <a href="{{ route('pay-slip-report') }}" class="dropdown-item">Pay Slip (Report)</a>
                                    @endif
                                </div>
                            </li>
            @endif
            <li class="nav-item dropdown res-dis-nn">
                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                    class="nav-link dropdown-toggle">Advances <span class="angle-down-topmenu"><i
                            class="fa fa-angle-down"></i></span></a>
                <div role="menu" class="dropdown-menu animated zoomIn">
                    <a href="{{ route('advance.index') }}" class="dropdown-item">Approved Advances</a>
                    <!-- <a href="{{ route('advance.existing') }}" class="dropdown-item">Create Advance(Existing)</a> -->
                    <a href="{{ route('advance.viewadvances') }}" class="dropdown-item">Advances</a>
                    <a href="{{ route('advance.processadvance') }}" class="dropdown-item">Process Advance</a>
                    <a href="{{ route('advance.processed_data_list') }}" class="dropdown-item">Process List</a>
                </div>
            </li>
            <li class="nav-item dropdown res-dis-nn">
                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                    class="nav-link dropdown-toggle">Loans <span class="angle-down-topmenu"><i
                            class="fa fa-angle-down"></i></span></a>
                <div role="menu" class="dropdown-menu animated zoomIn">
                    <a href="{{ route('loan.index') }}" class="dropdown-item">View Loans</a>
                    <a href="{{ route('loan.create') }}" class="dropdown-item">Create New Loan</a>
                    <!-- <a href="{{ route('loan.existing') }}" class="dropdown-item">Create Loan(Existing)</a> -->
                    <!-- <a href="{{-- route('loan.viewloan') --}}" class="dropdown-item">View Loan</a> -->
                    <a href="{{ route('loan.processloan') }}" class="dropdown-item">Process Loan</a>
                    <a href="{{ route('loan.processed_loan_list') }}" class="dropdown-item">Processed List</a>
                </div>
            </li>
            <li class="nav-item dropdown res-dis-nn">
                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                    class="nav-link dropdown-toggle">Policy <span class="angle-down-topmenu"><i
                            class="fa fa-angle-down"></i></span></a>
                <div role="menu" class="dropdown-menu animated zoomIn">
                    <a href="{{ route('policy.index') }}" class="dropdown-item">View Policy</a>
                    <a href="{{ route('policy.create') }}" class="dropdown-item">Create New Policy</a>
                    <a href="{{ route('policy.updatepolicy') }}" class="dropdown-item">Update Policy</a>
                    <!-- <a href="{{-- route('loan.viewloan') --}}" class="dropdown-item">View Loan</a> -->
                    <a href="{{ route('policy.processpolicy') }}" class="dropdown-item">Process Policy</a>
                    <!-- <a href="{{ route('policy.processed_policy_list') }}" class="dropdown-item">Processed List</a> -->
                </div>
            </li>
            <li class="nav-item dropdown res-dis-nn">
                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                    class="nav-link dropdown-toggle">Form16 <span class="angle-down-topmenu"><i
                            class="fa fa-angle-down"></i></span></a>
                <div role="menu" class="dropdown-menu animated zoomIn">
                    <a href="{{ route('form16.index') }}" class="dropdown-item">Create New Form16</a>
                    <a href="{{ route('form16.viewIndex') }}" class="dropdown-item">View Form16</a>
                </div>
            </li>
            <li class="nav-item dropdown res-dis-nn">
                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                    class="nav-link dropdown-toggle">Reports <span class="angle-down-topmenu"><i
                            class="fa fa-angle-down"></i></span></a>
                <div role="menu" class="dropdown-menu animated zoomIn">
                    <a href="{{ route('report.salaryHeadReport') }}" class="dropdown-item">Salary Head Report</a>
                    <a href="{{ route('report.summaryHeadReport') }}" class="dropdown-item">Summary Head Report</a>
                </div>
            </li>
            <li class="nav-item dropdown res-dis-nn">
                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                    class="nav-link dropdown-toggle">Savings Register<span class="angle-down-topmenu"><i
                            class="fa fa-angle-down"></i></span></a>
                <div role="menu" class="dropdown-menu animated zoomIn">
                    <a href="{{ route('report.glsiReport')}}" class="dropdown-item">GLSI Report</a>
                    <a href="{{ route('report.npsReport')}}" class="dropdown-item">NPS Report</a>
                    <a href="{{ route('report.sssReport')}}" class="dropdown-item">SSS Report</a>
                    <a href="{{ route('report.licReport')}}" class="dropdown-item">LIC Report</a>
                </div>
            </li>
        </ul>
    </div>
</div>