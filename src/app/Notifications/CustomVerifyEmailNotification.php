<?php

namespace App\Notifications;


use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmailNotification extends VerifyEmail
{
    // メールの通知内容をカスタマイズ
    // メールで送信する内容を定義
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->line('メール認証を行ってください。')
            ->action('メール認証', $verificationUrl)
            ->line('このリンクは 60 分以内に有効です。');
    }

    // メール認証用URLをカスタマイズ
    // メール認証のURLを生成
    protected function verificationUrl($notifiable)
    {
        // 正しい署名付きURLを生成
        return URL::temporarySignedRoute(
            'verification.verify', // ルート名
            now()->addMinutes(60), // 有効期限
            [
                'user_id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
