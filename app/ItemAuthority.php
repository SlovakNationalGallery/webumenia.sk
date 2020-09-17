<?php


namespace App;


use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class ItemAuthority
{
    /** @var array<string>|null */
    protected $authors;

    public function __construct(array $authors)
    {
        $this->authors = $authors;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addGetterConstraint('valid', new IsTrue());
    }

    /**
     * @return int|null
     */
    public function getAuthors(): ?int
    {
        return $this->authors;
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
    public function setTo(?int $to): ItemAuthority
    {
        $this->to = $to;
        return $this;
    }
}