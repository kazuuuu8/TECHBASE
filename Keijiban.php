<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <!-文字化けを防ぐコード->

<head>
  <!-- ビューポートの設定 -->
	<meta name="viewport" content="width=device-width,initial-scale=1">
  <title>K-pop掲示板</title>
</head>


<body>



<div class="header">
	<h1 class="logo-wrapper">
		<img src="logo.jpeg" alt="ロゴ画像" class="logo">
	</h1>
</div>

<br>
<h1>K-popを語る</h1>
<br>

<?php
//submitでPOST送信された場合のみ実行
if($_SERVER["REQUEST_METHOD"] == "POST"){
  //送信されたフォームにより処理を分岐
  $error_name = null;
  $error_comment = null;
  $error_delete_no = null;
  switch($_POST["mode"]){
    case "add":
     //名前・コメント未入力のチェック
     //投稿追加処理
     break;
    case "delete":
     //行番号未入力＆数字じゃない場合のチェック
     //投稿削除処理
     break;
 }
}
?>



<?php

//PDOでDBに接続する
$dsn = '';
$user = '';
$password = '';
$pdo = new PDO($dsn,$user,$password);
$stmt = $pdo -> query('SET NAMES utf8'); //文字化け対策

$sql = "CREATE TABLE kpop"
."("
."id INT AUTO_INCREMENT,"
."name CHAR(32),"
."comment TEXT,"
."times DATETIME,"
."pass INT"
.");";
$stmt = $pdo -> query($sql);
//「kpop」というテーブルに、投稿番号、氏名、コメント、投稿時間、パスワードのカラムを作成
?>

<hr>

<?php

/*
$sql ="SHOW CREATE TABLE kpop;";  //テーブルの中身確認
$result = $pdo -> query($sql);
  foreach($result as $row){
  var_dump($row);
  echo "<hr>";
}
*/

/*
$sql ="SELECT * FROM kpop";  //データの削除
$result = $pdo ->query($sql);
$sql ="DELETE FROM kpop";
$result = $pdo ->query($sql);
*/


header('Content-Type: text/html; charset=UTF-8');


  
  
    //新規投稿
    $name=$_POST["namae"];
	$cmt=$_POST["comment"];
	$times=date('Y/m/j H:i:s');
	$hidden = $_POST["hidden"]; //hiddenの値
	  $log=$name.$cmt ;
	$pass = $_POST["pass"];
    
   //echo "投稿内容の確認"."|".$log.$times."<hr>" ; //投稿内容の確認
    //echo "hiddenデータ確認<<".$hidden."<hr>" ;//
   
   
if (isset($_POST["btn"]) && empty($_POST["hidden"])){
  //ボタンが押された時　かつ　hiddenがないときに投稿する  
  

	   
	  $stmt = $pdo -> prepare("INSERT INTO kpop(name,comment,times,pass) VALUES(:name,:comment,:times,:pass);");
	  $stmt ->bindParam(":name",$name,PDO::PARAM_STR);
	  $stmt ->bindParam(":comment",$cmt,PDO::PARAM_STR);
	  $stmt ->bindParam(":times",$times,PDO::PARAM_STR);
	  $stmt ->bindParam(":pass",$pass,PDO::PARAM_INT);
	  $stmt -> execute();

	  //echo "hiddenデータ確認>>".$hidden."<hr>" ;//
	  
	  }else{
   }



//削除方法
 if(isset($_POST["deleteNo"]) && isset($_POST["delpass"])) {
 //削除番号と削除パスワードが入力された場合
 
    $delNo = $_POST["deleteNo"];
	$delpass = $_POST["delpass"];
 
   $sql = "SELECT * FROM kpop;";
    $result = $pdo ->query($sql);
   $stmt = $pdo -> query("SELECT * FROM kpop WHERE id = '$delNo' AND pass='$delpass' ");
	$results = $stmt -> fetch(PDO::FETCH_ASSOC);//結果オブジェクトを配列として取得
   
	
	
	//echo "削除番号テスト--".$delNo."---".$delpass."<br>";
   if($results['pass']==$delpass ){
   //削除パスと削除番号が一致したら削除
   $sql = "DELETE FROM kpop WHERE id = $delNo;"; //削除番号とnumが一致したのを削除
   $result = $pdo ->query($sql);
   echo "投稿は削除されました。"."<hr>";
   
   //idを一旦削除し、再度番号を振り直す
   $sql= $pdo ->query("ALTER TABLE kpop DROP COLUMN id");
   $sql= $pdo ->query("ALTER TABLE kpop ADD id INT(11) primary key NOT NULL AUTO_INCREMENT first;");
   $sql= $pdo ->query("ALTER TABLE kpop AUTO_INCREMENT=1");
   
   
      }else{
      echo "正しいパスワードを入力してください。"."<hr>";
      }
}



