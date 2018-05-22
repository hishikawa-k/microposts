<?php
    session_start();

    // MySQLサーバ接続に必要な値を変数に代入
    $username = 'root';
    $password = '';
    $mypageflag = FALSE;
    $followflag = FALSE;
    // PDO のインスタンスを生成して、MySQLサーバに接続
    $database = new PDO('mysql:host=localhost;dbname=microposts;charset=UTF8;', $username, $password);

    if (isset($_GET['user_id'],$_SESSION['login_user_id'])) {
        if ($_GET['user_id']==$_SESSION['login_user_id']) {
            $mypageflag = TRUE;
        }
        // 実行するSQLを作成
        $sql = 'SELECT * FROM microposts.posts WHERE user_id = :user_id';
        $sql2 = 'SELECT * FROM microposts.user WHERE id = :user_id';
        $followflagsql = 'SELECT * FROM microposts.follow WHERE user_id = :login_user_id AND follow_id = :follow_id';
        
        // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
        $statement = $database->prepare($sql);
        $statement2 = $database->prepare($sql2);
        $followflagstatement = $database->prepare($followflagsql);
       
        // ユーザ入力データ($_POST['user_id'])などをVALUES(?)の?の部分に代入する
        $statement->bindParam(':user_id',  $_GET['user_id']);
        $statement2->bindParam(':user_id',  $_GET['user_id']);
        $followflagstatement->bindParam(':login_user_id',  $_SESSION['login_user_id']);
        $followflagstatement->bindParam(':follow_id',  $_GET['user_id']);

        // SQL文を実行する
        $statement->execute();
        $statement2->execute();
        $followflagstatement->execute();
      
        // 結果レコード（ステートメントオブジェクト）を配列に変換する
        $records_posts = $statement->fetchAll();
        $records_user = $statement2->fetchAll();
        $records_followflag = $followflagstatement->fetchAll();
        if (!empty($records_followflag)) {
             $followflag = TRUE;  
        }
        $statement1 = null;
        $statement2 = null;
        $followflagstatement = null;
    }
    else{
        header( "Location:./index.php" ) ;
    }
?>


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
                            <li><a href="./users.php">Users</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['user_record'][0]['user_name']; ?>
                                <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="./profile-posts.php?user_id=<?php echo $_SESSION['login_user_id']; ?>">My profile</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="./logout.php">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>   
        
        <div class="container">
            <div class="row">
                <aside class="col-xs-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $records_user[0]['user_name']; ?></h3>
                        </div>
                        <div class="panel-body">
                            <!--<img class="media-object img-rounded img-responsive" src="https://secure.gravatar.com/avatar/8e892dc8a07194cd897450e2fcf69875?s=500&amp;r=g&amp;d=identicon" alt="">-->
<?php
                                    if (!$mypageflag) {
                                        if (!$followflag) {
?>
                                        <form method="POST" action="database.php?follow_id=<?php echo$_GET['user_id']; ?>" accept-charset="UTF-8">
                                            <input name="follow" type="hidden" value="Z2zb6j3Re8ZnjPt2Qx2SP3mhcCdmuqL30Lbt1k7C">
                                            <input class="btn btn-primary btn-block" type="submit" value="Follow">
                                        </form>
                                        
<?php
                                        }else{
?>
                                        <form method="POST" action="database.php?unfollow_id=<?php echo$_GET['user_id']; ?>" accept-charset="UTF-8">
                                            <input name="unfollow" type="hidden" value="b2nBNIFiZlkQaIW6sHSWKbTmUelsVdnhFl6qS3tW">
                                            <input class="btn btn-danger btn-block" type="submit" value="Unfollow">
                                        </form>
<?php                                        
                                    }
                                }
?>                        </div>
                    </div>
                </aside>
                <div class="col-xs-8">
                    <ul class="nav nav-tabs nav-justified">
                        <li role="presentation" class="active"><a href="./profile-follows.php?user_id=<?php echo $_GET['user_id']; ?>">Microposts <span class="badge"><?php echo count($records_posts); ?></span></a></li>
                        <li role="presentation" class=""><a href="./profile-follows.php?follow_id=<?php echo $_GET['user_id']; ?>">Followings <span class="badge"></span></a></li>
                        <li role="presentation" class=""><a href="./profile-followers.php?user_id=<?php echo $_GET['user_id']; ?>">Followers <span class="badge"></span></a></li>
                    </ul>
                    
                    <!--自分のツイート一覧のテンプレート開始-->
                    <ul class="media-list" id="myposts">
<?php
    foreach ($records_posts as $value) {
?>
                        <li class="media">
                            <div class="media-left">
                                <!--プロフィール画像は未実装のためコメントアウト-->
                                <!--<img class="media-object img-rounded" src="https://secure.gravatar.com/avatar/8e892dc8a07194cd897450e2fcf69875?s=50&amp;r=g&amp;d=identicon" alt="">-->
                            </div>
                            <div class="media-body">
                                <div>
                                    <a href="./profile-posts.php?user_id=<?php echo $value['user_id']; ?>"><?php echo $records_user[0]['user_name']; ?></a>
                                    <span class="text-muted">posted at <?php echo $value['created_at']; ?></span>
                                </div>
                                <div>
                                    <p><?php echo $value['posts_content']; ?></p>
                                </div>
                                <div>
                                    <form method="POST" action="./database.php?delete_id=<?php echo $value['posts_id']; ?>" accept-charset="UTF-8">
                                        <input name="posts_delete" type="hidden" value="DELETE">
                                        <input class="btn btn-danger btn-xs" type="submit" value="Delete">
                                    </form>
                                </div>
                            </div>
                        </li>
<?php
    }
?>
                    </ul>
                    <!--自分のツイート一覧のテンプレート終了-->
                    
                </div>
            </div>
        </div>
    </body>
</html>
