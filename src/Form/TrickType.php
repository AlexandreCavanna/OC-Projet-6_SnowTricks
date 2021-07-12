<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\UX\Dropzone\Form\DropzoneType;

class TrickType extends AbstractType
{

    private string $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom', 'required' => false])
            ->add('description', TextareaType::class, ['required' => false])
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
                'multiple' => true,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '1024k',
                                'maxSizeMessage' => 'Le poids de l\'image ne doit pas dépasser 1 Mo.',
                                'mimeTypesMessage' => 'Veuillez upload une image au format jpeg ou png.',
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/png'
                                ]
                            ]),
                        ],
                    ]),
                ]
            ])
            ->add('videos', CollectionType::class, [
                'label' => 'Video(s)',
                'entry_type' => VideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
            ])
        ;

        $builder->get('coverImage')->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $coverImage = $event->getData();

            if ($coverImage === null && $event->getForm()->getData() === null) {
                $event->setData(
                    new UploadedFile(
                        $this->targetDirectory.'/placeholder/trick-placeholder.jpg',
                        'trick-placeholder.jpg'
                    )
                );
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
