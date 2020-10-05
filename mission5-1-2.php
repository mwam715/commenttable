<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>

<body>
    <h1 class="midashi_1"> <span style ="background-color:aqua">🐤コメント掲示板 🐤</span></h1>
    <?php
	// DB接続設定
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$pass = 'パスワード';
	$pdo = new PDO($dsn, $user, $pass,
	  array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	//mission4-2  
	$sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date DATETIME,"
	. "password varchar(32)"
	.");";
	$stmt = $pdo->query($sql);
	
        
    if(!empty($_POST['name']) && !empty($_POST['comment']) && empty($_POST['renum'])){
    $name = $_POST['name'];
    $com = $_POST['comment'];
    $date = date("Y/m/d H:i:s");
    $password = $_POST['writepw'];
    
    //mission4-5  
	$sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, password)
	     VALUES (:name, :comment, :date, :password)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $com, PDO::PARAM_STR);
	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
	$sql -> bindParam(':password', $password, PDO::PARAM_STR);
	$sql -> execute();
    }
        
    if(!empty($_POST["delnum"]) && !empty($_POST["del_pw"])){
       $delpw =$_POST['del_pw'];
       
       //mission4-6
	   $delnum = $_POST['delnum'];
	   $id = $delnum;// idがこの値のデータだけを抽出したい、とする
	   $sql = 'SELECT * FROM tbtest WHERE id=:id';
	   $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
       $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
       $stmt->execute();    // ←SQLを実行する。
       $results = $stmt->fetchAll();
       foreach ($results as $row){
           $delid = $row['id'];
           $pass = $row['password'];
           echo $pass;
           
       }
    
       if($pass == $delpw){
        //mission4-8 
        $id = $_POST['delnum'];
    	$sql = 'delete from tbtest where id=:id';
    	$stmt = $pdo->prepare($sql);
    	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    	$stmt->execute();
    	}elseif($pass != $POST['del_pw']){
    	    //echo $writepw;
    	    echo "パスワードが間違っています!!";
    	}
    }
    
    elseif(!empty($_POST['Renum'])){
        //mission4-7  
	    $id = $_POST['Renum']; //変更する投稿番号
	    
	    //mission4-6
	   $sql = 'SELECT * FROM tbtest WHERE id=:id ';
	   $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
       $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
       $stmt->execute();    // ←SQLを実行する。
       $results = $stmt->fetchAll();
       foreach ($results as $row){
           //$rowの中にはテーブルのカラム名が入る
		   $renum = $row['id'];
		   $rename = $row['name'];
		   $recom = $row['comment'];
		   $repw = $row['password'];
		   }
		 
		   if($repw == $_POST['rewritepw']){
            $Renum = $renum;
            $Rename = $rename;
            $Recom = $recom;
            $Repw = $repw;
            }else{
                echo "！パスワードが間違っています！";
            }
    }
        
	
        
    //編集フォーム
    if(!empty($_POST['name']) && !empty($_POST['comment']) 
        && !empty($_POST['writepw']) && !empty($_POST['renum'])){
            
        //mission4-7
        $id = $_POST['renum'];//再掲
        echo $id;
	    $name = $_POST['name'];
	    $comment = $_POST['comment'];
	    $date = date("Y/m/d H:i:s");
	    $password = $_POST['writepw'];
	    $sql = 'UPDATE tbtest SET name=:name,comment=:comment, date=:date, password=:password WHERE id=:id';

	    //左を右に変える
     	$stmt = $pdo->prepare($sql);
    	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
    	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    	$stmt->bindParam(':date', $date, PDO::PARAM_STR);
    	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    	$stmt->execute();
        }
    
         ?>
【投稿】
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前"
         value="<?php if(isset($Rename)){echo $Rename;}?>">
        <input type="text" name="writepw" placeholder="パスワード">
        <input type="hidden" name="renum"
         value="<?php if(isset($Renum)){echo $Renum;} ?>">
        <input type="submit" name="submit1"><br>
        <td valign="top">
        <input type="text" name="comment" placeholder="コメント"
         style="width:350px; height:100px;"
         
         value="<?php if(isset($Recom)){echo $Recom;}?>">
        </td> 
    </form>
【削除】
    <form action="" method="post">
        <input type="number" name="delnum"
          placeholder="削除対象番号">
        <input type="text" name="del_pw" placeholder="パスワード">
        <input type="submit" name="submit2" value="削除"><br>
    </form>
【編集】
    <form action="" method="post">
        <input type="number" name="Renum"
          placeholder="編集対象番号">
        <input type="text" name="rewritepw" placeholder="パスワード"> 
        <input type="submit" name="submit3" value="変更"><br><br>
    </form>
    
    <?php
    //mission4-6   
    $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo "【投稿番号】:". $row['id']."<br>";
		echo "【名前】:". $row['name']."<br>";
		echo "【コメント】:".$row['comment']."<br>";
		echo "【日付】:".$row['date']."<br>";
	    echo "<hr>";
	}
	
    ?>
    
</body>
</html>