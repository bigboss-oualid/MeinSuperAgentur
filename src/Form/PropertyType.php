<?php

namespace App\Form;

use App\Entity\Property;
use App\Entity\Specification;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price')
            ->add('heat', ChoiceType::class, [
            	'choices' => $this->getChoices()
            ])
	        ->add('specifications', EntityType::class, [
	        	'required'     => false,
	        	'class'        => Specification::class,
		        'choice_label' => 'name',
		        'multiple'     => ' true'
	        ])
	        ->add('pictureFiles', FileType::class, [
	        	'required' => false,
		        'multiple' => true
	        ])
            ->add('city')
            ->add('address')
            ->add('postal_code')
	        ->add('lat', HiddenType::class)
	        ->add('lng', HiddenType::class)
            ->add('sold')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
	        'translation_domain' => 'forms'
        ]);
    }

	/**
	 * Invers key's and value'S Array
	 * @return array
	 */
    private function getChoices(): array
	{
		$choices =Property::HEAT;
		$output = [];

		foreach($choices as $k => $v)
		{
			$output[$v] = $k;
		}
		return $output;
	}
}
