<?php


namespace App\Forms\Types;


use App\ItemTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemTranslationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('work_type')
            ->add('work_level')
            ->add('topic')
            ->add('subject')
            ->add('measurement')
            ->add('dating')
            ->add('medium')
            ->add('technique')
            ->add('inscription')
            ->add('place')
            ->add('state_edition')
            ->add('gallery')
            ->add('relationship_type')
            ->add('related_work');
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => ItemTranslation::class,
        ]);
    }
}