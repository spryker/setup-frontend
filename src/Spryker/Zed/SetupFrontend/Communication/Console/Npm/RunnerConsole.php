<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Communication\Console\Npm;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @deprecated Will be removed without replacement.
 * Use `frontend:yves:build` for build Yves frontend.
 * Use `frontend:zed:build` for build Zed frontend.
 *
 * @method \Spryker\Zed\Setup\Business\SetupFacadeInterface getFacade()
 * @method \Spryker\Zed\Setup\Communication\SetupCommunicationFactory getFactory()
 */
class RunnerConsole extends Console
{
    public const COMMAND_NAME = 'frontend:npm:run';

    public const NPM_COMMAND_TPL = 'npm run %s';

    public const OPTION_TASK_BUILD_ALL = 'build-all';
    public const OPTION_TASK_BUILD_ALL_SHORT = 'a';

    public const OPTION_TASK_BUILD_CORE = 'build-core';
    public const OPTION_TASK_BUILD_CORE_SHORT = 'c';

    public const OPTION_TASK_BUILD_YVES = 'build-yves';
    public const OPTION_TASK_BUILD_YVES_SHORT = 'y';

    public const OPTION_TASK_BUILD_ZED = 'build-zed';
    public const OPTION_TASK_BUILD_ZED_SHORT = 'z';

    /**
     * @var string[]
     */
    protected $commands = [
        self::OPTION_TASK_BUILD_ALL => 'spy-setup all',
        self::OPTION_TASK_BUILD_CORE => 'spy-setup core',
        self::OPTION_TASK_BUILD_ZED => 'spy-setup zed',
        self::OPTION_TASK_BUILD_YVES => 'spy-setup yves',
    ];

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('This command will execute \'npm run\' with the specified task');
        $this->setHelp(<<<EOM
This command will execute 'npm run' with a specified task.

Example:
 - code:npm --build-all  will build the client resources for the core, the project zed and the project yves code
EOM
        );

        $this->addOption(
            static::OPTION_TASK_BUILD_ALL,
            static::OPTION_TASK_BUILD_ALL_SHORT,
            InputOption::VALUE_NONE,
            'execute \'npm run\' to build all core and project resources'
        );

        $this->addOption(
            static::OPTION_TASK_BUILD_CORE,
            static::OPTION_TASK_BUILD_CORE_SHORT,
            InputOption::VALUE_NONE,
            'execute \'npm run\' to build the core resources of zed'
        );

        $this->addOption(
            static::OPTION_TASK_BUILD_ZED,
            static::OPTION_TASK_BUILD_ZED_SHORT,
            InputOption::VALUE_NONE,
            'execute \'npm run\' to build the project resources of zed'
        );

        $this->addOption(
            static::OPTION_TASK_BUILD_YVES,
            static::OPTION_TASK_BUILD_YVES_SHORT,
            InputOption::VALUE_NONE,
            'execute \'npm run\' to build the project resources of yves'
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = $this->getCommand();

        return $this->runCommand($command);
    }

    /**
     * @return string
     */
    protected function getCommand(): string
    {
        $task = $this->getNpmTask();
        $command = sprintf(static::NPM_COMMAND_TPL, $this->commands[$task]);

        return $command;
    }

    /**
     * @param string $command
     *
     * @return int
     */
    protected function runCommand($command): int
    {
        $this->info('Run command: ' . $command);
        $process = new Process(explode(' ', $command), APPLICATION_ROOT_DIR);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return $process->isSuccessful() ? Console::CODE_SUCCESS : Console::CODE_ERROR;
    }

    /**
     * @return string
     */
    protected function getNpmTask(): string
    {
        $tasks = [
            static::OPTION_TASK_BUILD_ALL,
            static::OPTION_TASK_BUILD_CORE,
            static::OPTION_TASK_BUILD_ZED,
            static::OPTION_TASK_BUILD_YVES,
        ];

        foreach ($tasks as $task) {
            $exists = $this->input->getOption($task);

            if ($exists) {
                return $task;
            }
        }

        return static::OPTION_TASK_BUILD_ALL;
    }
}
