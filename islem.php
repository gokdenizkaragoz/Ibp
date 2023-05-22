<?php
ob_start();
session_start();

require 'baglan.php';

if (isset($_POST['kayit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_again = @$_POST['password_again'];

    if (!$username) {
        echo "kullanıcı adını lütfen giriniz";
    } elseif (!$password || !$password_again) {
        echo "Lütfen şifrenizi girin";
    } elseif ($password != $password_again) {
        echo "girdiğiniz şifreler aynı değil";
    } else {
        //VERİ TABANI KAYIT İŞLEMİ
        $sorgu = $db->prepare('INSERT INTO users SET user_name=?,user_password=?');
        $ekle = $sorgu->execute([
            $username, $password
        ]);
        if ($ekle) {
            echo "Kayit basarili, yönlendiriliyorsunuz";
            header('Refresh:2; index.php');
        } else {
            echo "hata oluştu konrol et";
        }
    }
}

//GİRİŞ YAPMA
if (isset($_POST['giris'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!$username) {
        echo "kullanıcı adını girin";
    } elseif (!$password) {
        echo "şifre girin";
    } else {
        $kullanici_sor = $db->prepare('SELECT * FROM users WHERE user_name = ? || user_password = ?');
        $kullanici_sor->execute([
            $username, $password
        ]);
        $say = $kullanici_sor->rowCount();
        if ($say == 1) {
            $_SESSION['username'] = $username;
            echo "Başariyla giriş yaptınız, yön";
        } else {
            echo "hata oldu tekrar kontrol et";
        }
    }
}
