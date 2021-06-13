<?php

namespace App\Form;

use App\Entity\Picture;
use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $trick = new Trick();
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('description', TextareaType::class)
            ->add('label', ChoiceType::class, [
                'label' => 'Groupe',
                'choices' => [
                    'Flips' => 'Flips',
                    'Rotations désaxées' => 'Rotations désaxées',
                    'Slides' => 'Slides',
                    'Grabs' => 'Grabs',
                 ],
            ])
            ->add('coverImage', DropzoneType::class, [
                'label' => 'Image de couverture',
                'attr' => ['placeholder' => 'Glisser déposer / cliquer sur une image'],
                'data_class' => null,
                'required' => false,
                'mapped' => false,
            ])
            ->add('pictures', DropzoneType::class, [
                'attr' => [
                    'data-controller' => 'hello',
                    'placeholder' => 'Glisser déposer / cliquer sur une ou plusieurs images',
                    'class' => 'mb-3',
                ],
                'label' => 'Image(s)',
                'required' => false,
                'mapped' => false,
                'multiple' => true
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
