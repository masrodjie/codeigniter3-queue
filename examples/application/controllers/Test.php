<?php if(!defined('BASEPATH')) exit('No direct access script allowed');

class Test extends CI_Controller {
{
    public function index()
    {
        $queue = new Masrodjie\Queue\Libraries\Queue();
        $queue->push('\App\Jobs\SendEmail', ['to' => 'me@example.com']);
    }
}
