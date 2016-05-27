<?php
    /**
     * Include social libraries and create objects.
     *
     * @author Ashiqur Rahman
     * @author_url http://ghumkumar.com
     **/
    session_start();
    $fb_access_token = isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token'] : false;
    include "libraries/Facebook/autoload.php";

    $fb = new Facebook\Facebook([
        'app_id' => 'APP_ID',
        'app_secret' => 'SECRET',
        'default_graph_version' => 'v2.5',
    ]);
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
        <!-- Latest compiled and minified CSS -->
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
                <div class="col-sm-12">
                    <?php
                    if(!$fb_access_token):
                        $helper = $fb->getRedirectLoginHelper();
                        $permissions = ['publish_actions']; // optional
                        $loginUrl = $helper->getLoginUrl('http://localhost/social_post/fb-login-callback.php', $permissions);
                    ?>
                    <div class="alert alert-danger">
                    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    	<strong><i class="fa fa-fw fa-facebook"></i> Facebook Connection!</strong> <a href="<?php echo $loginUrl; ?>">You need to connect with Facebook</a>
                    </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <form action="post.php" method="post" role="form" enctype="multipart/form-data">
                        <legend>What's on your mind?</legend>

                        <div class="form-group">
                            <label for="post_content">Post content</label>
                            <textarea name="post_content" id="post_content" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="post_image">Upload file</label>
                            <input type="file" name="post_image" id="post_image" class="form-control" />
                        </div>

                        <?php
                        if($fb_access_token):
                        ?>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="fb" checked /> <i class="fa fa-fw fa-facebook"></i> Facebook
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        <?php
                        endif;
                        ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <!-- Latest compiled and minified JS -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </body>
</html>