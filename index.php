<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'init.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'>
    <link rel='stylesheet' href='../css/main.css'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.0.1/css/ol.css" type="text/css">
    <title>Musleh's PD Services</title>
    <script type="text/javascript" src="../js/graph.js"></script>
    <script type="text/javascript" src="../js/collapse.js"></script>
    <script type="text/javascript" src="../js/olmap.js"></script>
   
    </script>
</head>
<body>
<!-- Make the Banner, and the logout button that shows when user is logged in -->
 <div class='bwrapper'> <div class='banner'> 
  
    <div id="mainb"><h2>Musleh's Parkinson Disease Services</h2><p>- for Patients, Physicians, and Researchers</p></div></div>
    <?php if(empty($_SESSION['username'])): ?>
     </div>
    <?php else: ?> 
    <!-- Create menu based on role -->
 
        <div class="menu" ><br/>
        <?php include 'menu.php';
        echo MenuNav::ConstructMenu();?>
        </div>
    </div>
<?php endif; ?>
<!-- Making the Sign in page-->
<?php if(empty($_SESSION['username'])): ?>
    <div class='wrapper'>
    <br/><br/>
    <p>Please sign in with your account:</p>
     <div id='signi' align="center">
     <br/>
        <a href="signin.php?l=github"><img src='/img/githubSI.png' alt='Sign in with GitHub' width='200px'></a><br/>
        <a href="signin.php?l=google"><img src='/img/googleSI.png' alt='Sign in with Google' width='200px'></a><br/>
        <a href="signin.php?l=linkedin"><img src='/img/linkedinSI.png' alt='Sign in with LinkedIn' width='200px'></a>
    <br/><br/>
    </div>
    </div>

    
    <?php else: ?> 
    
    <!-- If Twitter user signed - Doctor -->
        <?php if($_SESSION['myrole']==2): ?>
            <br/>
            <?php if(isset($_GET['p']) && $_GET['p']!='home' ): ?>
                <?php if($_GET['p']=='therapy'): ?>
                    <div class='therapy'>
                    <?php include 'therapy.php';?>
                    </div>
                <?php elseif($_GET['p']=='data'): ?>
                    <?php include 'data.php'; ?>
                <?php endif; ?>
            <?php else:?>
             <div class='signo'>Welcome Doctor <?php echo $_SESSION['username']; ?>. You are Signed In with LinkedIn.</br> </div>
            <?php endif; ?>

    <!-- If Google user signed - Patient -->
        <?php elseif($_SESSION['myrole']==1): ?>
            <br/>
            <?php if(isset($_GET['p']) && $_GET['p']!='home'): ?>
                <?php if($_GET['p']=='therapy'): ?>
                    <div class='therapy'>
                    <?php include 'therapy.php';?>
                    </div>
                <?php elseif($_GET['p']=='video'): ?>
                    <?php include 'exercise.php'; ?>
                <?php endif; ?>
            <?php else:?>
            <div class='signo'>Welcome Patient <?php echo $_SESSION['username']; ?> . You are Signed In with Google.</br> </div>
            <?php endif; ?>
    <!-- If Github user signed - Researcher -->
        <?php else:?>
            <br/>
            <?php if(isset($_GET['p']) && $_GET['p']!='home' ): ?>
                <?php if($_GET['p']=='news'): ?>
                    <div class='news'>
                    <?php include 'rss.php'; ?>
                    </div>
                <?php elseif($_GET['p']=='data'): ?>
                    <?php include 'data.php'; ?>
                <?php endif; ?>
            <?php else:?>
            <div class='signo'>Welcome Researcher <?php echo $_SESSION['username']; ?> . You are Signed In with Github.</br> </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
    
    



</body> </html>
