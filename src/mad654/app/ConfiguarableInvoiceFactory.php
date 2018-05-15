<?php


namespace mad654\app;


use mad654\TimeInvoice\Invoice;
use mad654\TimeInvoice\InvoiceFactory;
use mad654\TimeInvoice\InvoiceNumber;
use mad654\TimeInvoice\SimpleDateInvoiceNumber;
use mad654\TimeInvoice\SimpleInvoiceAddress;
use mad654\TimeInvoice\TaxFreeInvoice;
use mad654\TimeInvoice\WorkingHour;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\NodeInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

class ConfiguarableInvoiceFactory implements InvoiceFactory
{
    private $invoiceClass;

    /**
     * @var InvoiceNumber
     */
    private $lastInvoiceNumber;

    /**
     * @var array
     */
    private $config;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * @var FileLocator
     */
    private $fileLocator;

    /**
     * InvoiceFactory constructor.
     * @param string $invoiceClass
     * @param InvoiceNumber $last
     */
    public function __construct(string $invoiceClass, InvoiceNumber $last) {
        $this->invoiceClass = $invoiceClass;
        $this->lastInvoiceNumber = $last;
        $this->processor = new Processor();
    }

    public static function instance(): InvoiceFactory {
        $factory = new ConfiguarableInvoiceFactory(TaxFreeInvoice::class, new SimpleDateInvoiceNumber());
        $factory->init('etc');

        return $factory;
    }

    public function init(string $configurationDir): void {
        $configDirectories = array($configurationDir);
        $this->fileLocator = new FileLocator($configDirectories);

        $filePath = $this->fileLocator->locate('app.ini', [], true);

        $this->config = $this->processor->process(
            $this->getConfigTree(),
            [ parse_ini_file($filePath, true) ]
        );
    }

    /**
     * @param WorkingHour[] $workingHours
     * @return Invoice
     */
    public function fromWorkingHours(array $workingHours): Invoice {
        return $this->invoiceClass::fromWorkingHours(
            $workingHours,
            $this->nextInvoiceNumber(),
            $this->creator(),
            $this->bankAccount(),
            $this->recipient()
        );
    }

    private function nextInvoiceNumber() {
        return $this->lastInvoiceNumber;
    }

    private function creator() {
        return new SimpleInvoiceAddress(
            $this->config['address']['name'],
            $this->config['address']['address'],
            $this->config['address']['zip'],
            $this->config['address']['city']
        );
    }

    private function bankAccount() {
        return new \mad654\TimeInvoice\PaymentSettings(
            $this->config['bankAccount']['dueDateText'],
            $this->config['bankAccount']['accountOwner'],
            $this->config['bankAccount']['bank'],
            $this->config['bankAccount']['iban'],
            $this->config['bankAccount']['bic']
        );
    }

    private function recipient() {
        return new \mad654\TimeInvoice\SimpleInvoiceAddress(
            $this->config['recipient']['name'],
            $this->config['recipient']['address'],
            $this->config['recipient']['zip'],
            $this->config['recipient']['city']
        );
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\NodeInterface
     */
    private function getConfigTree(): NodeInterface {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('time-invoice');

        $rootNode->children()
            ->arrayNode('address')
                ->children()
                    ->scalarNode('name')->isRequired()->end()
                    ->scalarNode('address')->isRequired()->end()
                    ->scalarNode('zip')->isRequired()->end()
                    ->scalarNode('city')->isRequired()->end()
                ->end()
            ->end()

            ->arrayNode('bankAccount')
                ->children()
                    ->scalarNode('dueDateText')->isRequired()->end()
                    ->scalarNode('accountOwner')->isRequired()->end()
                    ->scalarNode('bank')->isRequired()->end()
                    ->scalarNode('iban')->isRequired()->end()
                    ->scalarNode('bic')->isRequired()->end()
                ->end()
            ->end()

            ->arrayNode('recipient')
                ->children()
                    ->scalarNode('name')->isRequired()->end()
                    ->scalarNode('address')->isRequired()->end()
                    ->scalarNode('zip')->isRequired()->end()
                    ->scalarNode('city')->isRequired()->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder->buildTree();
    }
}