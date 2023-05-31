<div id="sidebar" class="sidebar sidebar-fixed expandable sidebar-light">
  <div class="sidebar-inner">

    <div class="ace-scroll flex-grow-1" data-ace-scroll="{}">

      <ul class="nav has-active-border active-on-right">

@can('allExceptMarketingOnly', auth()->user())
<li class="nav-item {{ Request::segment(1) == 'dashboard' || Request::segment(1) == 'home' ? 'active' : ''}}">
    <a href="{{url('dashboard')}}" class="nav-link">
      <i class="nav-icon fa fa-tachometer-alt"></i>
      <span class="nav-text fadeable">
        <span>Dashboard</span>
      </span>
    </a>
    <b class="sub-arrow"></b>
  </li>

  <li class="nav-item {{ Request::segment(1) == 'patients' ? 'active' : ''}}">
    <a href="{{url('patients')}}" class="nav-link">
      <i class="nav-icon fa fa-users"></i>
      <span class="nav-text fadeable">
        <span>Patients</span>
      </span>
    </a>
    <b class="sub-arrow"></b>
  </li>

  <li class="nav-item {{ Request::segment(1) == 'schedule' ? 'active' : ''}}">
    <a href="{{url('schedule')}}" class="nav-link">
      <i class="nav-icon fa fa-calendar-alt"></i>
      <span class="nav-text fadeable">
        <span>Schedule</span>
      </span>
    </a>
    <b class="sub-arrow"></b>
  </li>

  <li class="nav-item {{ Request::segment(1) == 'tickets' ? 'active' : ''}}">
    <a href="{{route('ticket.index')}}" class="nav-link">
      <i class="nav-icon fa fa-clipboard-list"></i>
      <span class="nav-text fadeable">
        <span>Tickets</span>
      </span>
    </a>
    <b class="sub-arrow"></b>
  </li>


  <li class="nav-item {{ Request::segment(1) == 'receivables' ? 'active' : ''}}">
      <a href="{{route('receivable.index')}}" class="nav-link">
          <i class="nav-icon fa fa-file-invoice-dollar"></i>
          <span class="nav-text fadeable">
              <span>Receivables</span>
          </span>
      </a>
      <b class="sub-arrow"></b>
  </li>
@endcan


@can('marketingOnly', auth()->user())
<li class="nav-item {{ Request::segment(1) == 'reports' ? 'active' : ''}}">
    <a href="#" onclick="return false;" class="nav-link dropdown-toggle collapsed">
    <i class="nav-icon fa fa-file-alt"></i>
    <span class="nav-text fadeable">
        <span>Reports</span>
    </span>
    <b class="caret fa fa-angle-left rt-n90"></b>
    </a>
    <div class="hideable submenu collapse">
    <ul class="submenu-inner">
        <li class="nav-item">
            <a href="{{route('report.marketing')}}" class="nav-link">
                <span class="nav-text">
                <span>Marketing</span>
                </span>
            </a>
        </li>
    </ul>
    </div>
    <b class="sub-arrow"></b>
</li>
<li class="nav-item {{ Request::segment(1) == 'settings' ? 'active' : ''}}">
    <a href="#" onclick="return false;" class="nav-link dropdown-toggle collapsed">
      <i class="nav-icon fa fa-cogs"></i>
      <span class="nav-text fadeable">
        <span>Settings</span>
      </span>
      <b class="caret fa fa-angle-left rt-n90"></b>
    </a>
    <div class="hideable submenu collapse">
      <ul class="submenu-inner">
        <li class="nav-item">
            <a href="{{url('settings/marketing-info')}}" class="nav-link">
              <span class="nav-text">
                  <span>Marketing Sources</span>
              </span>
          </a>
      </li>
      </ul>
    </div>
    <b class="sub-arrow"></b>
  </li>
