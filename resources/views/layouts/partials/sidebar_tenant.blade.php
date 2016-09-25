@foreach($flats as $flat)
<li class="{!! $active_user or '' !!}">
    <a href="{{route('employees.index')}}"><i class="fa fa-users"></i> <span class="nav-label">Сотрудники</span></a>
</li>
@endforeach
<li class="{!! $active_dictionary or '' !!}">
    <a href="{{route('flats.attach')}}">
        <i class="fa fa-plus"></i> <span class="nav-label">Добавить квартиру</span>
    </a>
</li>