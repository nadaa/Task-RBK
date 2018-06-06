<?php
require_once 'config.php';
// Initialize the session
session_start();
 
$logged_user=$_SESSION['username'];

// If session doesn't exist, it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}
//get the loggedin user info
$sql = mysqli_query($link,"SELECT id FROM users WHERE username = '".$logged_user."'");
$res = mysqli_fetch_array($sql);
$uid= $res['id'];
 
// Initialize message variable
 $msg = "";
  // If upload button is clicked ...
  if (isset($_POST['upload'])) {
    // Get image name
    $image = $_FILES['image']['name'];
    // Get text
    $image_text = mysqli_real_escape_string($link, $_POST['image_text']);
    // image file directory
    $target = "images/".basename($image);
    $sql = "INSERT INTO images (image_url, des,user_id) VALUES ('$image', '$image_text','$uid')";
    // execute query
    mysqli_query($link, $sql);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $msg = "Image uploaded successfully";
    }else{
        $msg = "Failed to upload image";
    }
  }
  $result = mysqli_query($link, "SELECT * FROM images");
    //mysqli_close($link);

    if (isset($_POST['delete'])) {
      $image_del=$_POST['delete'][0];

      //get image_url of the selected image
      $sql = mysqli_query($link,"SELECT * FROM images WHERE id = '".$image_del."'");
      $res = mysqli_fetch_array($sql);
      $selected_img= $res['image_url'];
      $img_path= $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/'.'images/'.$selected_img;

      //delete the selected image from the db
      $sql = mysqli_query($link,"DELETE FROM images WHERE id ='".$image_del."'");   
      mysqli_query($link, $sql);

        //delete from the fs
      unlink($img_path);   
    }

else if (isset($_POST['showurl'])) {
    echo 'showurl button';
      $image_del=$_POST['showurl'][0];
        // deletebooking(intval($_POST['delete']));
        // delete the image from the filesystem and from the db
        $sql = mysqli_query($link,"SELECT * FROM images WHERE id = '".$image_del."'");
      $res = mysqli_fetch_array($sql);
      $selected_img= $res['image_url'];
      $img_path= $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/'.'images/'.$selected_img;

       echo "<script type='text/javascript'>alert('$img_path');</script>";
       
    }

?>
<html>
<head>
<title>Image Upload</title>
<link rel = "stylesheet"
   type = "text/css"
   href = "styles.css" />
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
<!-- <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script> -->
</head>
<body style="background-color: #E8E4D2">
<h1 style="text-align: center"> Image Uploader App</h1>
<div id="content">
<p><a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a></p>

  <div id='upload'>
  <form method="POST" action="index.php" enctype="multipart/form-data">
    <input type="hidden" name="size" value="1000000">
    <div>
      <input type="file" name="image">
    </div>
    <div>
      <textarea 
        id="text" 
        cols="40" 
        rows="2" 
        name="image_text" 
        placeholder="image description"></textarea>
    </div>
    <div>
        <button type="submit" name="upload">Upload</button>
    </div>
  </form>
</div>
<div id='imgs'>
  <?php
 // $image url= $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']);
    while ($row = mysqli_fetch_array($result)) {
      //echo "<div id='img-del'>";
       echo "<div id='img_div'>";
       echo "<img class='img-responsive' src='images/".$row['image_url']."' >";
       echo "<p>".$row['des']."</p>";
       echo "</div>";
       echo"<div >";
      echo '<form method="POST" action="index.php" enctype="multipart/form-data"><input  type="hidden" name="delete" value="'.$row["id"].'"/><input type="submit" value="delete"/> <input type="submit" value="showurl"/></form>';
       echo "</div>";
       // echo $row['id'];
    }
  ?>
</div>
</div>
</body>
</html>