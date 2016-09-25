<li class="{!! $active_users or '' !!}">
    <a href="{{route('employees.index')}}">
        <i class="fa fa-user"></i> <span class="nav-label">Сотрудники</span>
    </a>
</li>
@foreach($houses as $house)
    <li class="{{ ((isset($currentHouse) && $currentHouse == $house->id) ? 'active' : '')    }}">
        <a href="{{route('houses.create')}}">
            <i class="fa fa-home"></i> <span class="nav-label">{!! $house->address !!}</span>
        </a>
    </li>
@endforeach
<li class="{!! $active_new_house or '' !!}">
    <a href="{{route('houses.create')}}">
        <i class="fa fa-plus"></i> <span class="nav-label">Добавить дом</span>
    </a>
</li>