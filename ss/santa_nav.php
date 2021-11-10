<nav>
    <ul>
        <li><a href="logout.php">Log Out</a></li>
        <?php if( $_SESSION['role'] === 1 ): ?>
            <li><a href="admin.php">Admin</a></li>
        <?php endif ?>
    </ul>
</nav>