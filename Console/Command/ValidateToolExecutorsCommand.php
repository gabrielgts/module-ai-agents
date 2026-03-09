<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Console\Command;

use Gtstudio\AiAgents\Api\GetAiToolsListInterface;
use Gtstudio\AiAgents\Model\Tool\ToolExecutorPool;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Lists all AiTools entities and flags which ones are missing a registered PHP executor.
 *
 * Run: bin/magento aiagents:tools:validate
 */
class ValidateToolExecutorsCommand extends Command
{
    public function __construct(
        private readonly GetAiToolsListInterface $getAiToolsList,
        private readonly ToolExecutorPool $executorPool
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('aiagents:tools:validate')
            ->setDescription(
                'List all AI tool entities and flag those missing a registered ToolExecutor.'
            );
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tools = $this->getAiToolsList->execute()->getItems();

        if (empty($tools)) {
            $output->writeln('<comment>No AI tools found in the database.</comment>');
            return Command::SUCCESS;
        }

        $missingCount = 0;

        foreach ($tools as $tool) {
            $code = (string) $tool->getCode();

            if ($this->executorPool->has($code)) {
                $output->writeln(sprintf('<info>[OK]      %s</info>', $code));
            } else {
                $output->writeln(sprintf(
                    '<comment>[MISSING] %s — no ToolExecutor registered in ToolExecutorPool</comment>',
                    $code
                ));
                $missingCount++;
            }
        }

        if ($missingCount > 0) {
            $output->writeln(sprintf(
                PHP_EOL . '<comment>%d tool(s) have no executor. '
                . 'Register them in di.xml under ToolExecutorPool.</comment>',
                $missingCount
            ));
        }

        return Command::SUCCESS;
    }
}
