<?php

namespace App\Tests\Common\Twig\Extension;

use App\Common\Twig\Extension\YesOrNoExtension;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class YesOrNoExtensionTest extends TestCase {

  private TranslatorInterface $translator;

  private YesOrNoExtension $yesOrNoExtension;

  public function setUp(): void {
    $this->translator = $this->createMock(TranslatorInterface::class);

    $this->yesOrNoExtension = new YesOrNoExtension($this->translator);
  }

  /**
   * @test
   */
  public function givenFalse_whenYesOrNo_shouldReturnNo(): void {
    // given
    $value = false;
    $no = 'no';

    $this->translator
      ->expects(static::once())
      ->method('trans')
      ->with('no')
      ->willReturn($no);

    // when
    $result = $this->yesOrNoExtension->yesOrNo($value);

    // then
    Assert::assertEquals($result, $no);
  }

  /**
   * @test
   */
  public function givenTrue_whenYesOrNo_shouldReturnTrue(): void {
    // given
    $value = true;
    $yes = 'yes';

    $this->translator
        ->expects(static::once())
        ->method('trans')
        ->with('yes')
        ->willReturn($yes);

    // when
    $result = $this->yesOrNoExtension->yesOrNo($value);

    // then
    Assert::assertEquals($result, $yes);
  }
}