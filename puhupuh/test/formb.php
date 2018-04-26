<table border="1">
    <form action="#" method="post">
        <tr>
            <th colspan="2">U bent aangelogd als: <?php echo $_SESSION['username']; ?></th>
        </tr>
        <tr>
            <td>Views:</td>
            <td><input type="text" value="<?php echo $_SESSION['views']; ?>" readonly></td>
        </tr>
        <tr>
            <td><input type="submit" name="action" value="refresh"></td>
            <td><input type="submit" name="action" value="logoff"></td>
        </tr>
    </form>
</table>
