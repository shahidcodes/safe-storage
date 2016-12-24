<?php
require_once 'core/init.php';
$log = new Log(false);
$user = new User();
$toasts = "[";
// file upload
if( isset($_FILES["InputFile"]) && Input::exists() ){
    $encryptor = new Encryption();
    $already = $encryptor->is_already();
    if(!$already){
        if($encryptor->encrypt(Input::get("PrivateKey"))){
            $toasts .= "'Successfully Uploaded',";
        }else{
          $toasts .= "'Problem In Uploading. Code: Ind13',";
        }
    }else{
        $toasts .= "'File Already Exists',";
        // echo $already;
        // TODO == Show existing file
    }
}
$toasts .= ']';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Secure Storage</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="theme-color" content="#00695c">
	<!-- Etc Meta-->
	<meta name="description" content="Keep your personal file safe" />
	<meta name="author" content="Shahid Kamal">
	<meta name="keywords" content="safe,storage,sh4hidkh4n,shahidkh4n" />
	<meta name="robots" content="index, follow" />
	<!-- <link rel="stylesheet" type="text/css" href="includes/css/materialize.min.css"> -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
	<!-- <script type="text/javascript" src="includes/js/jquery.js"></script> -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style type="text/css">
    #showImage{
      width: 80%;
      max-height: 90%;
    }
  </style>
  <script type="text/javascript">
    $(function() {
      var toasts = <?=$toasts?>;
      for (var i = 0; i < toasts.length; i++) {
        Materialize.toast(toasts[i], 2000, 'rounded');
      }
      /*Modal*/
      $('.modal').modal();
      /*/Modal*/
      $(".showFileLink").on("click", function() {
        password = prompt("Enter Password");
        filename = $(this).attr("data-file");
        console.log(filename);
        src = "decrypt.php?password=" + password + "&file=" + filename;
        $("#image").attr("src", src);
        $("#showImage").modal("open");
      });
      // show tooltip uploading
      $("#upload_btn").on("click", function() {
        Materialize.toast("Uploading...", 4000);
      });

      $("#fab_add").on("click", function() {
        $("#upload").modal("open");
      });
      //grab # anchor to show toast
      toastName = location.href.split("#")
      if (toastName.length > 1) {
      	switch(toastName[1]){
      		case "viewfiles":
      			Materialize.toast("Listing Files");
      			break;
      		default:
      			break;
      	}
      }
      // refresh when view files button clicked
      $("#viewFilesBtn").on("click", function() {
      	location.reload();
      });
    });
  </script>
</head>
<body>
<nav class="teal darken-3">
    <div class="nav-wrapper">
      <a href="#" class="brand-logo">Secure Storage</a>
      <?php
      if($user->isLogged()):
      ?>
      <?php $log->setLog("User Logged In", __LINE__) ?>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="#upload">Upload</a></li>
        <li><a id="viewFilesBtn" href="#viewfiles">View Files</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
  <?php endif; ?>
    </div>
