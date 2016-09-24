<li class="{!! $active_user or '' !!}">
    <a href="{{route('employees.index')}}"><i class="fa fa-users"></i> <span class="nav-label">Сотрудники</span></a>
</li>
<li class="{!! $active_dictionary or '' !!}">
    <a href="{{route('dictionary.index')}}"><i class="fa fa-book"></i> <span class="nav-label">Словари</span></a>
</li>