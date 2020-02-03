<?php

namespace App\Forms\Types;

use Primal\Color\Color;
use Primal\Color\Parser;
use Primal\Color\UnknownFormatException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ColorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function (?Color $model) {
                return $model ? $model->toRGB()->toHex() : null;
            },
            function (?string $string) {
                if ($string === null) {
                    return null;
                }

                try {
                    return Parser::Parse($string)->toRGB();
                } catch (UnknownFormatException $e) {
                    throw new TransformationFailedException();
                }
            }
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $data = $form->getData();
        $view->vars['color'] = $data ? $data->toHex() : $data;
    }

    public function getBlockPrefix()
    {
        return 'color';
    }

    public function getParent()
    {
        return HiddenType::class;
    }
}
