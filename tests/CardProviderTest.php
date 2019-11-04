<?php declare(strict_types=1);

namespace MemoryConsole\Test;

use InvalidArgumentException;
use Memory\Contracts\Card;
use MemoryConsole\CardProvider;
use PHPUnit\Framework\TestCase;

class CardProviderTest extends TestCase
{
    public function test_provide_more_than_26_alphabetic_cards_throws_exception(): void
    {
        $provider = new CardProvider();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('can not provide more than 26 alphabetic cards');
        $provider->provideAlphabeticCards(27);
    }

    /**
     * @dataProvider provideOddNumbers
     */
    public function test_provide_odd_alphabetic_cards_throws_exception(int $numberOfCards): void
    {
        $provider = new CardProvider();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('number of cards needs to be an even number');
        $provider->provideAlphabeticCards($numberOfCards);
    }

    public function provideOddNumbers(): array
    {
        return [
            [1],
            [3],
            [5],
            [11],
            [19],
            [25],
        ];
    }

    public function test_provide_alphabetic_cards_are_unique_alphabetic_chars(): void
    {
        $provider = new CardProvider();
        $cards = $provider->provideAlphabeticCards(4);
        $titles = array_map(function (Card $card) {
            return $card->title();
        }, $cards);

        $unique = array_unique($titles);
        $this->assertSame($unique, $titles, 'no duplicates found');

        $alphabet = range('A', 'Z');
        foreach ($titles as $title) {
            $this->assertTrue(in_array($title, $alphabet));
        }
    }
    public function test_provide_alphabetic_cards_are_randomized(): void
    {
        $provider = new CardProvider();
        $cards = $provider->provideAlphabeticCards(26);
        $titles = array_map(function (Card $card) {
            return $card->title();
        }, $cards);

        $unique = array_unique($titles);
        $this->assertSame($unique, $titles, 'no duplicates found');
        $alphabet = range('A', 'Z');
        $this->assertNotSame($alphabet, $titles);
    }
}
