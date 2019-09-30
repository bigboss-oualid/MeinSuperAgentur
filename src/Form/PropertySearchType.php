<?php

namespace App\Form;

use App\Entity\PropertySearch;
use App\Entity\Specification;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPrice', IntegerType::class, [
            	'required' => false,
	            'label' => false,
	            'attr' => [
	            	'placeholder' => 'Maximales Budget'
	            ]
            ])
            ->add('minSurface', IntegerType::class, [
	            'required' => false,
	            'label' => false,
	            'attr' => [
		            'placeholder' => 'Minimale Wohnfläche'
	            ]
            ])
	        ->add('specifications', EntityType::class, [
				'required'     => false,
		        'label'        => false,
		        'class'        => Specification::class,
		        'choice_label' => 'name',
		        'multiple'       => true
	        ])
	        ->add('address', null, [
		        'label'   => false,
		        'required'=> false,
	        ])
	        ->add('distance', ChoiceType::class, [
	        	'label'   => false,
	        	'required'=> false,
	        	'choices' => [
	        		'10 Km' => 10,
			        '15 Km' => 15,
			        '20 Km' => 20,
			        '30 Km' => 30,
			        '50 Km' => 50,
			        '100 Km'=> 100,
			        '150 Km'=> 150,
			        '200 Km'=> 200,
		        ],
		        'empty_data' => '10',
	        ])
	        ->add('lat', HiddenType::class)
	        ->add('lng', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
	        'method' => 'get',
	        'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
	    return '';
    }
}
