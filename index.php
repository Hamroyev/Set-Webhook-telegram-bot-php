
<?php
ob_start();

/*
Set webhook bot | example: @Webhook_uz_bot

Version: 1

Language: UZ

Data: 05.08.2018

Maker: @Mirmuhsin
*/

define('API_KEY','1234567890:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'); //TOKEN
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$message_id = $message->message_id;
$chat_id = $message->chat->id;
$text1 = $message->text;
$user_id = $message->from->id;
$reply_text = $message->reply_to_message->text;
$token = file_get_contents("util/$chat_id.txt");
$idlar = file_get_contents("idlar.txt");
//bu yerni o'zgartirishingiz mumkin.

$menu = json_encode([
        'resize_keyboard'=>false,
        'keyboard'=>[
            [['text'=>"Webhookni sozlash"]], //Keyboard SET WEBHOOK
      ]
    ]);



$reply_menu = json_encode([
           'resize_keyboard'=>false,
            'force_reply' => true,
            'selective' => true
        ]);



if($text1=="/start"){
  $text = "👋 Assalomu aleykum 👋

❇️ Ushbu bot yordamida o'z telegram botlaringizni webhook manzilini telegramdan chiqmasdan turib oson sozlashingiz mumkin! ❇️

🔆 Batafsil ma'lumot /help komandasi orqali 🔆

🛠 Yaratuvchi: @Mirmuhsin 🛠";
  bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>$text,
    'parse_mode'=>'markdown',
    'reply_markup'=> $menu
  ]);
    if(mb_stripos("$idlar", "$user_id") !== false){
    } else {
    $saqla = "$idlar\n$user_id";
    file_put_contents("idlar.txt", $saqla);
    }
}

if($text1 == "Webhookni sozlash"){
    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => "Tokenni kiriting",
        'reply_markup'=> $reply_menu
    ]);
}

if($reply_text == "Tokenni kiriting"){
    if(mb_stripos($text1, ":") !== false and strlen($text1) == '45'){
    $saqlash = "$token$text1";
    file_put_contents("util/$chat_id.txt", "$saqlash");
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "Token saqlandi! Endi webhook url manzilini kiriting (https:// ishlatmang!)",
            'parse_mode' => 'markdown',
            'reply_markup'=>$reply_menu
        ]);
    } else {
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "O'yin qilmang, iltimos 😜",
            'parse_mode' => 'markdown'
        ]);
    }
}


if($reply_text == "Token saqlandi! Endi webhook url manzilini kiriting (https:// ishlatmang!)"){
    if(mb_stripos($text1, "/") !== false){
        $result = "https://api.telegram.org/bot$token/setwebhook?url=$text1";
        file_get_contents("https://api.telegram.org/bot$token/setwebhook?url=$text1");
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "Omadli yakunlandi, webhook sozlandi!\n\nAgar webhookni keyingi safar mobil brauzeringiz orqali qo'lda sozlamoqchi bo'lsangiz \n\n`$result`\n\n manzilini mobil brauzereringiz oynasiga kiritishingiz kerak yoki bizning webhook.lark.ru ( faqat kompyuter versiyalarida ishlaydi ) saytimiz orqali ulashingiz mumkin! \n \nAgar botimiz sizga yoqgan bo'lsa, iltimos, uni do'stlaringizgaham tavsiya qiling!",
            'parse_mode' => 'markdown',
            'reply_markup'=>json_encode([
              'inline_keyboard'=>[
                [
                  ['text'=>'Ulashish','url'=>'https://t.me/share/url?url=https%3A//telegram.me/Webhook_uz_bot&text=Endi%20webhookni%20telegramdan%20chiqmasdan%20sozlash%20mumkin%21']
                ]
              ]
            ])
        ]);
        unlink("util/$chat_id.txt");
    } else {
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "Yoki url manzilni yozishda https:// ishlatyapsiz yoki mendan *bug* topmoqchisiz 😜",
            'parse_mode' => 'markdown'
        ]);
    }
}



if($text1 == "/help"){
    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => "👋 Assalomu aleykum 👋

❇️ Ushbu bot yordamida o'z telegram botlaringizni webhook manzilini telegramdan chiqmasdan turib oson sozlashingiz mumkin! ❇️

🛑 Diqqat, bot tokenlari saqlab olinmaydi va kuzatilmaydi ( hech bo'lmaganda o'zingiz yaxshilab o'ylab ko'ring, token orqali uzog'i bilan botni boshqa serverga ulash mumkin. Ammo bu ko'pga cho'zilmaydi, bir kun o'tar, ikki kun o'tar va oxiri siz buni sezib qolasiz va meni hammaga yomonlaysiz. Endi yaxshilab o'ylang, ikki kun bot boshqaraman deb yomon nom orttirish menga kerakmi? ) 🛑

💻 Agar webhookni kompyuterda web brauzerlar orqali sozlamoqchi bo'lsangiz o'zimizning webhook.lark.ru saytimizni tavsiya qilaman 💻

🔰 Sizgaham shunday botlar kerakmi? Unda bizga murojaat qiling. 🔰

🛠 Yaratuvchi: @Mirmuhsin 🛠"
    ]);
}

//Static users

if($text1 == "/statistika"){
    $statistika = count(file("idlar.txt"))-1;
    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => "Bot foydalanuvchilari soni: _ $statistika _",
        'parse_mode' => 'markdown'
    ]);
}
