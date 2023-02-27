@foreach ($sidebars as $item)
    @php
        $item = (object) $item;
    @endphp

    <li>
        <a href="{{ $item->url }}" class="{{ count($item->child) > 0 ? 'dropdown-toggle' : '' }}">
            <i class="menu-icon {{ $item->icon }}"></i>
            <span class="menu-text">
                {{ $item->name }}
            </span>
            @if (count($item->child) > 0)
                <b class="arrow fa fa-angle-down"></b>
            @endif
        </a>
        @if (count($item->child) > 0)
            <ul class="submenu">
                @foreach ($item->child as $item)
                    <li>
                        <a href="{{ $item['url'] }}" target="{{ $item['target'] }}">
                            <i class="menu-icon fa fa-caret-right"></i>{{ $item['name'] }}</a>
                        <b class="arrow"></b>
                    </li>
                @endforeach
            </ul>
        @endif
    </li>
@endforeach
