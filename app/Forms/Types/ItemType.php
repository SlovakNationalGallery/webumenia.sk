<?php


namespace App\Forms\Types;

use App\Authority;
use App\Item;
use App\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ItemType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('translations', CollectionType::class, [
                'entry_type' => ItemTranslationType::class,
            ])
            ->add('description_user_id', ChoiceType::class, [
                'choices' => User::pluck('id', 'username'),
            ])
            ->add('identifier')
            ->add('author')

            ->add('tags', ChoiceType::class, [
                'choices' => Item::existingTags()->pluck('name', 'name')->toArray(),
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('date_earliest')
            ->add('date_latest')
            ->add('lat')
            ->add('lng')
            ->add('related_work_order')
            ->add('related_work_total')
            ->add('acquisition_date')
            ->add('credit')
            ->add('primary_image', FileType::class, [
                'mapped' => false,
                'required' => $options['new'],
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ItemImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ])
            ->get('author')->resetViewTransformers();

        // set empty translations
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options) {
                $data = $event->getData();

                foreach ($options['locales'] as $locale) {
                    $data->translateOrNew($locale);
                }
            }
        );

        // set current tags to selected
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                $options = $form['tags']->getConfig()->getOptions();

                $current = $data->tags->pluck('name', 'name')->toArray();
                $options['data'] = $current;

                $form->add('tags', ChoiceType::class, $options);


                // $optionsAuthors = $form['author']->getConfig()->getOptions();
                // $selectedAuthors = explode(';', $data['author']);

                // if ($optionsAuthors['choices'] && isset($selectedAuthors)) {
                //     $selectedAuthors  = array_combine($selectedAuthors, $selectedAuthors);
                //     $optionsAuthors['choices']  = array_merge(
                //         $optionsAuthors['choices'],
                //         $selectedAuthors
                //     );
                //     $optionsAuthors['data'] = $selectedAuthors;
                //     $form->add('author', ChoiceType::class, $optionsAuthors);
                // }
            }
        );

        // allow adding new tags
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if (isset($data['tags'])) {
                    $options = $form['tags']->getConfig()->getOptions();

                    $selected = array_combine($data['tags'], $data['tags']);
                    $options['choices'] += $selected;

                    $form->add('tags', ChoiceType::class, $options);
                }


                // if (isset($data['author'])) {
                //     $optionsAuthor = $form['author']->getConfig()->getOptions();

                //     $selectedAuthor = array_combine($data['author'], $data['author']);
                //     $optionsAuthor['choices'] += $selectedAuthor;

                //     $form->add('author', ChoiceType::class, $optionsAuthor);
                // }
            }
        );
    }

    public function getBlockPrefix()
    {
        return 'item';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'translation_domain' => 'item',
        ]);

        $resolver->setRequired('new');
        $resolver->setAllowedTypes('new', 'bool');

        $resolver->setRequired('locales');
        $resolver->setAllowedTypes('locales', 'string[]');
    }
}
