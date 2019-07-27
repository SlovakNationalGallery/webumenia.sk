<?php

namespace App\Forms\Types;

use App\Color;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ColorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function (?Color $model) {
                return $model ? $model->convertTo(Color::TYPE_HEX) : null;
            },
            function (?string $string) {
                if ($string === null) {
                    return null;
                }

                try {
                    return new Color($string, Color::TYPE_HEX);
                } catch (\InvalidArgumentException $e) {
                    throw new TransformationFailedException();
                }
            }
        ));
    }

    public function getParent()
    {
        return TextType::class;
    }
}