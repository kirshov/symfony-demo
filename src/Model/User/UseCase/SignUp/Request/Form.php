<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', Type\TextType::class, [
				'label' => 'First Name',
			])
			->add('lastName', Type\TextType::class, [
				'label' => 'Last Name',
				'required' => false,
			])
            ->add('email', Type\EmailType::class)
			->add('password', Type\PasswordType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
			'translation_domain' => 'users',
        ]);
    }
}
