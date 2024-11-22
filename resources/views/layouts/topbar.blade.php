<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="header-top-menu tabl-d-n">
        <ul class="nav navbar-nav mai-top-nav">
            <li class="nav-item"><a href="#" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item dropdown res-dis-nn">
                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                    class="nav-link dropdown-toggle">Attendance <span class="angle-down-topmenu"><i
                            class="fa fa-angle-down"></i></span></a>
                <div role="menu" class="dropdown-menu animated zoomIn">
                    <a href="#" class="dropdown-item">View</a>
                    <a href="#" class="dropdown-item">View & Manage</a>
                </div>
            </li>
            <li class="nav-item dropdown res-dis-nn">
                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                    class="nav-link dropdown-toggle">Leave<span class="angle-down-topmenu"><i
                            class="fa fa-angle-down"></i></span></a>
                <div role="menu" class="dropdown-menu animated zoomIn">
                    <a href="#" class="dropdown-item">Apply</a>
                    @if (\App\Helpers\commonHelper::isPermissionExist('leave_master_edit'))
                        <a href="#" class="dropdown-item">Leave Type</a>
                    @endif
                    @if (
                        \App\Helpers\commonHelper::isPermissionExist('approve_leave') ||
                            \App\Helpers\commonHelper::isPermissionExist('recommand_leave'))
                        <a href="#" class="dropdown-item">Inbox</a>
                        <a href="#" class="dropdown-item">Outbox</a>
                    @endif
                </div>
            </li>
            <li class="nav-item"><a href="#" class="nav-link">Holyday</a>
            </li>
        </ul>
    </div>
</div>
