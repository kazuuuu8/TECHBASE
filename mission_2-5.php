<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <!-文字化けを防ぐコード->

<head>
  <!-- ビューポートの設定 -->
	<meta name="viewport" content="width=device-width,initial-scale=1">
  <title>K-popを語る</title>
</head>


<body>

<div class="header">
	<h1 class="logo-wrapper">
		<img src="logo.jpeg" alt="ロゴ画像" class="logo">
	</h1>
</div>

<br>
<h1>K-popを語ろう</h1>
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
header('Content-Type: text/html; charset=UTF-8');

   $filename="kpop.txt";
  

    //投稿
    $name=$_POST["namae"];
	$cmt=$_POST["comment"];
     $log=$name.$cmt ;
	$times=date('Y/m/j H:i:s');
     
   $fp=fopen($filename, 'a');
if(isset($_POST["btn"]) && !isset($_POST["id"])){

      $lines = file($filename);
	  $num=count($lines);
	  $num+= 1 ;
       $logs= $num."<>".$name."<>".$cmt."<>".$times ;
	  
	   fwrite($fp, $logs."\n");
	   
	  }else{
	  }
	 fclose($fp);
	 
	 //削除
if (isset($_POST["deleteNo"])) {
    $delete = $_POST["deleteNo"];
    $delCon = file("kpop.txt");

    $fo = fopen("kpop.txt", "w");
    for ($j = 0; $j < count($delCon); $j++) {
        $delDate = explode("<>", $delCon[$j]);
        if ($delDate[0] != $delete) {
            fwrite($fo, $delCon[$j]);
        } else {

            $a=count(file($filename));
			$a += 1;
             fwrite($fo, $a."<>"."削除されました。"."\n");
        }
    }
    fclose($fo);
}

?>



<?php  //編集
if(isset($_POST["btn"]) && isset($_POST["id"])) {
  $contents = file("kpop.txt");
  $fp1 = fopen('kpop.txt','w');
  $editNo =  $_POST["id"];
  foreach($contents as $content) {
	$parts = explode("<>", $content);
    if($parts[0] == $editNo){
      $Name = $_POST["namae"];
      $Comment = $_POST["comment"];
      $Times=date('Y/m/j H:i:s');
	  $Logs= $editNo ."<>".$Name."<>".$Comment."<>".$Times ;
      fwrite($fp1, $Logs."\n");
    } else {
      fwrite($fp1, "$content");
    }
  }
  fclose($fp1);
}
?>


<?php
// 投稿表示
 $arrays=file($filename);
 foreach($arrays as $array)
{
  //配列としてデータを組み込む
  $ray=explode("<>",$array);
  //<>で区切ってデータを取得
  
echo $array ."<br>"."<br>";
 //改行しながら値を表示
}

?>

<br>
<hr>

<!-投稿フォーム-!>

<form action="mission_2-5.php" method="post">
 <input type="hidden" name="id" value="<?=$ray[0]?>">
  <table>
	<tr>
	 <td>名前:</td>
	 <td><input type="text" name="namae" value="<?=$ray[1]?>"></td>
	</tr>
	<tr>
	 <td>コメント:</td>
	 <td><textarea name="comment" rows="4" cols="40"></textarea></td>
	</tr>
	<tr>
	 <td></td>
	 <td><input type="submit" name="btn" value="上記内容で送信する"></td>
	</tr>
  </table>
</form>

<form action="mission_2-5.php" method="post"> 
削除対象番号: <input type="text" name="deleteNo" size="5"> 
<?php echo $error_delete_no ?>
 <input type="submit" name="delete" value="削除"> 
<input type="hidden" name="mode" value="delete">
 </form>
 
<form action="mission_2-5.php" method="post">
編集対象番号： <input type="text" name="editNo" size="5">
<?php echo $error_edit_no ?>
 <input type="submit" name="edit" value="編集">
 <input type="hidden" name="Mode" value="edit">
 </form>
 

 <br>
 <br>
  

 <hr>


</body>



</html>