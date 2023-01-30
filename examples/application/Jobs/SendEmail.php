<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmail implements ShouldQueue
{

    use InteractsWithQueue, Queueable, SerializesModels;

    public function fire($e, $payload) {
        $this->onQueue('processing');
        echo "FIRE\n";

        $ci=&get_instance();
        $ci->load->library('email');

        $ci->email->from('your@example.com', 'Your Name');
        $ci->email->to($payload['to']);

        $ci->email->subject('Email Test');
        $ci->email->message('Testing the email class.');

        $ci->email->send();
        
        $e->delete();
    }

}