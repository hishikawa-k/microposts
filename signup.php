
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Microposts</title>
        
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-inverse navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="./index.php">Microposts</a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="./signup.php">Signup</a></li>
                            <li><a href="./login.php">Login</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>        
        <div class="container">
            <div class="text-center">
                <h1>Sign up</h1>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <form method="POST" action="./database.php" accept-charset="UTF-8">
                        <input name="signup" type="hidden" value="qiSb6TMydWkQTL4Li1fUSo9VPRRGkXpBGSsZwOym">
                        <div class="form-group">
                            <label for="user_name">Name</label>
                            <input class="form-control" name="user_name" type="text" id="name">
                        </div>
                        <div class="form-group">
                            <label for="user_id">UserId</label>
                            <input class="form-control" name="user_id" type="text" id="name">
                        </div>
                        <div class="form-group">
                            <label for="user_email">Email</label>
                            <input class="form-control" name="user_email" type="email" id="email">
                        </div>
                
                        <div class="form-group">
                            <label for="user_pass">Password</label>
                            <input class="form-control" name="user_pass" type="password" value="" id="password">
                        </div>
                
                        <div class="form-group">
                            <label for="user_repass">Confirmation</label>
                            <input class="form-control" name="user_repass" type="password" value="" id="password">
                        </div>
                
                        <input class="btn btn-primary btn-block" type="submit" value="Sign up">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
