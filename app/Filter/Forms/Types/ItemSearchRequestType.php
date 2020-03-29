<?php

namespace App\Filter\Forms\Types;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Filter\ItemSearchRequest;
use App\Forms\Types\ColorType;
use App\Forms\Types\CommaSeparatedIntegerRangeType;
use App\Forms\Types\NullableCheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemSearchRequestType extends AbstractType
{
    protected $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('search', HiddenType::class, ['required' => false])
            ->add('color', ColorType::class, ['required' => false])
            ->add('credit', HiddenType::class, ['required' => false])
            ->add('has_image', NullableCheckboxType::class)
            ->add('has_iip', NullableCheckboxType::class)
            ->add('is_free', NullableCheckboxType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            /** @var ItemSearchRequest $searchRequest */
            $searchRequest = $event->getData();

            $getChoiceOptions = function ($attribute) use ($searchRequest) {
                $choices = $this->itemRepository->listValues($attribute, $searchRequest);
                $value = $searchRequest->get($attribute);

                if ($choices->isEmpty() && $value !== null) {
                    $choices[sprintf('%s (0)', $value)] = $value;
                }

                return [
                    'choices' => $choices,
                    'choice_translation_domain' => false,
                    'placeholder' => sprintf('item.filter.%s', $attribute),
                    'required' => false,
                ];
            };

            $form->add('years-range', CommaSeparatedIntegerRangeType::class, [
                    'min' => $this->itemRepository->getMinimum('date_earliest') ?? 0,
                    'max' => $this->itemRepository->getMaximum('date_latest') ?? (int)date('Y'),
                    'required' => false,
                ])
                ->add('author', ChoiceType::class, $getChoiceOptions('author'))
                ->add('gallery', ChoiceType::class, $getChoiceOptions('gallery'))
                ->add('work_type', ChoiceType::class, $getChoiceOptions('work_type'))
                ->add('topic', ChoiceType::class, $getChoiceOptions('topic'))
                ->add('tag', ChoiceType::class, $getChoiceOptions('tag'))
                ->add('technique', ChoiceType::class, $getChoiceOptions('technique'))
                ->add('sort_by', ChoiceType::class, [
                    'required' => false,
                    'placeholder' => $searchRequest->getSearch() !== null ? 'sorting.relevance' : 'sorting.updated_at',
                    'choices' => [
                        'sorting.created_at' => 'created_at',
                        'sorting.title' => 'title',
                        'sorting.author' => 'author',
                        'sorting.newest' => 'newest',
                        'sorting.oldest' => 'oldest',
                        'sorting.view_count' => 'view_count',
                    ]
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
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ItemSearchRequest::class,
            'translation_domain' => 'item.filter',
            'csrf_protection' => false,
            'method' => 'GET',
        ]);
    }
}