@endcan
@can('superadminORadminORmanager', auth()->user())

        <li class="nav-item {{ Request::segment(1) == 'reports' ? 'active' : ''}}">
            <a href="#" onclick="return false;" class="nav-link dropdown-toggle collapsed">
            <i class="nav-icon fa fa-file-alt"></i>
            <span class="nav-text fadeable">
                <span>Reports</span>
            </span>
            <b class="caret fa fa-angle-left rt-n90"></b>
            </a>
            <div class="hideable submenu collapse">
            <ul class="submenu-inner">
                <li class="nav-item">
                <a href="{{route('report.marketing')}}" class="nav-link">
                    <span class="nav-text">
                    <span>Marketing</span>
                    </span>
                </a>
                </li>
                <li class="nav-item">
                <a href="{{route('report.financial')}}" class="nav-link">
                    <span class="nav-text">
                    <span>Financials</span>
                    </span>
                </a>
                </li>

                <li class="nav-item">
                  <a href="{{route('report.sales-by-product')}}" class="nav-link">
                      <span class="nav-text">
                      <span>Sales By Product</span>
                      </span>
                  </a>
                  </li>

                  <li class="nav-item">
                    <a href="{{route('report.marketing-trend', ['type' => 'WoW'])}}" class="nav-link">
                        <span class="nav-text">
                        <span>Marketing Trend</span>
                        </span>
                    </a>
                  </li>
            </ul>
            </div>
            <b class="sub-arrow"></b>
        </li>


        <li class="nav-item {{ Request::segment(1) == 'settings' ? 'active' : ''}}">
          <a href="#" onclick="return false;" class="nav-link dropdown-toggle collapsed">
            <i class="nav-icon fa fa-cogs"></i>
            <span class="nav-text fadeable">
              <span>Settings</span>
            </span>
            <b class="caret fa fa-angle-left rt-n90"></b>
          </a>


          <div class="hideable submenu collapse">
            <ul class="submenu-inner">
              @can('manageLocations', auth()->user())
              <li class="nav-item">
                <a href="{{url('settings/locations')}}" class="nav-link">
                  <span class="nav-text">
                    <span>Locations</span>
                  </span>
                </a>
              </li>
              @endcan
              @can('manageUsers', auth()->user())
              <li class="nav-item">
                <a href="{{url('settings/users')}}" class="nav-link">
                  <span class="nav-text">
                    <span>Staff/Users</span>
                  </span>
                </a>
              </li>
              @endcan
              <li class="nav-item">
                  <a href="{{url('settings/marketing-info')}}" class="nav-link">
                    <span class="nav-text">
                        <span>Marketing Sources</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{url('settings/edit/clinic-info')}}" class="nav-link">
                    <span class="nav-text">
                        <span>Clinic Info</span>
                    </span>
                </a>
            </li>
            @can('manageScheduleTypes', auth()->user())
            <li class="nav-item">
                <a href="{{url('settings/schedule-types')}}" class="nav-link">
                    <span class="nav-text">
                        <span>Schedule Types</span>
                    </span>
                </a>
            </li>
            @endcan
            @can('manageRoles', auth()->user())
            <li class="nav-item">
                <a href="{{url('settings/roles')}}" class="nav-link">
                    <span class="nav-text">
                        <span>Roles</span>
                    </span>
                </a>
            </li>
            @endcan
            @can('managePricing', auth()->user())
            <li class="nav-item">
                <a href="{{route('pricing')}}" class="nav-link">
                    <span class="nav-text">
                        <span>Pricing</span>
                    </span>
                </a>
            </li>
            @endcan
            @can('manageZingleIntegration', auth()->user())
            <li class="nav-item">
                <a href="{{url('/zingle-integration', session('current_location')->id)}}" class="nav-link">
                    <span class="nav-text">
                        <span>Zingle Integration</span>
                    </span>
                </a>
            </li>
            @endcan
            @can('export', auth()->user())
            <li class="nav-item">
                <a href="{{route('exports.patinets')}}" class="nav-link">
                    <span class="nav-text">
                        <span>Export Patients</span>
                    </span>
                </a>
            </li>
            @endcan
            @can('export', auth()->user())
            <li class="nav-item">
                <a href="{{route('exports.tickets')}}" class="nav-link">
                    <span class="nav-text">
                        <span>Export Tickets</span>
                    </span>
                </a>
            </li>
            @endcan
            @can('export', auth()->user())
            <li class="nav-item">
                <a href="{{route('exports.schedules')}}" class="nav-link">
                    <span class="nav-text">
                        <span>Export Schedules</span>
                    </span>
                </a>
            </li>
            @endcan
            @can('export', auth()->user())
            <li class="nav-item">
                <a href="{{route('exports.payments')}}" class="nav-link">
                    <span class="nav-text">
                        <span>Export Payments</span>
                    </span>
                </a>
            </li>
            @endcan
            @can('superadmin', auth()->user())
            <li class="nav-item">
                <a href="{{route('log.index')}}" class="nav-link">
                    <span class="nav-text">
                        <span>Export Log</span>
                    </span>
                </a>
            </li>
            @endcan
            @can('superadmin', auth()->user())
            <li class="nav-item">
                <a href="{{route('notification.create')}}" class="nav-link">
                    <span class="nav-text">
                        <span>Daily Stats Notification</span>
                    </span>
                </a>
            </li>
            @endcan
            @can('superadmin', auth()->user())
            <li class="nav-item">
                <a href="{{url('settings/emails')}}" class="nav-link">
                    <span class="nav-text">
                        <span>Emails</span>
                    </span>
                </a>
            </li>
            @endcan
            </ul>
          </div>
          <b class="sub-arrow"></b>
        </li>
        @endcan

      </ul>

    </div><!-- /.sidebar scroll -->

  </div>
</div>
