
<?php
    header("Content-Type: text/html; charset = UTF-8");

    //削除機能
    if (!empty($_POST["remove"]) && !empty($_POST["password_remove"])){
        //保存したパスワードを取得する
        $filename = "mission_2-6.txt";
        $lines = (file($filename));
        foreach($lines as $num => $line){
            $line = mb_convert_encoding($line, "UTF-8", "Shift-JIS");
            $newLine = explode("<>", $line);
            if ($newLine[0] == $_POST["remove"]){
                $password = $newLine[3];
            }
        }

        //パスワードは合っているかどうか確認する
        if($password != $_POST["password_remove"]){
                echo "<script type='text/javascript'>alert('パスワードが間違っています');</script>";
        }else{
            $filename = "mission_2-6.txt";
            $lines = (file($filename));
            unlink($filename);
            foreach ($lines as $num => $line){
                $newLine = explode("<>", $line);
                
                //保存されていた各投稿番号とPOSTで送信された削除番号を比較し、イコールでない時のみテキストファイルに上書き保存を行う
                if ($newLine[0] < $_POST["remove"]){
                    $fp = fopen($filename, "a") or die ("unable to open the file");
                    $txt = $newLine[0]."<>".$newLine[1]."<>".$newLine[2]."<>".$newLine[3]."<>".$newLine[4];
                    fwrite($fp, $txt);
                    fclose($fp);
                    header('Location: /mission_2-6.php');
                    
                //番号の調整
                }elseif($newLine[0] > $_POST["remove"]){
                    $number = $newLine[0] - 1;
                    $fp = fopen($filename, "a") or die ("unable to open the file");
                    $txt = $number."<>".$newLine[1]."<>".$newLine[2]."<>".$newLine[3]."<>".$newLine[4];
                    fwrite($fp, $txt);
                    fclose($fp);
                    header('Location: /mission_2-6.php');
                }
            }
        }
    }
    
    //編集機能
    if(!empty($_POST["edit"]) && !empty($_POST["password_edit"])){

        //保存したパスワードを取得する
        $filename = "mission_2-6.txt";
        $lines = (file($filename));
        foreach($lines as $num => $line){
            $line = mb_convert_encoding($line, "UTF-8", "Shift-JIS");
            $newLine = explode("<>", $line);
            if ($newLine[0] == $_POST["edit"]){
                $password = $newLine[3];
            }
        }

        //パスワードは合っているかどうか確認する
        if($password != $_POST["password_edit"]){

                echo "<script type='text/javascript'>alert('パスワードが間違っています');</script>";

        }else{
            $filename="mission_2-6.txt";
            $lines = file($filename);
            //配列値を取得する
            foreach ($lines as $num => $line){
                $line = mb_convert_encoding($line, "UTF-8", "Shift-JIS");
                $newLine = explode("<>", $line);
                if ($newLine[0] == $_POST["edit"]){
                    $userid_edit=$newLine[1];
                    $comment_edit=$newLine[2];
                    $password_edit=$newLine[3];
                    
                }
            }
        }
    }
