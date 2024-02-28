<?php

namespace App\Visit\Form\Type;

use App\Visit\Dto\VisitTypeListItemDto;
use App\Visit\Service\VisitTypeService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitTypeChoiceType extends AbstractType {

  public function __construct(private readonly VisitTypeService $visitTypeService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver
        ->setRequired('companyId')
        ->setAllowedTypes('companyId', 'int')
        ->setDefaults([
            'companyId' => null,
            'choice_value' => fn (?VisitTypeListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?VisitTypeListItemDto $dto) => $dto?->name,
            'choices' => fn (Options $options) => $this->visitTypeService->getList(
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
            fn (?VisitTypeListItemDto $dto) => $dto?->id));
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<VisitTypeListItemDto> $choices
   */
  private function selectChoiceById(array $choices, ?int $id): ?VisitTypeListItemDto {
    if ($id === null) {
      return null;
    }

    $selectedChoice = array_filter($choices, fn (?VisitTypeListItemDto $dto) => $dto?->id === $id);

    return array_key_exists(0, $selectedChoice) ? $selectedChoice[0] : null;
  }
}