<li class="nav-item dropdown" style="border-left: 1px dotted #aaa">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img class="rounded-circle" width="30" height="30" src="{{ Auth::user()->avatar_link }}"> {{ Auth::user()->name }}
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="/profile"><i class="bi bi-person-fill"></i> Account details</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
                <i class="bi bi-lock-fill"></i> Logout
            </a>

            <form id="logout-form" action="/logout" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</li>