?>
<html>
	<head>
		<title>〜日本留学中〜</title>
		<style type="text/css">
			body{
				margin-left: 15px;
				margin-top: 15;
				text-align: center;
			}
			
			#myform{
				width: 520px;
				padding-top: 10px;
				border: 1px solid black;
				border-radius: 5px;
				
			}
			
			#memory{
				width: 500px;
				padding: 10px;
				border: 1px solid black;
				border-radius: 5px;
			}
			
			#removeForm{
				width: 500px;
				padding: 0px 10px;
				padding-bottom: 10px;
				border: 1px solid black;
				border-radius: 5px;
			}
			
			#editForm{
				width: 500px;
				text-align: center;
				padding: 0px 10px;
				padding-bottom: 10px;
				border: 1px solid black;
				border-radius: 5px;
			}
		</style>
	</head>
	<body>
		<!--入力フォーム-->
		<form method="post" id="myForm">
			<h3>日本での留学生活について、今日の感想は?</h3>
			<p>ユーザーID: 
			<input type="text" name="userid" placeholder="User ID"
						<?php  
							//編集モードなら配列値を表示させる
                            if(!empty($_POST["edit"]) && !empty($_POST["password_edit"])){
                                echo"value=$userid_edit";
                            }
						?>
			></p>
			
			<p>コメント:
			<input type="text" name="comment" placeholder="Comment here"
						<?php
                            if(!empty($_POST["edit"]) && !empty($_POST["password_edit"])){
                                echo"value=$comment_edit";
                            }
						?>
			></p>
			<p>パスワード:
			<input type="text" name="password" placeholder="password" 
                   <?php 
                        if(!empty($_POST["edit"]) && !empty($_POST["password_edit"])){
                            echo"value=$password_edit";
                        }
                   ?>
			></p>
			<?php  if(!empty($_POST["edit"]) && !empty($_POST["password_edit"])){ echo"<input type='hidden' name='editConfirmation' value=".$_POST['edit'].">"; }?>
			<input type="submit" name="submit" value="投稿" form="myForm" onclick="return confirm('確認しましたか?');">
			
			
			<?php
				
				//編集かどうかわかる
				if (empty($_POST["editConfirmation"])){
				    //入力モード
					if (empty($_POST["userid"]) || empty($_POST["comment"]) || empty($_POST["password"])){
						echo "<h5>必須項目を記入してください！</h5>";
					}else{
						$filename = ("mission_2-6.txt");
						$lines = file($filename);
						$fp = fopen($filename, "a") or die ("Unable to open the file");
						
						//投稿番号
						$number = count(file($filename)) + 1;
						
						//送信された入力値を受け取り、テキストファイルに保存する
						$txt = $number."<>".$_POST["userid"]."<>".$_POST["comment"]."<>".$_POST["password"]."<>".date("y/m/d h:i:s");
						$txt = mb_convert_encoding($txt, "Shift-JIS", "UTF-8");
						fwrite($fp, $txt."\r\n");
						fclose($fp);
						header('Location: /mission_2-6.php');
					}
				}else{
					//編集モード
					$filename="mission_2-6.txt";
					$lines = file($filename);
					unlink($filename);
                    
					foreach ($lines as $num => $line){
						$line = mb_convert_encoding($line, "UTF-8", "Shift-JIS");
						$newLine = explode("<>", $line);
						
                        //保存されていた各投稿番号とPOSTで送信された編集番号を比較し、イコールの時に編集モード下で入力フォームから送信された値と差し替える
						if ($newLine[0] == $_POST["editConfirmation"]){
							$fp = fopen($filename, "a") or die ("Unable to open the file!");
							$txt = $newLine[0]."<>".$_POST["userid"]."<>".$_POST["comment"]."<>".$_POST["password"]."<>".$newLine[4];
							$txt = mb_convert_encoding($txt, "Shift-JIS", "UTF-8");
							fwrite($fp, $txt);
							fclose($fp);
				        
                        //イコールでない時にそのまま保存する
						}elseif ($newLine[0] != $_POST["editConfirmation"]){
							$fp = fopen($filename, "a") or die ("Unable to open the file!");
							$txt = $newLine[0]."<>".$newLine[1]."<>".$newLine[2]."<>".$newLine[3]."<>".$newLine[4];
							$txt = mb_convert_encoding($txt, "Shift-JIS", "UTF-8");
							fwrite($fp, $txt);
							fclose($fp);
						}
					}
					header('Location: /mission_2-6.php');
				}
								
			?>
			
		</form>
		
		<!--掲示板-->
		<div id="memory">
			<h3>メモリー</h3>
			<?php
				
				$filename = "mission_2-6.txt";
				$lines = (file($filename));
              
                echo "<p>番号、ユーザーID、コメント、日付</p>";
				foreach($lines as $line){
					$line = mb_convert_encoding($line, "UTF-8", "Shift-JIS");
					$newLine = explode("<>", $line);
					echo "<p>".$newLine[0]."、".$newLine[1]."、".$newLine[2]."、".$newLine[4]."</p>";
				}
			?>
		</div>
		
		<br>
		
		<!--削除フォーム-->
		<form method="post" id="removeForm">
			<p>削除したい内容がありますか?</p>
			<p>削除対象番号：<input name="remove" type="text" placeholder="number"></p>
			<p>パスワード:<input type="text" name="password_remove" placeholder="password"></p>
			<input type="submit" name="submit2" value="削除" form="removeForm" onclick="return confirm('確認しましたか?');">
		</form>
		
		<!--編集フォーム-->
		<form method="post" id="editForm">
			<p>編集したい内容がありますか?</p>
			<p>編集対象番号：<input name="edit" type="text" placeholder="number"></p>
			<p>パスワード:<input type="text" name="password_edit" placeholder="password"></p>
			<input type="submit" name="submit3" value="編集" form="editForm">
			
		</form>
	</body>
</html>