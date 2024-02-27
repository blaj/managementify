<?php

namespace App\Client\Form\Type;

use App\Client\Dto\ClientListItemDto;
use App\Client\Service\ClientService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientChoiceType extends AbstractType {

  public function __construct(private readonly ClientService $clientService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver
        ->setRequired('companyId')
        ->setAllowedTypes('companyId', 'int')
        ->setDefaults([
            'companyId' => null,
            'choice_value' => fn (?ClientListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?ClientListItemDto $dto) => $dto?->surname
                . ' '
                . $dto?->firstname,
            'choices' => fn (Options $options) => $this->clientService->getList(
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
            fn (?ClientListItemDto $dto) => $dto?->id));
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<ClientListItemDto> $choices
   */
  private function selectChoiceById(array $choices, ?int $id): ?ClientListItemDto {
    if ($id === null) {
      return null;
    }

    $selectedChoice = array_filter($choices, fn (?ClientListItemDto $dto) => $dto?->id === $id);

    return array_key_exists(0, $selectedChoice) ? $selectedChoice[0] : null;
  }
}