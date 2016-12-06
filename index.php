<style>

input {
  border:solid 1px #EEA34A;
  padding:10px;
  font-size:1em;
  font-family:Arial, sans-serif;
  color:#aaa;
  border:solid 1px #ccc;
  margin:0 0 20px;
  width:400px;
}

.alert {
    padding: 20px;
    background-color: #FE9A2E; /* Orange */
    color: white;
    margin-bottom: 15px;
}

</style>

<img src="index.png" />
<h1>ファイルアップロードフォーム</h1>

<!-- データのエンコード方式である enctype は、必ず以下のようにしなければなりません -->
<form enctype="multipart/form-data" action="action.php" method="POST">
    <!-- input 要素の name 属性の値が、$_FILES 配列のキーになります -->
    ファイル名
    <br>
    <input type="text" name="filename" value="例：abc.txt">
    <br>
    アップロードするファイルを選択
    <div id= "file_upload">
            <input type="file" id="userfile" name="userfile" style=";">
    </div>
    <br>
    <input type="submit" value="ファイルを送信する" />
</form>

<div id= "file_upload_result">
    <?php
    if(isset($_GET['Message'])){
        echo "  <label id='result' class='alert'>実行結果：" .$_GET['Message'] . "</label>" ;
    }
    ?>
</div>
