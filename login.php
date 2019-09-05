<?php
 
error_reporting(E_ALL); //E_STRICTレベル以外のエラーを報告する
ini_set('display_errors','On'); //画面にエラーを表示させるか
 
//post送信されていた場合
if(!empty($_POST)){
 
  //バリデーションは省略
 
  //変数にユーザー情報を代入
  $email = $_POST['email'];
  $pass = $_POST['pass'];
 
  //DBへの接続準備
  $dsn = 'mysql:dbname=php_sample01;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $options = array(
          // SQL実行失敗時に例外をスロー
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          // デフォルトフェッチモードを連想配列形式に設定
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
          // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
          PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
      );
 
  // PDOオブジェクト生成（DBへ接続）
  $dbh = new PDO($dsn, $user, $password, $options);
 
  //SQL文（クエリー作成）
  $stmt = $dbh->prepare('SELECT * FROM users WHERE email = :email AND pass = :pass');
 
  //プレースホルダに値をセットし、SQL文を実行
  $stmt->execute(array(':email' => $email, ':pass' => $pass));
 
  $result = 0;
 
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
 
  //結果が０でない場合
  if(!empty($result)){
 
    //SESSION（セッション）を使うにsession_start()を呼び出す
    session_start();
 
    //SESSION['login']に値を代入
    $_SESSION['login'] = true;
 
    //マイページへ遷移
    header("Location:mypage.php"); //headerメソッドは、このメソッドを実行する前にechoなど画面出力処理を行っているとエラーになる。
 
  }
}
 
?>
 
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ホームページのタイトル</title>
    <style>
      body{
        margin: 0 auto;
        padding: 150px;
        width: 25%;
        background: #fbfbfa;
      }
      h1{ color: #545454; font-size: 20px;}
      form{
        overflow: hidden;
      }
      input[type="text"]{
        color: #545454;
        height: 60px;
        width: 100%;
        padding: 5px 10px;
        font-size: 16px;
        display: block;
        margin-bottom: 10px;
        box-sizing: border-box;
      }
      input[type="password"]{
        color: #545454;
        height: 60px;
        width: 100%;
        padding: 5px 10px;
        font-size: 16px;
        display: block;
        margin-bottom: 10px;
        box-sizing: border-box;
      }
      input[type="submit"]{
        border: none;
        padding: 15px 30px;
        margin-bottom: 15px;
        background: #3d3938;
        color: white;
        float: right;
      }
      input[type="submit"]:hover{
        background: #111;
        cursor: pointer;
      }
      a{
        color: #545454;
        display: block;
      }
      a:hover{
        text-decoration: none;
      }
      .err_msg{
        color: #ff4d4b;
      }
    </style>
  </head>
  <body>
 
      <h1>ログイン</h1>
      <form method="post">
 
        <input type="text" name="email" placeholder="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email'];?>">
 
        <input type="password" name="pass" placeholder="パスワード" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass'];?>">
 
        <input type="submit" value="送信">
 
      </form>
 
  </body>
</html>