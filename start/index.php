<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Index page</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/business-casual.css" rel="stylesheet">
    <link href="../css/mycss.css" rel="stylesheet" type="text/css">
</head>

<body>

    <div class="brand" id="companyname"> FATMA COMPANY</div>
    <div class="address-bar">The Plaza | 5483 | Beverly Hills, Thika 26892 | 555.519.2013</div>
    <!----------head---------->
    	<?php require_once("../includes/start_head.php");?>
    <!----------head---------->
	<div class="container">

        <div class="row">
            <div class="box">
                <div class="col-lg-12 text-center">
                 <!---------start images---------->
                 <?php require_once("../includes/start_images.php");?>
                 <!---------start images---------->
                    <h2>
                        <small>Welcome to</small>
                    </h2>
                    <h1>
                        <span class="brand-name">## company website</span>
                    </h1>
                    <hr class="tagline-divider">
                    <h2>
                        <small>By <strong>###</strong></small>
                    </h2>
                </div>
            </div>
        </div>
	<!--------------content------------------------>
     <?php require_once("../includes/content.php");?>
	<!--------------content------------------------>
    </div>
    <!-- /.container -->

    <footer>
       <?php require_once("../includes/footer.php");?>
    </footer>

    <!-- JavaScript -->
<script src="../js/jquery-1.10.2.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/myjs.js"></script>

</body>

</html>
