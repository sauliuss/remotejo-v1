<div class="loadbar"></div>
<nav class="nav-wrapper">
    <div class="nav">
        <a href="/" class="logo">
            <svg class="logo__img" id="logo" aria-hidden="true">
                 <use xlink:href="{{ asset('img/svg_symbols.svg#logo')}}"></use>
            </svg>
        </a>
        <div class="nav__menu">
            <a href="/companies" class="nav__menu__item"><span class="link">Remote Companies</span></a>
            <a href="/stacks" class="nav__menu__item"><span class="link">Remote Jobs</span></a>
        </div>
        <div class="nav__menu">
            <a href="/review" class="nav__menu__item"><span class="link">Review a Company</span></a>
            <a href="/add-company" class="nav__menu__item"><span class="link">Add a Company</span></a>
            <a href="/new-job" class="btn btn--small btn--yellow btn--post">Post a job</a>
        </div>
        <button type="button" href="#" tabindex="9999" class="btn-menu">
            <svg role="img" class="icon icon icon--fill icon-menu" aria-hidden="true">
                <use xlink:href="/img/svg_symbols.svg#icon-menu"></use>
            </svg>
            <ul class="popup-menu">
                <li class="popup-menu__item">
                    <a href="/review">Remote companies</a>
                </li>
                <li class="popup-menu__item">
                    <a href="/review">Remote jobs</a>
                </li>
                <li class="popup-menu__item">
                    <a href="/review">Review a company</a>
                </li>
                <li class="popup-menu__item">
                    <a href="/add-company">Add a company</a>
                </li>
                <li class="popup-menu__item popup-menu__item--yellow">
                    <a href="/new-job">Post a job</a>
                </li>
            </ul>
        </button>
<!--        <div class="btn-show-menu-user">
            <img class="avatar__img is-circle is-small" src="/assets/img/user-1.jpeg" alt="">
            <a href="#" class="link in-menu">John Doe</a>   
            <svg class="icon-arrow is-down is-small" aria-hidden="true">
                <use xlink:href="/assets/img/sprite.svg#icon-down"></use>
            </svg>      
        </div> -->
<!--        <div class="popup popup-nav">
            <div class="contain has-no-padding">
                <div class="popup__menu">
                    <a class="popup__menu__item" href="/profile.html">
                        <svg class="icon-user" id="icon-user" aria-hidden="true">
                            <use xlink:href="/assets/img/sprite.svg#icon-user"></use>
                        </svg>
                        <span>Profile</span>
                    </a>
                    <a class="popup__menu__item" href="/profile.html">
                        <svg class="icon-logout" id="icon-logout" aria-hidden="true">
                            <use xlink:href="/assets/img/sprite.svg#icon-logout"></use>
                        </svg>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div> -->
    </div>
</nav>