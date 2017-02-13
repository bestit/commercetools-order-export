<?php

namespace AppBundle\Command;

use AppBundle\ProgressBarFactory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The export command for orders.
 * @author blange <lange@bestit-online.de>
 * @package AppBundle
 * @subpackage Command
 * @version $id$
 */
class ExportCommand extends ContainerAwareCommand
{
    /**
     * Configures the command.
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('app:export-orders')
            ->setDescription('Exports the found orders to the given export folder.');
    }

    /**
     * Executes the command.
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $orderVisitor = $this->getContainer()->get('app.order_visitor');

        $this->getContainer()->get('app.exporter')->exportOrders(
            $orderVisitor,
            $this->getProgressBar($output, count($orderVisitor))
        );
    }

    /**
     * Returns the progress bar from the factory.
     * @param OutputInterface $output
     * @param int $total
     * @return ProgressBar
     */
    private function getProgressBar(OutputInterface $output, int $total): ProgressBar
    {
        return $this->getProgressBarFactory()->getProgressBar($output, $total);
    }

    /**
     * Returns the factory for the progress bar.
     * @return ProgressBarFactory
     */
    private function getProgressBarFactory(): ProgressBarFactory
    {
        return $this->getContainer()->get('app.progress_bar_factory');
    }

}
