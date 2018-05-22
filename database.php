<?php
    session_start();
    
    // MySQLサーバ接続に必要な値を変数に代入
    $username = 'root';
    $password = '';

    // PDO のインスタンスを生成して、MySQLサーバに接続
    $database = new PDO('mysql:host=localhost;dbname=microposts;charset=UTF8;', $username, $password);


    //ユーザ登録フォームからの送信であるか
    if (isset($_POST["signup"])) {
        if (isset($_POST['user_id'],$_POST['user_name'],$_POST['user_pass'],$_POST['user_repass'],$_POST['user_email'])) {
                // 実行するSQLを作成
                $user_namesql = 'SELECT * FROM microposts.user WHERE user_id = :userid;';
                $user_emailsql = 'SELECT * FROM microposts.user WHERE user_email = :useremail;';
                
                // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
                $statement1 = $database->prepare($user_namesql);
                $statement1->bindParam(':userid', $_POST["user_id"]);
                
                $statement2 = $database->prepare($user_emailsql);
                $statement2->bindParam(':useremail', $_POST["user_email"]);
                // SQL文を実行する
                $statement1->execute();

                $statement2->execute();
                
                 // 結果レコード（ステートメントオブジェクト）を配列に変換する
                $records1 = $statement1->fetchAll();

                 // 結果レコード（ステートメントオブジェクト）を配列に変換する
                $records2 = $statement2->fetchAll();
                // ステートメントを破棄する
                $statement1 = null;
                $statement2 = null;

            if (empty($records1) && empty($records2)) {
                $pass_repass = strcmp($_POST['user_pass'] ,$_POST['user_repass'] );
                
                if ($pass_repass ==0 ) {
                    // 実行するSQLを作成
                    $sql = 'INSERT INTO microposts.user (user_id,user_name,user_pass,user_email,created_at) values(:user_id,:user_name,:user_pass,:user_email,null);';
                        
                    // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
                    $statement = $database->prepare($sql);
                        
                    // ユーザ入力データ($_POST['user_name'])などをVALUES(?)の?の部分に代入する
                    $statement->bindParam(':user_id', $_POST["user_id"]);
                    $statement->bindParam(':user_name', $_POST["user_name"]);
                    $statement->bindParam(':user_pass', $_POST["user_pass"]);
                    $statement->bindParam(':user_email', $_POST["user_email"]);
                    
                    // SQL文を実行する
                    $statement->execute();
                    
                                    
                    $user_infosql = 'SELECT * FROM microposts.user WHERE user_id = :userid;';
                    $statement2 = $database->prepare($user_infosql);
                    $statement2->bindParam(':userid', $_POST["user_id"]);
                    $statement2->execute();
                    $records3 = $statement2->fetchAll();
                    $_SESSION['login_user_id']=$records3[0]['id'];
                    // ステートメントを破棄する
                    $statement = null;
                     header( "Location:./index.php" ) ;
                }else{
                    echo "入力した２つのパスワードが一致しません。";
                    echo "<a href=\"./signup.php\">登録ページへ戻る</a>";
                }
            }else{
                echo "<p>既に使用されているemail,passのいづれかを入力しているため登録できませんです</p>";
                echo "<a href=\"./signup.php\">登録ページへ戻る</a>";

            }
        }
    }
    
        //ログインフォームからの送信であるか
    if (isset($_POST["login"])) {
        if (isset($_POST['user_pass'],$_POST['user_email'])) {
            // 実行するSQLを作成
            $sql = 'SELECT * FROM microposts.user WHERE user_email = :user_email;';
            
            // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
            $statement = $database->prepare($sql);
            $statement->bindParam(':user_email', $_POST["user_email"]);
            // SQL文を実行する
            $statement->execute();
             // 結果レコード（ステートメントオブジェクト）を配列に変換する
            $records = $statement->fetchAll();
            $pass = ($records[0]['user_pass']);
            $user_id = ($records[0]['user_id']);
             
             if ($pass == $_POST['user_pass']) {
                 $_SESSION['user_record'] = $records;
                $_SESSION['loginflag']= TRUE;
                $_SESSION['login_user_id']=$records[0]['id'];
                header( "Location:./index.php" ) ;
             }else{
                echo "入力されたemailかpasswordが間違っています";
                echo "<a href=\"./login.php\">ログインページへ戻る</a>";
             }
            // ステートメントを破棄する
            $statement = null;
        }
    }
    
    
    if (isset($_POST["newpost"])) {
        if (isset($_POST['postcontent'],$_SESSION['user_record'])) {
            // 実行するSQLを作成
                // 実行するSQLを作成
                $sql = 'INSERT INTO microposts.posts (user_id,posts_content,created_at) values(:user_id,:posts_content,null);';
                    
                // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
                $statement = $database->prepare($sql);
                    
                // ユーザ入力データ($_POST['user_id'])などをVALUES(?)の?の部分に代入する
                $statement->bindParam(':user_id',  $_SESSION['user_record'][0]['id']);
                $statement->bindParam(':posts_content', $_POST["postcontent"]);
                
                // SQL文を実行する
                $statement->execute();
                // ステートメントを破棄する
                $statement = null;
                 header( "Location:./index.php" ) ;
            }else{
                echo "本文を入力してください";
                echo "<a href=\"./index.php\">投稿ページへ戻る</a>";
            }
        // ステートメントを破棄する
        $statement = null;
    }
  
    //投稿削除ボタンからの送信であるか
    if (isset($_POST["posts_delete"])) {
        if (isset($_GET['delete_id'])) {
            // 実行するSQLを作成
            $sql = 'DELETE FROM microposts.posts WHERE posts_id = :posts_id;';
            
            // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
            $statement = $database->prepare($sql);
            $statement->bindParam(':posts_id', $_GET['delete_id']);
            
            // SQL文を実行する
            $statement->execute();
            
            header( "Location:./index.php");
            
            // ステートメントを破棄する
            $statement = null;
        }
    }
  
   
   //ユーザ登録フォームからの送信であるか
    if (isset($_POST["follow"])) {
        if (isset($_GET['follow_id'],$_SESSION['login_user_id'])) {
                // 実行するSQLを作成
                $sql = 'SELECT * FROM microposts.follow WHERE user_id = :userid AND follow_id = :follow_id;';
                
                // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
                $statement = $database->prepare($sql);
     
                $statement->bindParam(':user_id', $_SESSION['login_user_id']);
                $statement->bindParam(':follow_id', $_GET['follow_id']);
     
                // SQL文を実行する
                $statement->execute();
    
                 // 結果レコード（ステートメントオブジェクト）を配列に変換する
                $records = $statement->fetchAll();
                
                // ステートメントを破棄する
                $statement = null;

            if (empty($records)) {
                // 実行するSQLを作成
                $sql = 'INSERT INTO microposts.follow (user_id,follow_id) values(:user_id,:follow_id);';
                    
                // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
                $statement = $database->prepare($sql);
                    
                // ユーザ入力データ($_POST['user_name'])などをVALUES(?)の?の部分に代入する
                $statement->bindParam(':user_id', $_SESSION["login_user_id"]);
                $statement->bindParam(':follow_id', $_GET["follow_id"]);
                
                // SQL文を実行する
                $statement->execute();

                // ステートメントを破棄する
                $statement = null;
                 header( "Location:./index.php" ) ;
            }
        }
    }
 
    //ユーザ登録フォームからの送信であるか
    if (isset($_POST["unfollow"])) {
        if (isset($_GET['unfollow_id'],$_SESSION['login_user_id'])) {
            // 実行するSQLを作成
            $sql = 'DELETE FROM microposts.follow WHERE user_id = :login_user_id AND follow_id = :unfollow_id ;';
                
            // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
            $statement = $database->prepare($sql);
                
            // ユーザ入力データ($_POST['user_name'])などをVALUES(?)の?の部分に代入する
            $statement->bindParam(':login_user_id', $_SESSION["login_user_id"]);
            $statement->bindParam(':unfollow_id', $_GET["unfollow_id"]);
            
            // SQL文を実行する
            $statement->execute();

            // ステートメントを破棄する
            $statement = null;
            header( "Location:./index.php" ) ;
        }
    }
?>