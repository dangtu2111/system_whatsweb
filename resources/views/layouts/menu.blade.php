<li class="menu-header">Dashboard</li>
<li class="{{ Request::is(config('whatsweb.backend')) ? 'active' : '' }}">
	<a href="{{ route('backend') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
</li>

<li class="menu-header">Master Data</li>
<li class="{{ Request::is(config('whatsweb.backend') . '/users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="far fa-user"></i><span>Users</span></a>
</li>

<li class="{{ Request::is(config('whatsweb.backend') . '/links*') ? 'active' : '' }}">
    <a href="{!! route('links.index') !!}"><i class="fas fa-link"></i><span>Links</span></a>
</li>

<li class="{{ Request::is(config('whatsweb.backend') . '/pages*') ? 'active' : '' }}">
    <a href="{!! route('pages.index') !!}"><i class="far fa-file"></i><span>Pages</span></a>
</li>

<li class="menu-header">Reports</li>
<li class="{{ Request::is(config('whatsweb.backend') . '/reports*') ? 'active' : '' }}">
    <a href="{!! route('reports.index') !!}"><i class="fas fa-chart-line"></i><span>Reports</span></a>
</li>

<li class="menu-header">Settings</li>
<li class="{{ Request::is(config('whatsweb.backend') . '/settings*') ? 'active' : '' }}">
    <a href="{!! route('settings.index') !!}"><i class="fas fa-cog"></i><span>Setting</span></a>
</li>
