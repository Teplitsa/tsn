@foreach($flats as $flat)
    <li class="{{ ((isset($currentFlat) && $currentFlat == $flat->id) ? 'active' : '')    }}">
        <a href="{{route('flats.show', $flat)}}">
            <i class="fa fa-home"></i> <span class="nav-label">{{ $flat->address }}</span>
        </a>
    </li>
@endforeach
<li class="{!! $active_dictionary or '' !!}">
    <a href="{{route('flats.attach')}}">
        <i class="fa fa-plus"></i> <span class="nav-label">Добавить квартиру</span>
    </a>
</li>