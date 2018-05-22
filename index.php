<?php
    session_start();
    
    // MySQLサーバ接続に必要な値を変数に代入
    $username = 'root';
    $password = '';

    // PDO のインスタンスを生成して、MySQLサーバに接続
    $database = new PDO('mysql:host=localhost;dbname=microposts;charset=UTF8;', $username, $password);
    

    if (isset($_SESSION['user_record'],$_SESSION['loginflag'])) {
        // 実行するSQLを作成
        $sql = 'SELECT * FROM microposts.posts';
        $sql2 = 'SELECT * FROM microposts.user';
        
        // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
        $statement = $database->prepare($sql);
        $statement2 = $database->prepare($sql2);
        
        // SQL文を実行する
        $statement->execute();
        $statement2->execute();
        
        // 結果レコード（ステートメントオブジェクト）を配列に変換する
        $records_posts = $statement->fetchAll();
        $records_user = $statement2->fetchAll();
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
<?php
    if (isset($_SESSION['loginflag'])) {
        if ($_SESSION['loginflag']) {
?>
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
                        <a class="navbar-brand" href="/">Microposts</a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="./users.php">Users</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">test <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="./profile-posts.php?user_id=<?php echo $records_user[0]['id'] ?>">My profile</a></li>
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
                <aside class="col-md-4">
                    <form method="POST" action="./database.php" accept-charset="UTF-8">
                        <input name="newpost" type="hidden" value="780rEIcNrcv3LTu0QcC6uanfaittUIcjwAhGaEOq">
                        <div class="form-group">
                            <textarea class="form-control" rows="5" name="postcontent" cols="50"></textarea>
                        </div>
                        <input class="btn btn-primary btn-block" type="submit" value="Post">
                    </form>
                </aside>
                <div class="col-xs-8">
                    <ul class="media-list">
                        <!--投稿一覧　テンプレ開始-->
<?php
    foreach ($records_posts as $value) {
?>
                        <li class="media">
                            <div class="media-left">
                                <!--プロフィール画像は未実装につきコメントアウト-->
                                <!--　<img class="media-object img-rounded" src="https://secure.gravatar.com/avatar/1919913f3489edca7d28f52fb08c8627?s=50&amp;r=g&amp;d=identicon" alt="">-->
                            </div>
                            <div class="media-body">
                                <div>
                                    <a href="./profile-posts.php?user_id=<?php echo $value['user_id']; ?>"><?php echo $value['user_id']; ?></a>
                                    <span class="text-muted">posted at <?php echo ($value['created_at']); ?></span>
                                </div>
                                <div>
                                    <p><?php echo $value['posts_content']; ?></p>
                                </div>
                                <div>
                                    <!--自分の投稿である場合のみ表示する部分テンプレ開始-->
<?php
        if ($_SESSION['user_record'][0]['id'] == $value['user_id']) {
?>
                                    <form method="POST" action="./database.php?delete_id=<?php echo $value['posts_id']; ?>" accept-charset="UTF-8">
                                        <input name="posts_delete" type="hidden" value="DELETE">
                                        <input class="btn btn-danger btn-xs" type="submit" value="Delete">
                                    </form>
<?php
        }
?>
                                    <!--自分の投稿である場合のみ表示する部分テンプレ終了-->
                                </div>
                            </div>
                        </li>
<?php
    }
?>
                        <!--投稿一覧テンプレ終了-->
                    </ul>
                </div>
            </div>
        </div>
    </body>
<?php
    }
}
    else{
?>
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
            <div class="center jumbotron">
                <div class="text-center">
                    <h1>Welcome to the Microposts</h1>
                    <a href="./signup.php" class="btn btn-lg btn-primary">Sign up now!</a>
                </div>
            </div>
        </div>
    </body>
<?php
}
?>
</html>
