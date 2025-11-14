<?php

namespace App\Form;

use App\Entity\Coupon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code du coupon',
                'attr' => [
                    'class' => 'form-control form-control-glass',
                    'placeholder' => 'Ex: PROMO10',
                ],
                'help' => 'Le code sera automatiquement converti en majuscules',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de réduction',
                'choices' => [
                    'Pourcentage (%)' => 'percentage',
                    'Montant fixe (€)' => 'fixed',
                ],
                'attr' => [
                    'class' => 'form-select form-control-glass',
                ],
            ])
            ->add('value', NumberType::class, [
                'label' => 'Valeur',
                'attr' => [
                    'class' => 'form-control form-control-glass',
                    'step' => '0.01',
                    'min' => '0',
                ],
                'help' => 'Pourcentage (ex: 10 pour 10%) ou montant en euros',
            ])
            ->add('minPurchaseAmount', NumberType::class, [
                'label' => 'Montant minimum d\'achat (€)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-glass',
                    'step' => '0.01',
                    'min' => '0',
                ],
                'help' => 'Montant minimum pour utiliser le coupon (optionnel)',
            ])
            ->add('maxDiscountAmount', NumberType::class, [
                'label' => 'Réduction maximale (€)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-glass',
                    'step' => '0.01',
                    'min' => '0',
                ],
                'help' => 'Montant maximum de réduction (optionnel)',
            ])
            ->add('usageLimit', IntegerType::class, [
                'label' => 'Limite d\'utilisation',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-glass',
                    'min' => '1',
                ],
                'help' => 'Nombre maximum d\'utilisations (optionnel)',
            ])
            ->add('validFrom', DateTimeType::class, [
                'label' => 'Valide à partir de',
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'attr' => [
                    'class' => 'form-control form-control-glass',
                ],
            ])
            ->add('validUntil', DateTimeType::class, [
                'label' => 'Valide jusqu\'au',
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'attr' => [
                    'class' => 'form-control form-control-glass',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coupon::class,
        ]);
    }
}
