<?php


namespace App\Forms\Types;


use App\ItemTranslation;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class ItemTranslationType extends ModelType
{
    protected $className = ItemTranslation::class;

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder->add('description', TextareaType::class, [
            'required' => false,
        ]);
    }
}
