<?php

namespace App\Filter;

use App\IntegerRange;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class AuthorityFilter extends AbstractFilter
{
    /** @var IntegerRange */
    protected $years;

    protected $role;

    protected $nationality;

    protected $place;

    protected $firstLetter;

    protected $filterables = [
        'role',
        'nationality',
        'place',
    ];

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addGetterConstraint('years', new Valid());
    }

    public function getYears(): ?IntegerRange
    {
        return $this->years;
    }

    public function setYears(?IntegerRange $years): AuthorityFilter
    {
        $this->years = $years;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): AuthorityFilter
    {
        $this->role = $role;
        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): AuthorityFilter
    {
        $this->nationality = $nationality;
        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): AuthorityFilter
    {
        $this->place = $place;
        return $this;
    }

    public function getFirstLetter(): ?string
    {
        return $this->firstLetter;
    }

    public function setFirstLetter(?string $firstLetter): AuthorityFilter
    {
        $this->firstLetter = $firstLetter;
        return $this;
    }

    public function __get($name)
    {
        if ($name === 'years-range') {
            return $this->getYears();
        }

        throw new \RuntimeException();
    }

    public function __set($name, $value)
    {
        if ($name === 'years-range') {
            $this->setYears($value);
        } else {
            throw new \RuntimeException();
        }
    }
}