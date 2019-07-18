<?php


namespace App;


use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class IntegerRange
{
    /** @var int|null */
    protected $from;

    /** @var int|null */
    protected $to;

    public function __construct(int $from = null, int $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addGetterConstraint('valid', new IsTrue());
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->from <= $this->to ||
            $this->from === null || $this->to === null;
    }

    /**
     * @return int|null
     */
    public function getFrom(): ?int
    {
        return $this->from;
    }

    /**
     * @param int|null $from
     * @return IntegerRange
     */
    public function setFrom(?int $from): IntegerRange
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTo(): ?int
    {
        return $this->to;
    }

    /**
     * @param int|null $to
     * @return IntegerRange
     */
    public function setTo(?int $to): IntegerRange
    {
        $this->to = $to;
        return $this;
    }
}