<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            @include('layouts.partials.user_block')
            @include('layouts.partials.sidebar_'.auth()->user()->role->keyword)
        </ul>
    </div>
</nav>
