<?php
    include_once("funciones.php");
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Find it</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <!-- Fonts -->
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="css/animate.css" rel="stylesheet" />
        <!-- Squad theme CSS -->
        <link href="css/style.css" rel="stylesheet">
        <link href="color/default.css" rel="stylesheet">

    </head>
    
    <body id="page-top" data-spy="scroll" data-target=".navbar-custom">
	<!-- Preloader -->
        <div id="preloader">
            <div id="load"></div>
	    </div>
        <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
            <div class="container">
                <a class="navbar-brand" href="index.html">
                    <h1>FIND IT</h1>
                </a>
                
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.html">Home</a></li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>
        
        <section id="intro" class="intro">
            <div class="slogan">
                <h2>Register</h2>
			     <h4>Join the community and help others to find their lost objects</h4>
            </div>
            <div class="page-scroll">
            </div>
        </section>
        <!-- Section: services -->
        <section id="service" class="home-section text-center bg-gray">
            <div class="heading-about">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-lg-offset-2">
                            <div class="wow bounceInDown" data-wow-delay="0.4s">
                                <div class="section-heading">
                                    <h2>Register</h2>
                                    <i class="fa fa-2x fa-angle-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <p>Insert your personal data to create a new profile</p>
                    <br>
                    <br>
                    <form class="block-form" method="post"          action="funciones.php">
                        <input type="text"      name="name"             placeholder="Nombre"        id="name" required/>
                        <input type="text"      name="mail"             placeholder="E-Mail"        id="mail" required/>
                        <input type="password"  name="password"         placeholder="Password"      id="password" required/>
                        <input type="password"  name="checkPassword"    placeholder="CheckPasword"  id="CheckPassword" required/>
                        <?php 
                            echo $_SESSION["Error"]; 
                            $_SESSION["Error"] = "";
                        ?>
                        <br>
                        <br>
                        <br>
                        <br>
                        <input type="submit" class="login centered-button" name="add" value="Register"/>
                    </form>
                </div>
            </div>
        </section>
        
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <p>&copy;Copyright 2015 -  All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Core JavaScript Files -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.easing.min.js"></script>	
	    <script src="js/jquery.scrollTo.js"></script>
	    <script src="js/wow.min.js"></script>
        <!-- Custom Theme JavaScript -->
        <script src="js/custom.js"></script>
        
    </body>
</html>
