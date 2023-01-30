<?php if(!defined('BASEPATH')) exit('No direct access script allowed');

class Queue extends CI_Controller {
 
    public function work() {
        $queue = new Masrodjie\Queue\Libraries\Queue();
        $dispatcher = new Illuminate\Events\Dispatcher();
        $exception = new \Masrodjie\Queue\Exceptions\Handler();
        $isDownForMaintenance = function () {
            return false;
        };
        $worker = new Illuminate\Queue\Worker($queue->getQueueManager(), $dispatcher, $exception, $isDownForMaintenance, null);
        $options = new Illuminate\Queue\WorkerOptions();
        $options->maxTries = 5;
        $options->timeOut = 300;
        $worker->daemon('redis', 'default', $options);
    }
    
}