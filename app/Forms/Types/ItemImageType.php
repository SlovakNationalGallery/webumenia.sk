<?php

namespace App\Forms\Types;


use App\ItemImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemImageType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('iipimg_url');
        $builder->add('order', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => ItemImage::class,
        ]);
    }
}