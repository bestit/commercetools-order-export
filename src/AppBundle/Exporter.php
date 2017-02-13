<?php

namespace AppBundle;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Twig_Environment;

/**
 * Exports the given orders.
 * @author blange <lange@bestit-online.de>
 * @package AppBundle
 * @version $id$
 */
class Exporter
{
    /**
     * The used customer factory.
     * @var CustomerFactory
     */
    private $customerFactory = null;

    /**
     * The used file system.
     * @var FilesystemInterface
     */
    private $filesystem = null;

    /**
     * The used view.
     * @var Twig_Environment
     */
    private $view = null;

    /**
     * Exporter constructor.
     * @param CustomerFactory $customerFactory
     * @param FilesystemInterface $filesystem
     * @param Twig_Environment $view
     */
    public function __construct(
        CustomerFactory $customerFactory,
        FilesystemInterface $filesystem,
        Twig_Environment $view
    ) {
        $this
            ->setCustomerFactory($customerFactory)
            ->setFilesystem($filesystem)
            ->setView($view);
    }

    /**
     * Exports the given orders.
     * @param OrderVisitor $orderVisitor
     * @param ProgressBar $bar
     * @return bool
     */
    public function exportOrders(OrderVisitor $orderVisitor, ProgressBar $bar): bool
    {
        $customerFactory = $this->getCustomerFactory();
        $filesystem = $this->getFilesystem();
        $view = $this->getView();

        $bar->start(count($orderVisitor));

        foreach ($orderVisitor as $order) {
            set_time_limit(0);

            $bar->advance();

            $written = $filesystem->put(
                sprintf('order_%s.xml', $order->getId()),
                $view->render(
                    'detail.xml.twig',
                    [
                        'order' => $order,
                        'customer' => $customerFactory->getCustomer($order->getCustomerId()),
                    ]
                )
            );
        }

        $bar->finish();

        return true;
    }

    /**
     * Returns the customer factory.
     * @return CustomerFactory
     */
    private function getCustomerFactory(): CustomerFactory
    {
        return $this->customerFactory;
    }

    /**
     * Returns the file system.
     * @return FilesystemInterface
     */
    private function getFilesystem(): FilesystemInterface
    {
        return $this->filesystem;
    }

    /**
     * Returns the view class.
     * @return Twig_Environment
     */
    private function getView(): Twig_Environment
    {
        return $this->view;
    }

    /**
     * Sets the customer factory.
     * @param CustomerFactory $customerFactory
     * @return Exporter
     */
    private function setCustomerFactory(CustomerFactory $customerFactory): Exporter
    {
        $this->customerFactory = $customerFactory;

        return $this;
    }

    /**
     * Sets the file system.
     * @param FilesystemInterface $filesystem
     * @return Exporter
     */
    private function setFilesystem(FilesystemInterface $filesystem): Exporter
    {
        $this->filesystem = $filesystem;
        return $this;
    }

    /**
     * Sets the view class.
     * @param Twig_Environment $view
     * @return Exporter
     */
    private function setView(Twig_Environment $view): Exporter
    {
        $this->view = $view;

        return $this;
    }
}
