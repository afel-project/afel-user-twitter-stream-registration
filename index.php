<!doctype html>
<html>
  <head>
    <title>Register your twitter account into the AFEL platform</title>
    <style>
      * {font-family: Helvetica, Verdana, "Sans Serif"; font-size: 16px}
      #main {width: 70%; margin-left: 15%; margin-top: 5%}
      #form {margin-left: auto; margin-right: auto; margin-top: 20px; margin-bottom: 30px; width: 400px; background: #126567; border-radius: 15px; padding-top: 15px; padding-bottom: 10px;}
      .formfield {width: 80%; margin-left: 10%; margin-top: 10px; margin-bottom: 10px;}
      #username {width: 100%;}
      #password {width: 100%;}
      #twitter {width: 100%; margin-bottom: 10px;}
      #gobutton {width: 80%; margin-bottom: 10px; margin-left: 10%; margin-right: 10%; text-align: center; background: #d34836; padding-left: 10px; padding-right: 10px; padding-top: 5px; padding-bottom: 5px; font-weight: bold; border-radius: 5px; color: white; text-decoration: none;}
      .message{margin-bottom: 30px; width: 80%; margin-left: 10%; padding: 15px 20px 15px 20px; background: #eee; border-radius: 5px}      
      #error{visibility: hidden;
      margin-bottom: 30px; width: 80%; margin-left: 10%; padding: 15px 20px 15px 20px; background: #eee; border-radius: 5px; color: #d34836}      
      h1 {font-size: 20px; margin-left: 10%; margin-bottom: 30px;}
    </style>
  </head>
  <body>
    <div id="main">

<?php
   
   $done = FALSE;
   
   if (isset($_POST["username"]) || isset($_POST["username"]) || isset($_POST["username"])){
   if (isset($_POST["username"]) && strcmp($_POST['username'], '')!==0){
         if (isset($_POST["password"])  && strcmp($_POST['password'], '')!==0){
                if (isset($_POST["twitter"])  && strcmp($_POST['twitter'], '')!==0){
                     $url  = "http://data.afel-project.eu/catalogue/index.php/newuserdataset";
                     $data = "username=".$_POST['username']."&password=".$_POST['password']."&type=AFEL User Twitter Activity&description=Activity data from twitter user ".$_POST['twitter'].".&ecapiconf={}";
                     $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL, $url);
                    curl_setopt($ch,CURLOPT_POST, 5);
                    curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec($ch);
                    $code = curl_getinfo($ch)['http_code'];
                    curl_close($ch);
                    $res = json_decode($result);
                    if (isset($res->error)) {
                         $error = "Error connecting to AFEL Platform - ".$res->error;
                    } else if (!isset($res->key) || !isset($res->dataset)) {
                         $error = "Issue setting up dataset with AFEL Data Platform";
                    } else {
                         $row = $_POST['twitter'].' '.$res->dataset.' '.$res->key.' '.$res->ecapi;
                         file_put_contents('registered', $row, FILE_APPEND | LOCK_EX);
                         $done=TRUE;
                    }
                } else {
                    $error = "Please provide a twitter @name";
                }
         } else {
            $error = "please provide a password";
         }
   } else {
      $error = "please provide a username";
   }
   }
?>

<h1>Register your Twitter account into the AFEL platform</h1>

      <div class="message">
<?php if (!$done) { ?>
      Please provide your login and password on the AFEL platform, as well as your twitter @name to register your twitter activity in the AFEL Data Platform. By registering your twitter @name, you accept the <a href="http://data.afel-project.eu/catalogue/index.php/terms-twitter">Terms and Conditions</a> of this tool  
        and of the AFEL platform.
<?php } else { ?>
      Your twitter account has been registered - thank you!
<?php } ?>

<?php if (!$done) { ?>
 </div>
      <div class="message">
	If you do not yet have an account on the AFEL platform, please <a href="http://data.afel-project.eu/catalogue/wp-login.php?action=register" target="_blank">register</a> 
	first, and come back to this page.
      </div>
      
      <form id="form" action="" method="POST">
	<div class="formfield">
	  <input type="text" name="username" id="username" placeholder="username" />
	</div>
	<div class="formfield">
	  <input type="password" name="password" id="password" placeholder="password" />
	</div>
	<div class="formfield">
	  <input type="input" name="twitter" id="twitter" placeholder="twitter @name" />
	</div>
	<input type="submit" id="gobutton" value="Register Twitter Account" />
      </form>

      <?php if (isset($error)) { ?>
                <div id="error" style="visibility: visible;">
                  <?php echo $error; ?>
      <?php } else { ?>
      <div id="error">
	There should not be any error yet...
      <?php } ?>
      </div>

<?php } ?>

  </body>
</html>




