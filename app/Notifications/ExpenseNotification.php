<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ExpenseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    //mendefinisikan varaibel secara global
    protected $expense; 
    protected $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($expense, $user) //parameter ini menangkap value dari ExpenseController.php 
    {
        $this->expense = $expense; //mengambil data expense, kemudian men-assign ke variabel diatas untuk dijadikan variabel global
        $this->user = $user; //mengambil data user yg sedang login/mengirimkan notifikasi
    }

 
    public function via($notifiable)
    {
        //menggunakan dua metode yaitu menyimpan ke databse dan broadcast ke pusher
        //karena antara yg disimpan ke database dan ke broadcast pusher berbeda form
        //jika form sama, maka bisa pakai satu method saja yaitu toArray()
        return ['database', 'broadcast'];
    }

    //form data yg disimpan ke database
    public function toDatabase($notifiable)
    {
        return [
            'sender_id' => $this->user->id,
            'sender_name' => $this->user->name,
            'expense' => $this->expense
        ];
    }

    //form data yg disimpan ke broadcast pusher
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'sender_id' => $this->user->id,
            'sender_name' => $this->user->name,
            'expense' => $this->expense
        ]);
    }
}
