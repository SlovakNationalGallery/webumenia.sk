<?php


namespace App\Forms\Types;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelType extends AbstractType
{
    /** @var string */
    protected $className;

    public function buildForm(FormBuilderInterface $builder, array $options) {
        foreach ((new $this->className)->getFillable() as $fillable) {
            $builder->add($fillable);
        }
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => $this->className,
        ]);
    }
}