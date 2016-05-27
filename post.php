<?php
/**
 * Posts data to social media site.
 *
 * @package: social_post.
 * @author: Ashiqur Rahman
 * @link: http://ghumkumar.com
 */

session_start();
$fb_access_token = isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token'] : false;
if(!$fb_access_token || !isset($_POST['post_content']) || !isset($_FILES["post_image"])) {
    header("Location: index.php");
    exit;
}

include "libraries/Facebook/autoload.php";

$fb = new Facebook\Facebook([
    'app_id' => 'APP_ID',
    'app_secret' => 'SECRET',
    'default_graph_version' => 'v2.5',
]);

$error = '';

$target = 'tmp_uploads/';
$target_file = $target . $_FILES['post_image']['name'];
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
$check = getimagesize($_FILES["post_image"]["tmp_name"]);

if(!$check) {
    $error[] = 'Uploaded file is not an image!';
}

if($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif') {
    $error[] = 'Invalid image type. Please upload jpg, jpeg, gif or png only.';
}

if(empty($error)) {

    if(!move_uploaded_file($_FILES['post_image']['tmp_name'], $target_file)) {
        $error[] = 'Could not upload file!';
    }
}

if(empty($error)) {
    $data = [
        'message' => $_POST['post_content'],
        'source' => $fb->fileToUpload($target_file)
    ];
}


try {
    $response = $fb->post('/me/photos', $data, $fb_access_token);
    $graphNode = $response->getGraphNode();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    $error[] = 'Graph returned an error: ' . $e->getMessage();
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    $error[] = 'Facebook SDK returned an error: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="Page Description">
        <meta name="author" content="Ghumkumar">
        <title>Social Share</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="https://use.fontawesome.com/9bd57a12fb.js"></script>
    </head>
    <body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>Social Share</h1>
            </div>
        </div>
        <div class="row">
            <?php
            if(!empty($error)):
                foreach($error as $e):
        ?>
                    <div class="alert alert-danger">
                    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    	<strong>Error!</strong> <?php echo $e; ?>
                    </div>
        <?php
                endforeach;
            else:
        ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>Success!</strong> Uploaded file to Facebook.<br />
                <?php if(isset($graphNode['id'])) : ?>
                    <i class="fa fa-facebook"></i> Photo ID: <?php echo  $graphNode['id']; ?>
                <?php endif; ?>
            </div>
        <?php
            endif;
            ?>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>