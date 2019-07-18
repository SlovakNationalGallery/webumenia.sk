<?php

namespace App\Forms\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NullableCheckboxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($data) { return $data; },
            function ($data) { return $data ?: null; }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('required', false);
    }

    public function getParent()
    {
        return CheckboxType::class;
    }
}