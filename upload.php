<?php
header('Content-Type: text/html; charset=UTF-8');
$tempfile = $_FILES['fname']['tmp_name'];
$dir = './uploadedFile/'; // アップロード先のディレクトリ名（HTMLからのpathではなくこのphpファイルからのpathである点に注意）
$filename = $dir . $_FILES['fname']['name']; // ディレクトリ名＋ファイル名
date_default_timezone_set('Asia/Tokyo'); // タイムゾーンを設定（ファイルの更新日時表示のため）

if ( is_uploaded_file($tempfile) ) { //ファイルがアップロードされている（is_uploaded_fileがtrue）
    if( preg_match('/\d{2}_st\d{2}_v[1-9]\.(?i)pdf/', $_FILES['fname']['name']) ){ //正規表現とマッチする（preg_matchがtrue，\d{2}は二桁の半角数字，[1-9]は1～9の半角数字1桁，(?i)は以後大文字小文字を区別しない）
        if ( move_uploaded_file($tempfile, $filename ) ) { //アップロードされたファイル（$tempfile）をファイルの保存先（$filename）に移動完了（move_uploaded_fileがtrue）
            echo "<h1>" . $_FILES['fname']['name'] . "をアップロードしました．</h1>";
        }
        else { //アップロードされたファイル（$tempfile）をファイルの保存先（$filename）に何らかの理由で移動できなかった（move_uploaded_fileがfalse）
            echo "<h1>ファイルをアップロードできません．</h1>";
        }
    }
    else { //正規表現とマッチしない（preg_matchがfalse）
        echo "<h1>ファイル名が間違っています．</h1>";
        echo "<h2>下記のファイル名の説明を良く読んで再アップロードしてください．</h2>";
        echo "<p>レポートの表紙とチェックシートとレポート本体を1つのPDFファイルとして保存し，ファイル名を「XX_stYY_vZ.pdf」としてアップロードすること．<br>このとき，XXには2桁の半角数字の班番号（01, 02, ..., 09, 10），YYには2桁の半角数字の出席番号（01, 02, ..., 44, 45），Zにはバージョン番号（初回提出は1，再レポート提出は2，再々レポート提出は3，...）がそれぞれ入る．<br>例：1班出席番号12番の初回レポート提出時は「01_st12_v1.pdf」，1班出席番号12番の再レポート提出時は「01_st12_v2.pdf」<br>「全角英数文字を使う」，「アンダースコア（_）とハイフン（-）を間違える」等の誤ったファイル名ではアップロードエラーが表示される．<br>また，班番号や出席番号を間違えた場合は減点するので注意せよ．<br>同一のファイル名で再アップロードするとファイルは上書きされる．</p>";
    }
}
else { //ファイルがアップロードされていない（is_uploaded_fileがfalse）
    echo "<h1>ファイルが選択されていないか，アップロードサイズの上限をオーバーしています．</h1>";
}

// ディレクトリの内容を読み込み（学生は自分のファイルがアップされたかどうか不安になるので，現在アップされいるファイル一覧を確認できるよう表示）
echo "現在サーバにアップロードされているファイル一覧と最終更新日時：<br>";
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ( strcmp($file, ".") !== 0 && strcmp($file, "..") !== 0){ // .と..は表示しない
                $filetime = date("Y-m-d H:i:s", filemtime( $dir . $file )); // ファイルの更新時刻取得しdate関数で時刻を整えて出力（$dir.$fileがファイルパス）
                echo "　- " . $file . " : " . $filetime . "<br>";
            }
        }
        closedir($dh);
    }
}
?>