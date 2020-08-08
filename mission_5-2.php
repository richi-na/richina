<?php
     // DB接続設定
 $dsn = 'データベース名';
 $user = 'ユーザー名';
 $password = 'パスワード名';
 $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-21</title>
</head>
<body>
    <font size="5"><b>おすすめの旅行先</b></font><br>
    <font size="3">海外or国内どっちでもオッケー^^！！</font>
    <?php  
         $sql = "CREATE TABLE IF NOT EXISTS tbtest7"
 ." ("
 . "id INT AUTO_INCREMENT PRIMARY KEY,"
 . "name char(32),"
 . "comment TEXT,"
 . "created DATETIME,"
 . "pass TEXT"
 .");";
    
    $date=date("Y-m-d H:i:s");
     //新規投稿
    if(isset($_POST["Ssubmit"])
        &&!empty($_POST["name"])
        &&!empty($_POST["come"])
        &&empty($_POST["edit_post"])
        &&!empty($_POST["pass1"])){
        $name1=$_POST["name"];
        $comment1=$_POST["come"];
        $pass1=$_POST["pass1"];
         $stmt = $pdo->query($sql);
    $sql = $pdo -> prepare("INSERT INTO tbtest7 (name, comment,created, pass) 
    VALUES (:name, :comment, :created, :pass)");
    $sql -> bindParam(':name', $name1, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment1, PDO::PARAM_STR);
    $sql -> bindParam(':created', $date, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass1, PDO::PARAM_STR);
    //好きな名前、好きな言葉は自分で決めること
    $sql -> execute();
    }elseif(isset($_POST["Ssubmit"])
        &&!empty($_POST["name"])
        &&!empty($_POST["come"])
        &&!empty($_POST["edit_post"])
        &&!empty($_POST["pass1"])){
        $Ename=$_POST["name"];
        $Ecome=$_POST["come"];
        $pass1=$_POST["pass1"];
            $id =$_POST["edit_post"]; //変更する投稿番号
        //変更したい名前、変更したいコメントは自分で決めること
     $sql = 'UPDATE tbtest7 SET name=:name,comment=:comment,created=:created,pass=:pass WHERE id=:id';
     $stmt = $pdo->prepare($sql);
     $stmt->bindParam(':name', $Ename, PDO::PARAM_STR);
     $stmt->bindParam(':comment', $Ecome, PDO::PARAM_STR);
     $stmt->bindParam(':created', $date, PDO::PARAM_STR);
     $stmt->bindParam(':pass', $pass1, PDO::PARAM_STR);
     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
     $stmt->execute();
    }
 
        //削除
    if(isset($_POST["delbox"])&&!empty($_POST["pass2"])){
        $pass2=$_POST["pass2"];
        $Dnum=$_POST["del"];
            $id=$Dnum;
                $sql = 'SELECT * FROM tbtest7 WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
	        foreach ($results as $row){
	            if($pass2==$row['pass']){
	                   $id = $Dnum;
                    $sql = 'delete from tbtest7 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
	            }else{
	            }
	            
	        }
    }else{
        
    }
 
        //編集
    $editNumber="";
    $editName="";
    $editComment="";
    if(isset($_POST["Esubmit"])){
        $pass3=$_POST["pass3"];
        $id = $_POST["Enum"] ; // idがこの値のデータだけを抽出したい、とする

        $sql = 'SELECT * FROM tbtest7 WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
	foreach ($results as $row){
	    if($pass3==$row['pass']){
		//$rowの中にはテーブルのカラム名が入る
		$editNumber=$row['id'];
		$editName=$row['name'];
		$editComment=$row['comment'];
	    }
	}
    }
?>
    <form action="" method="post">
        <input type="hidden" name="edit_post"
        value="<?php if(!empty($editNumber)){echo $editNumber;}?>">
        <input type="text" name="name" placeholder="お名前"
        value="<?php if(!empty($editName)){echo $editName;}?>">
        <input type="text" name="come" placeholder="コメント"
        value="<?php if(!empty($editComment)){echo $editComment;}?>">
        <input type="text" name="pass1" placeholder="password">
        <input type="submit" name="Ssubmit"><br>
        <input type="number" name="del" size="1" placeholder="削除対象番号">
        <input type="text" name="pass2" placeholder="password">
        <input type="submit" name="delbox" value="削除"><br>
        <input type="number" name="Enum" placeholder="編集番号">
        <input type="text" name="pass3" placeholder="password">
        <input type="submit" name="Esubmit" value="編集">
    </form>
<?php
            $sql = 'SELECT * FROM tbtest7';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
        foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['created'].'<br>';
            echo "<hr>";
            }
?>
</body>
</html>