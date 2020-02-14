<?php


namespace App\Forms\Types;


use App\IntegerRange;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommaSeparatedIntegerRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new CallbackTransformer(
            function ($data) {
                if (!$data instanceof IntegerRange) {
                    return null;
                }

                return sprintf('%d,%d', $data->getFrom(), $data->getTo());
            },
            function ($data) {
                if (!is_string($data)) {
                    return null;
                }

                $exploded = explode(',', $data);
                if (count($exploded) !== 2) {
                    return null;
                }

                $range = new IntegerRange((int)$exploded[0], (int)$exploded[1]);
                if (!$range->isValid()) {
                    return null;
                }

                return $range;
            }
        ));

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            $data = $event->getData();
            if (!$data) {
                return;
            }

            if ($data->getFrom() < $options['min'] || $data->getFrom() > $options['max']) {
                $data->setFrom(null);
            }

            if ($data->getTo() < $options['min'] || $data->getTo() > $options['max']) {
                $data->setTo(null);
            }
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $data = $form->getData();
        $view->vars['from'] = $data && $data->getFrom() !== null ? $data->getFrom() : $options['min'];
        $view->vars['to'] = $data && $data->getTo() !== null ? $data->getTo() : $options['max'];
        $view->vars['min'] = $options['min'];
        $view->vars['max'] = $options['max'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['min', 'max'])
            ->setAllowedTypes('min', 'int')
            ->setAllowedTypes('max', 'int')
            ->setDefaults([
                'data_class', IntegerRange::class,
                'required' => false,
            ]);
    }

    public function getBlockPrefix()
    {
        return 'comma_separated_integer_range';
    }

    public function getParent()
    {
        return TextType::class;
    }
}