<?php


    require_once ('src/Exception.php');
    require_once ('src/PHPMailer.php');
    require_once ('src/SMTP.php');
    require_once ('setting.php');
    require_once ('../newRegister/registration_mail_form.php');

    // PHPMailerのインスタンス生成
        $email = new PHPMailer\PHPMailer\PHPMailer();
    
        $email->isSMTP(); // SMTPを使うようにメーラーを設定する
        $email->SMTPAuth = true;
        $email->Host = MAIL_HOST; // メインのSMTPサーバー（メールホスト名）を指定
        $email->Username = MAIL_USERNAME; // SMTPユーザー名（メールユーザー名）
        $email->Password = MAIL_PASSWORD; // SMTPパスワード（メールパスワード）
        $email->SMTPSecure = MAIL_ENCRPT; // TLS暗号化を有効にし、「SSL」も受け入れます
        $email->Port = SMTP_PORT; // 接続するTCPポート
    
        // メール内容設定
        $email->CharSet = "UTF-8";
        $email->Encoding = "base64";
        $email->setFrom(MAIL_FROM,MAIL_FROM_NAME);
        $email->addAddress($mail, '仮会員登録中のユーザー様'); //受信者（送信先）を追加する
    //    $mail->addReplyTo('xxxxxxxxxx@xxxxxxxxxx','返信先');
    //    $mail->addCC('xxxxxxxxxx@xxxxxxxxxx'); // CCで追加
    //    $mail->addBcc('xxxxxxxxxx@xxxxxxxxxx'); // BCCで追加
        $email->Subject = MAIL_SUBJECT; // メールタイトル
        $email->isHTML(true);    // HTMLフォーマットの場合はコチラを設定します

        $body = 'この度はご登録いただきありがとうございます。24時間以内に下記のURLからご登録下さい。<br>'.$url;
    
        $email->Body  = $body; // メール本文
        // メール送信の実行
        if(!$email->send()) {
            echo 'メッセージは送られませんでした！';
            echo 'Mailer Error: ' . $email->ErrorInfo;
        } else {
            echo 'メールを送信いたしました。！';
        }

