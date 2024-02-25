<?php

namespace App\Specialist\Form\Type;

use App\Specialist\Dto\SpecialistListItemDto;
use App\Specialist\Service\SpecialistService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialistChoiceType extends AbstractType {

  public function __construct(private readonly SpecialistService $specialistService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver
        ->setRequired('companyId')
        ->setAllowedTypes('companyId', 'int')
        ->setDefaults([
            'companyId' => null,
            'choice_value' => fn (?SpecialistListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?SpecialistListItemDto $dto) => $dto?->surname
                . ' '
                . $dto?->firstname,
            'choices' => fn (Options $options) => $this->specialistService->getList(
                $options['companyId'])
        ]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    parent::buildForm($builder, $options);

    $builder->addModelTransformer(
        new CallbackTransformer(
            fn (?int $id) => $this->selectChoiceById(
                is_array($options['choices']) ? $options['choices'] : [],
                $id),
            fn (?SpecialistListItemDto $dto) => $dto?->id));
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<SpecialistListItemDto> $choices
   */
  private function selectChoiceById(array $choices, ?int $id): ?SpecialistListItemDto {
    if ($id === null) {
      return null;
    }

    $selectedChoice = array_filter($choices, fn (?SpecialistListItemDto $dto) => $dto?->id === $id);

    return array_key_exists(0, $selectedChoice) ? $selectedChoice[0] : null;
  }
}