<?php

function uploadFile () {

   define('MULTIPART_BOUNDARY', '--------------------------'.microtime(true));
   //フォームからファイル名を取得
   $filename = htmlspecialchars($_POST['filename']);
   $filepath = $_FILES['userfile']['tmp_name'];
   $contentType = $_FILES['userfile']['type'];

    //APIキーを設定
    $application_key = 'APPLICATION_KEY';
    $client_key      = 'CLIENT_KEY';

    //リクエスト作成
    $method = 'POST';
    $fqdn   = 'mb.api.cloud.nifty.com';
    $api_version = '2013-09-01';
    $path        = 'files/'.$filename;
    date_default_timezone_set('Asia/Tokyo');
    $timestamp = date(DATE_ISO8601, time());
    $url = "https://" . $fqdn . "/" . $api_version . "/" . $path;

    //シグネチャー計算
    $header_string  = "SignatureMethod=HmacSHA256&";
    $header_string .= "SignatureVersion=2&";
    $header_string .= "X-NCMB-Application-Key=".$application_key . "&";
    $header_string .= "X-NCMB-Timestamp=".$timestamp;
    $signature_string  = $method . "\n";
    $signature_string .= $fqdn . "\n";
    $signature_string .= "/" . $api_version . "/" . $path . "\n";
    $signature_string .= $header_string;
    $signature = base64_encode(hash_hmac("sha256", $signature_string, $client_key, true));

    //一時ファイルができているか（アップロードされているか）チェック
    if(is_uploaded_file($filepath)){

        //一時ファイルができている場合、ファイルコンテンツを作成
        $file_contents = file_get_contents($filepath);
        $content =  "--".MULTIPART_BOUNDARY."\r\n".
                    "Content-Disposition: form-data; name=\"file\"; filename=\"".basename($filepath)."\"\r\n".
                    "Content-Type: ".$contentType."\r\n\r\n".
                    $file_contents."\r\n";
        $content .= "--".MULTIPART_BOUNDARY."--\r\n";

        //ヘッダー指定
        $headers = array(
            'Content-Type: multipart/form-data; boundary='.MULTIPART_BOUNDARY,
            'X-NCMB-Application-Key: '.$application_key,
            'X-NCMB-Signature: '.$signature,
            'X-NCMB-Timestamp: '.$timestamp
        );

        $options = array('http' => array(
            'method' => 'POST',
            'content' => $content,
            'header' => implode("\r\n", $headers),
            'ignore_errors' => true
        ));
        $file_upload_request = file_get_contents($url, false, stream_context_create($options));
        $Message = urlencode($file_upload_request);
        header("Location:index.php?Message=".$Message);
        die;
    }else{
        //ファイルが読み込めない
        $Message = urlencode("ファイルがアップロードされていません。");
        header("Location:index.php?Message=".$Message);
        die;
    }
}

uploadFile();
?>
