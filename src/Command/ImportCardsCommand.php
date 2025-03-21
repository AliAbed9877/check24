<?php
namespace App\Command;

use App\Service\CardService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-credit-cards',
    description: 'Imports credit card data from the webservice'
)]
class ImportCardsCommand extends Command
{
    public function __construct(private readonly CardService $cardService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cardService->importOrUpdateCardsFromWebserviceToLocalSystem('https://tools.financeads.net/webservice.php?wf=1&format=xml&calc=kreditkarterechner&country=ES');
        $output->writeln('Credit cards imported successfully');
        return Command::SUCCESS;
    }
}