<?php


namespace App\Forms\Types;

use App\IntegerRange;
use App\Item;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemAuthoritiesType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->create('item_authorities', TextType::class, ['by_reference' => true]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        
            
        $data = $form->getData();
        $view->vars['items'] = $data;
        $view->vars['authorities_choices'] = \App\Authority::whereIn('id',array_keys(get_object_vars(json_decode($data))))->orderBy('name', 'asc')->get(['id as key', 'name as value']);
        $view->vars['roles_choices'] = json_encode(array_map(function ($role){
            return  [
                "key" => $role,
                "value"=> trans('authority.role.' . config('authorityRoles')[$role])
            ];
         }, array_keys(config('authorityRoles'))));
    }

    public function getTranslations() {
        return $this->translations;
    }
    public function getBlockPrefix()
    {
        return 'item_authorities';
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'required' => false,
            ]);
    }
}
