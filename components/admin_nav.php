<div class="offcanvas-header p-0">
    <div></div>
    <h5 class="offcanvas-title" id="sidebarLabel">Adminpaneel</h5>
    <div><button type="button" class="btn-close btn-close-white text-reset d-block d-lg-none" data-bs-dismiss="offcanvas" aria-label="Sluiten"></button></div>
</div>
<hr>
<ul class="nav nav-pills flex-column mb-auto">
    <li>
        <a href="index.php" class="nav-link text-white<?= setActive('index.php') ?>">
            <i class="me-2 fa-fw fa-solid fa-clipboard-list"></i>
            <span>Overzicht</span>
        </a>
    </li>
    <?php if (isset($_SESSION["perms"]["event"]) || isset($_SESSION["perms"]["admin"])): ?>
        <li>
            <a href="settings_event.php" class="nav-link text-white<?= setActive('settings_event.php') ?>" aria-current="page">
                <i class="me-2 fa-fw fa-solid fa-screwdriver-wrench"></i>
                <span>Evenementinstellingen</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if (isset($_SESSION["perms"]["activiteit"]) || isset($_SESSION["perms"]["admin"])): ?>
        <li>
            <a href="settings_activities.php" class="nav-link text-white<?= setActive('settings_activities.php') ?>">
                <i class="me-2 fa-fw fa-solid fa-check"></i>
                <span>Activiteiten</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if (isset($_SESSION["perms"]["qr-code"]) || isset($_SESSION["perms"]["admin"])): ?>
        <li>
            <a href="settings_qrcodes.php" class="nav-link text-white<?= setActive('settings_qrcodes.php') ?>">
                <i class="me-2 fa-fw fa-solid fa-qrcode"></i>
                <span>QR Codes</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if (isset($_SESSION["perms"]["scanner"]) || isset($_SESSION["perms"]["admin"])): ?>
        <li>
            <a href="scanner.php" class="nav-link text-white<?= setActive('scanner.php') ?>">
                <i class="me-2 fa-fw fa fa-solid fa-camera"></i>
                <span>Scanner</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if (isset($_SESSION["perms"]["admin"])): ?>
        <li>
            <a href="settings_accounts.php" class="nav-link text-white<?= setActive('settings_accounts.php') ?>">
                <i class="me-2 fa-fw fa fa-solid fa-user-gear"></i>
                <span>Accountmanagement</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if (isset($_SESSION["perms"]["admin"])): ?>
        <li>
            <a href="settings_reset.php" class="nav-link text-danger<?= setActive('settings_reset.php') ?>">
                <i class="me-2 fa-fw fa-solid fa-arrows-rotate"></i>
                <span>RESET</span>
            </a>
        </li>
    <?php endif; ?>
</ul>

<p class="small text-muted mb-0">Beta 0.8.9<p>
<hr class="mt-0">
<div class="dropdown">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAMAAACdt4HsAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAD2UExURbGxsa+vr62trbKysrW1tcfHx93d3evr6/Ly8vPz8+3t7eDg4MzMzLi4uLOzs9DQ0P7+/v////b29tjY2La2trq6uunp6fDw8MHBwb29vfj4+MbGxvHx8fn5+eXl5cvLy9ra2vr6+rm5ubCwsMnJyeTk5PT09LS0tPf3976+vv39/c3NzcjIyNnZ2c/Pz9/f39HR0ePj487OztfX17y8vLu7u8XFxdLS0re3t9TU1Ozs7MDAwN7e3ujo6Orq6vz8/O/v77+/v+fn59XV1cLCwuHh4dvb28PDw9bW1tzc3Pv7++Li4sTExMrKyubm5u7u7vX19a6urs05bDQAAAAJcEhZcwAADsIAAA7CARUoSoAAAAKMSURBVFhH7ZdZW9pAFIaDIKuhQQRECFCkxGJbkVoVFAFtXbDr//8zzfJlMssZnXLd90Y853zvEzLjJFocqQ3YQnZjUvi5Mf8FrwrSPvio4SVBZjubyxeKpR0bBQq9oPwm71Qidqt7KKpoBbV6HA9o7KOsoBM084iCgxYaMhqB3UaQ4WquQSPoIMbRpu8DLejy3z+mh6YIKSgrXyDgbR9tgZR1iE8cA+oCKpUq2gKk4B0SEkNqU1IC7wgJCec9BngoQXOEhMwxBngowQfMK3zEAA8l+IR5hRMM8FCCMeYVTtVZUjDBvILpFbTobVCpfMYADyU4061CFwM8lKA8REDC+YIBHkpglZCQaKMtQArO6ZtwgbYAKUgXERE4ukRbgBTQ6zBFU4QWWFmEOGYeeiIaQSaHGGNILYGPRmD1JUPhCg0ZncDKXHPbyZnXUFbQCqx062YRxUfLSRlFFVVg72Trs17G/5RejUu389O7r9Hts7O53LeJ/JyUBc0TN1zC5Sr6PXk4r8Kj2nHvRYUo8KpuMBXgXgiX7U1ZZzhALUQQ2DeYCXByDyj71zFYcltrcccdz7xAXvxRvdv3R73mtCHuTOcRCR9e8IQ+x7qwbOyqx8PiHBFBsI9VM6HIbhAneEbThNF3hDhB/wBNI66R4gQ/6FNEQzv+20wEPbTMWMfPyUTwEy1D4hOaCdK/0DHkPoolAu83OobMo1giuGR73Yw8dgITaF8KNLg4o5ngAQ1TnLMoxwRdNIzBXmSCKerGYB1jwSHxJHiZcZhLrkDzaqcHLwtMMEPdGOykWFCWXu9fpxTmmGBP83Kp5znMMUFtjboxnTDHBFf/uBEVwTbK5jDBn/C/+GOUzemEudTWX/lklWyZ8wkkAAAAAElFTkSuQmCC" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong><?= ucfirst($_SESSION["user"]) ?></strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser">
        <li><a class="dropdown-item" href="logout.php">Uitloggen</a></li>
    </ul>
</div>