//編集方法

if (isset($_POST["editNo"]) && isset($_POST["edipass"])){
//編集対象番号とパスワードが入力されたら

//投稿フォームに表示
$filedatas = file("kpop.txt");
$editNo =  $_POST["editNo"];  //編集対象番号
$edipass = $_POST["edipass"]; //編集パスワード

  $sql = "SELECT * FROM kpop;";
    $result = $pdo -> query($sql);
  $stmt = $pdo -> query("SELECT * FROM kpop WHERE id = '$editNo' AND pass = '$edipass'"); 
  $stm = $stmt -> fetch(PDO::FETCH_ASSOC);//結果オブジェクトを配列として取得
  
  //echo "編集番号テスト--".$editNo."--".$edipass."<hr>";//
  
  If($stm['pass']==$edipass){
  //編集対象番号のパスワードが一致したところ
  $sql ="SELECT * FROM kpop WHERE id = $editNo"; //編集対象番号の行を取得
  $result = $pdo -> query($sql);
  
     foreach($result as $row){
	   $hidden = $row[id];
       $namae = $row[name];
       $comment = $row[comment];
       $ediPass = $row[pass];
       }
  }else{
  echo "正しいパスワードを入力してください。"."<hr>";
  }
}


//編集モードで投稿する
if (isset($_POST["btn"]) && (!empty($_POST["hidden"]))) {
//送信ボタンが押され、hiddenの数値があるとき


$stm = $pdo ->prepare("SELECT * FROM kpop WHERE id = $hidden ;");//番号取得
 $stm ->execute(); 
$result = $stm -> fetch(PDO::FETCH_ASSOC);//結果オブジェクトを配列として取得
//echo "hiddenデータ確認".$hidden."<br>" ;//
//echo "hiddenデータ確認--".$result['id']."<hr>";//

 if($result['id']==$hidden){
//編集対象番号とhiddenが一致したら
 
 $sql ="UPDATE kpop SET name='$name',comment='$cmt',times='$times',pass='$pass' WHERE id='$hidden'";
 $result = $pdo ->query($sql);
 echo "投稿は編集されました。"."<hr>";
 
}
}

//ブラウザへの最終表示
$sql = "SELECT * FROM kpop;";
$result = $pdo -> query($sql);

  foreach($result as $row){
	echo "投稿番号".$row[id]."<br>";
	echo "By:".$row[name]."<br>";
	echo $row[comment]."<br>";
	echo "投稿時間:".$row[times]."<br>";
	  //echo "パスワード:".$row[pass]."<br>";  //確認
	echo "<hr>";
}

?>



<!-投稿フォーム-!>

<form action="Kadai.php" method="post">
  <table>
	<tr>
	 <td>名前:</td>
	 <input type="hidden" name="hidden" value="<?php echo $hidden; ?>">
	 <td><input type="text" name="namae" value="<?php echo $namae; ?>"></td>
	</tr>
	<tr>
	 <td>コメント:</td>
	 <td><textarea name="comment" rows="4" cols="40" ><?php echo $comment; ?></textarea></td>
	</tr>
	<tr>
	 <td>パスワード:</td>
	 <td><input type="text" name="pass" size="10" value="<?php echo $ediPass; ?>" >(半角英数字のみ)</td>
	</tr>
	<tr>
	 <td></td>
	 <td><input type="submit" name="btn" value="上記内容で送信する"></td>
	</tr>
  </table>
</form>

<br>
<?php echo "<---削除or編集の際は、番号とパスワードを入力してください--->" ;?>
<br>


<form action="Kadai.php" method="post"> 
削除対象番号: <input type="text" name="deleteNo" size="5"> 
<br>
<?php echo $error_delete_no ; ?>
パスワード: <input type="text" name="delpass" size="10">
 <input type="submit" name="delete" value="削除"> 
 </form>
 
 
<form action="Kadai.php" method="post">
編集対象番号： <input type="text" name="editNo" size="5">
<br>
<?php echo $error_edit_no ; ?>
パスワード: <input type="text" name="edipass" size="10">
 <input type="submit" name="edit" value="編集">
 </form>
 

 <br>
 <br>
  
 <hr>


</body>



</html>