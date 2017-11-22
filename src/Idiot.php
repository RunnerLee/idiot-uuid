<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-11
 */

namespace Runner\IdiotUuid;

use Predis\Client;
use Exception;

class Idiot
{
    const REDIS_AVAILABLE_SEEDS = 'coupon:available:seeds';

    const REDIS_SEEDS = 'coupon:seeds';

    /**
     * @var Client
     */
    protected $redis;

    /**
     * Coupon constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->redis = $client;
    }

    /**
     * 初始化种子池, 提出头尾两个种子, 剩下可用码数 2176650000
     *
     * @return void
     */
    public function initSeeds()
    {
        if (0 === $this->redis->exists(static::REDIS_SEEDS)) {
            $this->redis->zadd(static::REDIS_SEEDS, array_fill(1, 43534, 0));
            $this->redis->sadd(static::REDIS_AVAILABLE_SEEDS, range(1, 43534));
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function apply()
    {
        /**
         * 从有效种子池中获取一个有效的种子
         */
        if (is_null($index = $this->redis->srandmember(static::REDIS_AVAILABLE_SEEDS))) {
            throw new Exception('no available seeds');
        }

        /**
         * 获取种子的使用次数
         */
        $score = (int)$this->redis->zscore(static::REDIS_SEEDS, $index);

        /**
         * 计算 code 值
         */
        $number = (int)$index * 50000 + $score;

        /**
         * 自增种子使用次数, 供下次直接使用
         */
        $this->redis->zincrby(static::REDIS_SEEDS, 1, $index);

        /**
         * 如果种子使用次数达到 50000 次, 从有效池中移除
         */
        if (49999 === $score) {
            $this->redis->srem(static::REDIS_AVAILABLE_SEEDS, $index);
        }

        /**
         * 返回三十六进制的 code
         */
        return str_pad(base_convert($number, 10, 36), 6, '0', STR_PAD_LEFT);
    }
}
