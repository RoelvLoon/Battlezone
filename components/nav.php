<nav class="navbar navbar-expand-lg navbar-light bg-nav">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <img src="/img<?= $siteData["nav_logo"] ?>" alt="" width="25" height="25" class="d-inline-block align-text-top user-select-none">
            <span class="ms-1 bg-nav user-select-none"><?= $siteData["title"] ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHamburger" aria-controls="navbarHamburger" aria-expanded="false" aria-label="Navigatie">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarHamburger">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 px-3 py-1 p-lg-0">
                <li class="nav-item user-select-none">
                    <a class="nav-link bg-nav rounded p-2" href="/activiteiten">Activiteiten</a>
                </li>
            </ul>
            <div class="dropdown">
                <span class="d-flex align-items-center text-decoration-none fw-lighter dropdown-toggle" role="button" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?= $photo ?>" alt="PF" width="32" height="32" class="rounded-circle me-2 user-select-none">
                    <strong class="user-select-none"><?= $profile->displayName ?></strong>
                </span>
                <ul class="dropdown-menu dropdown-menu-end shadow col-12 col-lg-none" style="min-width: 200px;" aria-labelledby="dropdownUser">
                    <div class="m-2">
                        <p class="fw-bold mb-0"><?= $profile->displayName ?></p>
                        <p class="fw-normal text-muted mt-0 mb-1"><?= $profile->jobTitle ?></p>
                    </div>
                    <li><a class="dropdown-item" href="index.php?action=logout">Uitloggen</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>