<?php
	include("include/session.php");
?>

<html>
	<title>Test Login Page</title>
	<style type="text/css">
		body {
			background: #FFF;
			color: #222;
			font-family: Verdana, Tahoma, Arial, Trebuchet MS, Sans-Serif, Georgia, Courier, Times New Roman, Serif;
			font-size: 10px;
			line-height: 135%;
			margin: 10px 10px 10px 10px;
		}
		td {
			font-family: Verdana, Tahoma, Arial, Trebuchet MS, Sans-Serif, Georgia, Courier, Times New Roman, Serif;
			font-size: 12px;
		}
		td.tableprop {
			background-color: #E4EFFF;
			padding: 1px;
			FONT-SIZE: 10pt;
			FONT-FAMILY: Verdana;
			text-align: center;
		}
		td.tableheadprop {
			background-color: #83A2D0;
			padding: 1px;
			FONT-SIZE: 10pt;
			FONT-FAMILY: Verdana;
			color: #FFFFFF;
			font-weight: bold;
			text-align: center;
		}
		h2, h3 {
			font-weight:bold;
			font-family: Comic Sans ms, Arial, Helvetica, sans-serif;
			COLOR: #165382;
		}

		h2 { font-size:30px; }
		h3 { font-size:18px; }

		a {
			FONT-SIZE: 10pt;
			FONT-FAMILY: Verdana;
			COLOR: #3E84C3;
		}
		.wcs {
			font-family: Comic Sans ms, Arial, Helvetica, sans-serif;
			COLOR: #929292;
			font-size: 12px;
			font-weight: bold;
		}
	</style>
<body>
<table>
    <tr>
        <td>
            <?php if ($session->logged_in): ?>
            <h2>Logged In</h2>
            Welcome <b><?php echo $session->username; ?></b>, you are logged in. <br><br>
            [<a href="process.php">Logout</a>]
            <?php else: ?>

            <h2>Login</h2>
            <?php if ($form->num_errors > 0): ?>
            <strong><font size="2" color="#ff0000"><?php echo $form->error("access"); ?></font></strong>
            <font size="2" color="#ff0000"><?php echo $form->error("attempt"); ?></font><br><br>
            <?php endif ?>

            <!--start form-->
            <form action="process.php" method="POST">
                <table align="left" border="0" cellspacing="0" cellpadding="3">
                    <tr>
                        <td>Username:</td>
                        <td>
                            <input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td>
                            <input type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="left">
                            <input type="checkbox" name="remember"
                                   <?php if ($form->value("remember") != ""): ?> checked <?php endif ?>
                            >
                            <font size="2">Remember me next time &nbsp;&nbsp;&nbsp;&nbsp;</font>
                            <input type="hidden" name="sublogin" value="1">
                            <input type="submit" value="Login">
                        </td>
                    </tr>
                </table>
            </form>
            <!--end form-->
        </td>
    </tr>
    <tr>
        <td>
            <br><br>
            <h3>Available Users:</h3>
            <?php echo $database->displayUsers(); ?>
        </td>
    </tr>
    <tr>
        <td>
            <br><br>
            <h3>Login Attempts:</h3>
            <?php echo $database->displayAttempts($session->ip); ?>
        </td>
    </tr>
    <?php endif ?>
    <tr>
        <td align=\"left\"><br><br>
            <p>
                <font size=1>
                    Blocking access to the login page after three unsuccessful login attempts. &copy;
                </font>
                <span class=wcs>www.WebCheatSheet.com</span>
            </p>
        </td>
    </tr>
</table>

</body>
</html>
