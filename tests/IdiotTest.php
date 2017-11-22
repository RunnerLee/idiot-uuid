<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-11
 */

use Runner\IdiotUuid\Idiot;
use Predis\Client;

class IdiotTest extends \PHPUnit_Framework_TestCase
{

    public function testApply()
    {
        $client = new Client([
            'host' => '127.0.0.1',
            'port' => '6379',
            'auth' => null,
            'database' => 1,
        ]);

        $idiot = new Idiot($client);

        $idiot->initSeeds();

        $code = $idiot->apply();

        $this->assertRegExp('/^[a-z0-9]{6}$/', $code);

        $client->del([
            Idiot::REDIS_SEEDS,
            Idiot::REDIS_AVAILABLE_SEEDS,
        ]);
    }

}
