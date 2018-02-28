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
  

    //新規投稿
    $name=$_POST["namae"];
	$cmt=$_POST["comment"];
     $log=$name.$cmt ;
	$times=date('Y/m/j H:i:s');
	$id = $_POST["id"]; //hiddenの値
	$pass = $_POST["pass"];
    
    $fp=fopen($filename, 'a');
   //echo "投稿内容の確認"."|".$log.$times."<hr>" ; //投稿内容の確認
   
   
if (isset($_POST["btn"]) && empty($_POST["id"])) {
  //ボタンが押された時　かつ　hiddenがないときに投稿する 

      $lines = file($filename);
	  $num=count($lines);
	  $num+= 1 ;
	   $logs= $num."<>".$name."<>".$cmt."<>".$times."<>".$pass."<>";
	  
	   fwrite($fp, $logs."\n");
	   
	  }else{
	  }
	 fclose($fp);
	 //echo "hiddenデータ受け取り確認"."><".$_POST["id"]."<hr>"; //



//削除方法
 if(isset($_POST["deleteNo"]) && isset($_POST["delpass"])) {
 //削除番号と削除パスワードが入力された場合
    $delete = $_POST["deleteNo"];
	$delpass = $_POST["delpass"];
    $delCon = file("kpop.txt");
	//echo (var_dump($delete))."デリート番号の確認<<".$delete."<br>"; //
	//echo (var_dump($delpass))."デリートパスワードの確認<<".$delpass."<hr>"; //

    $fo = fopen("kpop.txt", "w");
    for ($j = 0; $j < count($delCon); $j++) {
	//echo "delete_for_test"."<>".$j."><"."<hr>" ; //
        $delDate = explode("<>", trim($delCon[$j]));//両端の空白をなくす
		
	//echo "番号".$delDate[0]."--パスワード".$delDate[4]."<br>" ; //テスト
    //var_dump($delDate[0],$delDate[4]);
	//echo "<hr>";
		if ($delDate[0] == $delete && $delDate[4] == $delpass) { 
		//両方一致したら番号と削除文を上書きする
            $a=count(file($filename));
			$a += 1;
             fwrite($fo, $a."<>"."削除されました。"."\n");
			 //echo $a."<hr>" ; //

        } else {
        //配列番号と削除番号がどちらかでも一致しなかったら
		//echo "delete_for_if_test"."><".$delData[0]."<hr>" ; //
            fwrite($fo, $delCon[$j]); //元の行を書き込む
        }
    }
    fclose($fo);
}

//編集方法

if (isset($_POST["editNo"]) && isset($_POST["edipass"])) { 
//編集対象番号とパスワードが入力されたら

//投稿フォームに表示
$filedatas = file("kpop.txt");
$editNo =  $_POST["editNo"];  //編集対象番号
$edipass = $_POST["edipass"]; //編集パスワード

foreach($filedatas as $filedata){
  
  $data = explode("<>",trim($filedata));//両端の空白をなくす
  
  //echo "テスト".$data[0]."-".$data[4]."<<>>".$editNo."-".$edipass."<br>"; //
  //var_dump($data[0],$editNo,"---",$data[4],$edipass);//
  
  If($data[0]==$editNo && $data[4]==$edipass){
  //編集番号と編集パスが一致した場合に
  
    $id=$data[0];
	$namae=$data[1];
	$comment=$data[2];
	$ediPass=$data[4];
	
	//echo "編集データ送信の確認".$id."<br>".$namae."<br>".$comment."<br>".$ediPass."<br>" ; //確認
	}
  }
}

//編集モードで投稿する
if (isset($_POST["btn"]) && (!empty($_POST["id"]))) {
//送信ボタンが押され、hiddenの数値があるとき

//echo "hiddenデータ確認".$id."<br>" ;

 $ediCon = file("kpop.txt"); //1行ずつ配列に格納する

  $fr = fopen('kpop.txt','w+'); //読み込んで空にして開く
  foreach($ediCon as $content) { //配列から１つずつ取り出す
 
	$parts = explode("<>", $content); //＜＞で切って配列に
	
  //echo "編集番号と投稿番号が一致してるか検証/".$parts[0]."/".$id."--"."<hr>" ; //テスト
  
    if($parts[0] == $id) {
 //投稿番号が編集番号と同じなら
	     $Logs= $id."<>".$name."<>".$cmt."<>".$times."<>".$pass."<>";
         fwrite($fr, $Logs."\n"); //編集した１行をファイルに追記
    } else { //一致しないときは元のデータをそのまま書き込み
         fwrite($fr, $content); //元の１行をファイルに追記
    }
   }
  fclose($fr);
  }
  
//投稿の最終表示
 $arrays=file($filename);
 
    foreach($arrays as $array)
	//echo "foreach_test"."<>".$arrays."<br>".$array."<hr>" ; //
 {
  //配列としてデータを組み込む
     $ray=explode("<>",$array);
  //<>で区切ってデータを取得
  
     echo "番号:".$ray[0]."<br>"; //投稿番号
     echo "By:".$ray[1]."<br>"; //名前
     echo $ray[2]."<br>"; //コメント
     echo "投稿時間:".$ray[3]."<br>"; //投稿時間
	 //echo "パスワード:".$ray[4]."<br>"; //パスワードの表示確認
     echo "<hr>"; 
	 //echo (var_dump($ray[0],$ray[4]))."<hr>"; //テスト
}

?>



<!-投稿フォーム-!>

<form action="mission_2-6.php" method="post">
  <table>
	<tr>
	 <td>名前:</td>
	 <input type="hidden" name="id" value="<?php echo $id; ?>">
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
<form action="mission_2-6.php" method="post"> 
削除対象番号: <input type="text" name="deleteNo" size="5"> 
<br>
<?php echo $error_delete_no ; ?>
パスワード: <input type="text" name="delpass" size="10">
 <input type="submit" name="delete" value="削除"> 

 </form>
 
<form action="mission_2-6.php" method="post">
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