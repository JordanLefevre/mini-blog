<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
                    'label'  => 'Titre',
                    'attr'=> array('class'=>'validate')
                ))
                ->add('content', TextareaType::class, array(
                    'label'  => 'Contenu',
                    'attr'=> array('class'=>'tinymce')
                ))
                ->add('category',EntityType::class, array(
                  'class' => 'AppBundle:Category',
                  'choice_label' => 'name',
                  'placeholder' => 'Choisir une catégorie',
                  'label' => 'Catégorie',
                  'attr'=> array('class'=>'validate')
                ))
                ->add('tags',EntityType::class, array(
                  'class' => 'AppBundle:Tag',
                  'choice_label' => 'name',
                  'expanded' => false,
                  'multiple' => true,
                  'attr'=> array('class'=>'validate')
                ))
                ->add('publicationDate',TextType::class, array(
                  'label'  => 'Date de parution',
                  'attr'=> array('class'=>'datepicker'),
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_article';
    }


}
