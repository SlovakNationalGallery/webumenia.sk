<?php

namespace App\Filter\Forms\Types;

use App\Collection;
use App\Filter\CollectionSearchRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionSearchRequestType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            /** @var CollectionSearchRequest $searchRequest */
            $searchRequest = $event->getData();

            $collections = $this::prepareCollections($searchRequest)->get();

            $getChoiceOptions = function ($attribute) use ($searchRequest, $collections) {
                $choices = $collections
                    // ->get()
                    ->countBy($attribute)
                    ->sort()
                    ->reverse()
                    ->mapWithKeys(function ($count, $item) {
                        $label = sprintf('%s (%d)', $item, $count);
                        return [$label => $item];
                    });

                $value = $searchRequest->get($attribute);

                if ($choices->isEmpty() && $value !== null) {
                    $choices[sprintf('%s (0)', $value)] = $value;
                }

                return [
                    'choices' => $choices,
                    'choice_translation_domain' => false,
                    'placeholder' => sprintf('%s', $attribute),
                    'required' => false,
                ];
            };

            $form
                ->add('author', ChoiceType::class, $getChoiceOptions('author'))
                ->add('type', ChoiceType::class, $getChoiceOptions('type'))
                ->add('sort_by', ChoiceType::class, [
                    'required' => false,
                    'placeholder' => 'sorting.published_at', // $searchRequest->getSearch() !== null ? 'sorting.relevance' : 'sorting.updated_at',
                    'choices' => [
                        'sorting.title' => 'name',
                        'sorting.updated_at' => 'updated_at',
                    ],
                ]);
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if (isset($data['author'])) {
                $form->add('author', ChoiceType::class, [
                    'choices' => [$data['author'] => $data['author']],
                ]);
            }
            if (isset($data['type'])) {
                $form->add('type', ChoiceType::class, [
                    'choices' => [$data['type'] => $data['type']],
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CollectionSearchRequest::class,
            'translation_domain' => 'kolekcie.filter',
            'csrf_protection' => false,
            'method' => 'GET',
        ]);
    }

    public static function prepareCollections(CollectionSearchRequest $searchRequest)
    {
        $collections = Collection::published()->with(['translations', 'items', 'user']);

        if ($searchRequest->getAuthor()) {
            $collections->where('users.name', $searchRequest->getAuthor());
        }
        if ($searchRequest->getType()) {
            $collections->where('type', '=', $searchRequest->getType());
        }
        return $collections;
    }
}
