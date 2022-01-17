<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword\Reset;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('password', Type\PasswordType::class)
	        ->add('repassword', Type\PasswordType::class, [
	        	'label' => 'Repeat password'
	        ]);
    }

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'translation_domain' => 'users',
		]);
	}
}
