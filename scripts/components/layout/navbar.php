<?php
if ($auth->isLoggedIn()) {
?>
<nav id="navbar">
    <ul>
        <li><a href="/dashboard">PMPs</a></li>
        <li><a href="/profile">Profil</a></li>
    </ul>
</nav>
<?php
};