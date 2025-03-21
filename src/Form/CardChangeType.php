<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CardChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('columnName', ChoiceType::class, [
                'label'       => 'Field to Edit',
                'choices'     => [
                    'Card Name'      => 'name',
                    'Annual Fee (€)' => 'annualFee',
                    'Remarks'        => 'remarks',
                ],
                'attr'        => ['class' => 'form-select'],
                'constraints' => [
                    new NotBlank(['message' => 'Please select a field to edit.']),
                ],
            ])
            ->add('value', TextType::class, [
                'label'       => 'Value',
                'attr'        => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Value cannot be blank.']),
                    new Callback([$this, 'validateValue']),
                ],
            ]);
    }

    public function validateValue($value, ExecutionContextInterface $context): void
    {
        $form = $context->getRoot();
        $columnName = $form->get('columnName')->getData();

        switch ($columnName) {
            case 'name':
                if (strlen($value) < 2 || strlen($value) > 255) {
                    $context->buildViolation('Card name must be between 2 and 255 characters.')
                        ->atPath('value')
                        ->addViolation();
                }
                if (!preg_match('/^[\p{L}0-9\s\-().\']+$/u', $value)) {
                    $context->buildViolation('Card name can only contain letters, numbers, spaces, hyphens (-), parentheses (), dots (.), and apostrophes (\').')
                        ->atPath('value')
                        ->addViolation();
                }
                break;

            case 'annualFee':
                if (!preg_match('/^\d+(\.\d{1,2})?$/', $value) || (float)$value < 0) {
                    $context->buildViolation('Annual fee must be a positive number with up to 2 decimal places.')
                        ->atPath('value')
                        ->addViolation();
                }
                break;

            case 'remarks':
                if (strlen($value) > 1000) {
                    $context->buildViolation('Remarks cannot exceed 1000 characters.')
                        ->atPath('value')
                        ->addViolation();
                }
                break;

            default:
                $context->buildViolation('Invalid field selected.')
                    ->atPath('columnName')
                    ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => null]);
    }
}