</nav>
<div class="container">
<?php
if (!$user->isLogged()) {
  $log->setLog("Not Logged", __LINE__);
if (Input::exists()) {
  if (Token::check(Input::get('token')) && Captcha::check(Input::get('captcha'))) { //checking if token exists
    $log->setLog("Token.Captcha Check OK!", __LINE__);
  	$validate = new Validate();
  	$validation = $validate->check($_POST, array(
  		'username' => array('required'	=> true),
      'password' => array('required'=> true,'min'=> '6')
  		));
  	if ($validation->passed()) { //method chaining
      $log->setLog("validation passed", __LINE__);
  		$user = new User();
      $remember = (Input::get('remember') === 'on') ? true : false;
  		$login = $user->login(Input::get('username'), Input::get('password'), $remember);
  		if ($login) {
  			Redirect::to('index.php');
  		}else{
        Session::flash('msg', "Username Password Incorrect!");
      }
    }else{
      echo "[+] Validation Failed!";
  		// Session::flash('msg', $validation->errors());
  	}
  }else if(Captcha::error()){
    echo "[+] Captcha error";
  	// Session::flash('msg', 'Captcha Mismatch');
  }else{
    $tokenCheck = (bool)Token::check(Input::get('token'));
    $catchaCheck = (bool)Captcha::check(Input::get('captcha'));
    $log->setLog("Unknown Error, TokenCheck: ".$tokenCheck. " CaptchaCheck:".$catchaCheck, __LINE__);
  }
}
echo Session::flash("msg");
?>
<!-- Login Form -->
<form action="" method="POST" class="col s12">
	<div class="input-field">
		<input type="text" name="username" />
		<label for="username">Username</label>
	</div>
	<div class="input-field">
		<input type="text" name="password" />
		<label for="username">Password</label>
	</div>
  <?php Session::put('secure', rand(1000, 9999)); ?>
    <div class="form-group">
    <label for="captcha">Captcha: </label>
    <img src="cap.php"><?php echo Session::flash('errorCap'); ?>
    <input type="text" name="captcha" class="form-control" value="">
    </div>
  <input type="hidden" name="token" value="<?=Token::generate() ?>">
  <div class="switch">
    <label>
      Remember: Off
      <input type="checkbox" name="remember">
      <span class="lever"></span>
      On
    </label>
  </div>
	<input type="submit" class="btn waves-effect waves-teal" />
</form>
<!-- /LoginForm -->
</div> <!-- /container -->
<?php
}else{
	?>
<!-- CardPanel -->  
<div class="card-panel teal text-darken-2">
	<ul class="collection with-header">
        <li class="collection-header"><h4>We Have These Files Secured!</h4></li>
        <?php
        $files = new Files();
        $allFiles = $files->getAllFiles();
        foreach ($allFiles as $file) {
          echo "<li class=\"collection-item\">
                <a class='showFileLink' data-file='$file->checksum' class='list-group-item'>$file->filename</a>
                </li>"; 
        }
        ?>
        </li>
    </ul>
</div>
<!-- /CardPanel -->
</div><!-- /Container -->
<!-- Modal Upload -->
<div id="upload" class="modal">
    <div class="modal-content">

      <h4>Upload Your Image</h4>
      <form action="" method="POST" enctype="multipart/form-data">
      <div class="input-field col s12">
          <input id="password" type="password" name="PrivateKey" />
          <label for="password">Password</label>
        </div>
      <div class="file-field input-field">
        <div class="btn">
          <span>File</span>
          <input type="file" name="InputFile">
        </div>
        <div class="file-path-wrapper">
          <input class="file-path" type="text">
        </div>
      </div>
      <input type="submit" id="upload_btn" class=" modal-action modal-close waves-effect waves-green btn red" value="Upload" />
    </div>
    <div class="modal-footer">
      <a class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
    </form>
</div>
<!-- /Modal Upload -->
<!-- ImageView Modal -->
<div id="showImage" class="modal modal-fixed-footer" style="width: 900;height: 480;">
    <div class="modal-content">
      <h4>Image:</h4>
      <img src="#" id="image" width="780" height="460" />
    </div>
    <div class="modal-footer">
      <a href="#!" id="" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
</div>
<!-- ImageView Modal -->
<!-- FAB -->
<div class="fixed-action-btn click-to-toggle">
	<a class="btn-floating btn-large red">
	  <i class="large material-icons">mode_edit</i>
	</a>
	<ul>
	  <li><a id="fab_add" class="btn-floating red"><i class="material-icons">add</i></a></li>
	  <li><a class="btn-floating yellow darken-1"><i class="material-icons">delete</i></a></li>
	</ul>
</div>
<!-- /FAB -->
<?php } ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>
<!-- <script type="text/javascript" src="includes/js/materialize.min.js"></script>-->
<?php
$log->getLog();
?>
</body>
</html>