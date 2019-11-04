<?php declare(strict_types=1);

namespace MemoryConsole;

use Memory\Game;
use Memory\Pair;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Server extends Command
{
    public const NAME = 'game-server';

    /**
     * @var CardProvider
     */
    private $cardProvider;

    public function __construct(CardProvider $cardProvider, string $name = null)
    {
        parent::__construct($name);
        $this->cardProvider = $cardProvider;
    }

    protected function configure()
    {
        parent::configure();
        $this->setName(self::NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Welcome to the Memory Console Game');

        $game = $this->createGame(8);
        $cards = $game->cards();
        shuffle($cards);
        $rows = [];
        foreach (array_chunk($cards, 4) as $batch) {
            $row = [];
            foreach ($batch as $card) {
                $row[] = $card->title();
            }
            $rows[] = $row;
        }


        $io->table([], $rows);

        $io->text('game created');
    }

    protected function createGame(int $numberOfCards): Game
    {
        $cards = $this->cardProvider->provideAlphabeticCards($numberOfCards);
        $pairs = [];
        foreach ($cards as $card) {
            $pairs[] = new Pair($card, clone $card);
        }

        return new Game(...$pairs);
    }

    private function rewrite(OutputInterface $output, string $message): void
    {
    }

    private function spinner(OutputInterface $output): void
    {
        while (true) {
            $this->rewrite($output, '\   .  ');
            $this->rewrite($output, '|   . .');
            $this->rewrite($output, '/   .. ');
            $this->rewrite($output, '-    ..');
        }
    }
}
