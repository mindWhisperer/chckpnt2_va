<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{route('home')}}"><img class="logo" src="/images/knihy.png" alt="knihy"/></a>
        <div class="navbar-nav btn-group">
            <a class="nav-link" href="{{route('home')}}">Novinky</a>
            <a class="nav-link" href="{{route('best-of')}}">Stálice</a>
            {{-- IF_LOGGED --}}
            @if(\App\Helpers\Roles::isLogged())
                <div class="top-menu-dropdown">
                    <input type="checkbox" id="dropdown">
                    <label for="dropdown" class="nav-link rounded">≡</label>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{route('panel')}}">Panel</a>
                        <a class="dropdown-item" href="{{route('profile')}}">Profil</a>
                        <a class="dropdown-item" href="{{route('add-book')}}">Pridať knihu</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{route('logout')}}">Odhlásiť</a>
                    </div>
                </div>

                {{-- IF_NOT_LOGGED --}}
            @else
                <a class="btn btn-success mb-0" href="{{route('login')}}">Prihlásiť</a>
            @endif
        </div>
    </div>
</nav>
