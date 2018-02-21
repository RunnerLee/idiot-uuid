<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-02
 */

namespace Runner\IdiotUuid;

class Code
{
    /**
     * @var int
     */
    protected $index;

    /**
     * @var int
     */
    protected $score;

    /**
     * @var int
     */
    protected $number;

    /**
     * Code constructor.
     *
     * @param int $index
     * @param int $score
     * @param int $number
     */
    public function __construct($index, $score, $number)
    {
        $this->index = $index;
        $this->score = $score;
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return str_pad(base_convert($this->number, 10, 36), 6, '0', STR_PAD_LEFT);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCode();
    }
}
