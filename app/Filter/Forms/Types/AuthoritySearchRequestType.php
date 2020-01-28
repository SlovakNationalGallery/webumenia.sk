<?php

namespace App\Filter\Forms\Types;

use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Filter\AuthoritySearchRequest;
use App\Forms\Types\CommaSeparatedIntegerRangeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthoritySearchRequestType extends AbstractType
{
    protected $authorityRepository;

    public function __construct(AuthorityRepository $authorityRepository)
    {
        $this->authorityRepository = $authorityRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $alphabet = range('A', 'Z');
        $builder->add('first_letter', ChoiceType::class, [
                'choices' => array_combine($alphabet, $alphabet),
                'required' => false,
                'expanded' => true,
                'placeholder' => false,
                'choice_translation_domain' => false,
            ])
            ->add('sort_by', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'sorting.items_with_images_count',
                'choices' => [
                    'sorting.name' => 'name',
                    'sorting.birth_year' => 'birth_year',
                    'sorting.items_count' => 'items_count',
                ]
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var AuthoritySearchRequest $searchRequest */
            $searchRequest = $event->getData();
            $form = $event->getForm();

            $getChoiceOptions = function ($attribute) use ($searchRequest) {
                $choices = $this->authorityRepository->listValues($attribute, $searchRequest);
                $value = $searchRequest->get($attribute);

                if (!$choices && $value !== null) {
                    $choices = [sprintf('%s (0)', $value) => $value];
                }

                return [
                    'choices' => $choices,
                    'placeholder' => sprintf('authority.filter.%s', $attribute),
                    'required' => false,
                    'choice_translation_domain' => false,
                ];
            };

            $form->add('years-range', CommaSeparatedIntegerRangeType::class, [
                    'min' => $this->authorityRepository->getMinimum('birth_year') ?? 0,
                    'max' => $this->authorityRepository->getMaximum('death_year') ?? (int)date('Y'),
                    'required' => false,
                ])
                ->add('role', ChoiceType::class, $getChoiceOptions('role'))
                ->add('nationality', ChoiceType::class, $getChoiceOptions('nationality'))
                ->add('place', ChoiceType::class, $getChoiceOptions('place'));
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AuthoritySearchRequest::class,
            'translation_domain' => 'authority.filter',
            'csrf_protection' => false,
            'method' => 'GET'
        ]);
    }
}