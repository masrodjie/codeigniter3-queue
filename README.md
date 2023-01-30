# The Illuminate Queue package for CodeIgniter 3


## Instalation

Include this package via Composer:

```console
composer require masrodie/codeigniter3-queue
```

## Setup services queue

Add redis config in .env. You can use dotenv package https://github.com/vlucas/phpdotenv
```
REDIS_HOST=localhost
REDIS_CLIENT=predis
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_SCHEME=tcp
REDIS_DB=0
```

Update composer.json
```
"autoload": {
    "psr-4": {
        "App\\":"application"
    }
}
```

Run
```sh
composer dump-autoload
```

## Usage

Example job
```php
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
```

### Create queue worker controller
```php
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

```

### How to use in controller
```php
<?php if(!defined('BASEPATH')) exit('No direct access script allowed');

class Test extends CI_Controller {
{
    public function index()
    {
        $queue = new Masrodjie\Queue\Libraries\Queue();
        $queue->push('\App\Jobs\SendEmail', ['to' => 'me@example.com']);
    }
}
```

### Run queue worker
```sh
php index.php queue/work
```

## More info usefull link docs laravel
- [Queues: Getting Started](https://laravel.com/docs/8.x/queues)


## License

This package is free software distributed under the terms of the [MIT license](LICENSE.md).
