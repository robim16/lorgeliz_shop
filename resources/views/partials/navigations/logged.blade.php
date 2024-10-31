<li class="nav-item dropdown d-flex flex-row align-items-center justify-content-start">

    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">

        <img src="{{ auth()->user() ? (auth()->user()->imagene ? url('storage/' . auth()->user()->imagene->url) : asset('asset/images/user.svg')) : asset('asset/images/user.svg') }}"
            alt="user image" class="rounded-circle" style="width: 34px">

        <span class="caret"></span>

    </a>

    <div class="dropdown-menu" aria-labelledby="navbarDropdown">

        <a class="dropdown-item"
            href="{{ auth()->user() ? route('users.show', auth()->user()->slug) : route('login') }}">
            {{ __('Mi cuenta') }}
        </a>

        @auth

            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
                {{ __('Cerrar sesión') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

        @endauth
    </div>
</li>
