<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LotteriaPromotionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build(): self
    {
        return $this->from(config('mail.from.address', 'lotteriaph@gmail.com'), config('mail.from.name', 'Lotteria Philippines'))
            ->subject('Get Ready for an Exclusive LOTTERIA Experience!')
            ->view('emails.lotteria-promotion')
            ->with([
                'user_name' => $this->user->name ?: $this->user->username,
                'dashboard_link' => url('/dashboard'),
            ]);
    }
}
