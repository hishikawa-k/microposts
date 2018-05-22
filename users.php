<?php
    session_start();
    // MySQLサーバ接続に必要な値を変数に代入
    $username = 'root';
    $password = '';
    // PDO のインスタンスを生成して、MySQLサーバに接続
    $database = new PDO('mysql:host=localhost;dbname=microposts;charset=UTF8;', $username, $password);

    if (isset($_SESSION['loginflag'])) {
        // 実行するSQLを作成
        $sql = 'SELECT * FROM microposts.user';
        $statement = $database->prepare($sql);
        $statement->execute();
        // 結果レコード（ステートメントオブジェクト）を配列に変換する
        $records_user = $statement->fetchAll();
    }else{
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
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">koko <span class="caret"></span></a>
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
            <ul class="media-list">
                <!--ユーザ情報のテンプレート開始ーーーーーーー-->
<?php
    foreach ($records_user as $value) {
?>
                <li class="media">
                    <div class="media-left">
                        <!--プロフィール画像は未実装のためコメントアウト-->
                        <!--<img class="media-object img-rounded" src="https://secure.gravatar.com/avatar/e79e636c493e13e803ace5afcddb87a5?s=50&amp;r=g&amp;d=identicon" alt="">-->
                    </div>
                    <div class="media-body">
                        <div>
                            <?php echo $value['user_name']; ?>
                        </div>
                        <div>
                            <p><a href="./profile-posts.php?user_id=<?php echo$value['id']  ?>">View profile</a></p>
                        </div>
                    </div>
                </li>
            </ul>
<?php
    }
?>
            <!--ユーザ情報のテンプレート終了-->
        </div>
    </body>
</html>
