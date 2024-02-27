<?php

namespace App\User\Form\Type;

use App\User\Dto\RoleListItemDto;
use App\User\Service\RoleService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleChoiceType extends AbstractType {

  public function __construct(private readonly RoleService $roleService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver
        ->setRequired('companyId')
        ->setAllowedTypes('companyId', 'int')
        ->setDefaults([
            'companyId' => null,
            'choice_value' => fn (?RoleListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?RoleListItemDto $dto) => $dto?->name,
            'choices' => fn (Options $options) => $this->roleService->getList(
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
            fn (?RoleListItemDto $dto) => $dto?->id));
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<RoleListItemDto> $choices
   */
  private function selectChoiceById(array $choices, ?int $id): ?RoleListItemDto {
    if ($id === null) {
      return null;
    }

    $selectedChoice = array_filter($choices, fn (?RoleListItemDto $dto) => $dto?->id === $id);

    return array_key_exists(0, $selectedChoice) ? $selectedChoice[0] : null;
  }
}