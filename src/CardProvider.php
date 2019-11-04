<?php declare(strict_types=1);

namespace MemoryConsole;

use InvalidArgumentException;
use Memory\Card\Card;
use Memory\Card\CardId;
use Memory\Card\Content\ColourContent;
use Memory\Card\Content\ContentId;
use Memory\Contracts\Content;

class CardProvider
{
    private const MAXIMUM_NUMBER_OF_ALPHABETIC_CARDS = 26;

    /**
     * @return Card[]
     */
    public function provideAlphabeticCards(int $numberOfCards): array
    {
        $this->validateNumberOfCards($numberOfCards);

        $alphabet = range('A', 'Z');
        $cards = [];

        for ($i = 1; $i <= $numberOfCards; $i++) {
            $cards[] = $this->createCard($alphabet);
        }

        return $cards;
    }

    private function getUniqueAlphabeticChar(array &$alphabet): string
    {
        $randomKey = array_rand($alphabet, 1);
        $title = $alphabet[$randomKey];
        unset($alphabet[$randomKey]);

        return $title;
    }

    private function getContent(string $title): Content
    {
        $contentId = new ContentId();

        return new ColourContent($contentId, $title, '#fff');
    }

    private function createCard(array &$alphabet): Card
    {
        $title = $this->getUniqueAlphabeticChar($alphabet);
        $content = $this->getContent($title);
        $cardId = new CardId();

        return new Card($cardId, $content);
    }

    private function validateNumberOfCards(int $numberOfCards): void
    {
        if ($this->isGreaterThanMaximumNumber($numberOfCards)) {
            throw new InvalidArgumentException('can not provide more than 26 alphabetic cards');
        }

        if ($this->isOddNumber($numberOfCards)) {
            throw new InvalidArgumentException('number of cards needs to be an even number');
        }
    }

    private function isGreaterThanMaximumNumber(int $numberOfCards): bool
    {
        return $numberOfCards > self::MAXIMUM_NUMBER_OF_ALPHABETIC_CARDS;
    }

    private function isOddNumber(int $numberOfCards): bool
    {
        return $numberOfCards % 2 !== 0;
    }
}
