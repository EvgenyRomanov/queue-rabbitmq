<?php

use Laminas\ConfigAggregator\ConfigAggregator;
use Symfony\Component\Console\Application;
use Illuminate\Container\Container;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\LazyCommand;
use Symfony\Component\Console\Command\Command;

return static function (Container $container, Application $app): void {
    /** @var ConfigAggregator $configAggregator */
    $configAggregator = $container[ConfigAggregator::class];
    $config = $configAggregator->getMergedConfig();

    $vendorCommands = [
        \Doctrine\Migrations\Tools\Console\Command\DumpSchemaCommand::class,
        \Doctrine\Migrations\Tools\Console\Command\ExecuteCommand::class,
        \Doctrine\Migrations\Tools\Console\Command\GenerateCommand::class,
        \Doctrine\Migrations\Tools\Console\Command\LatestCommand::class,
        \Doctrine\Migrations\Tools\Console\Command\ListCommand::class,
        \Doctrine\Migrations\Tools\Console\Command\MigrateCommand::class,
        \Doctrine\Migrations\Tools\Console\Command\RollupCommand::class,
        \Doctrine\Migrations\Tools\Console\Command\StatusCommand::class,
        \Doctrine\Migrations\Tools\Console\Command\SyncMetadataCommand::class,
        \Doctrine\Migrations\Tools\Console\Command\VersionCommand::class,
    ];

    foreach ($vendorCommands as $command) {
        $app->add($container[$command]);
    }

    /**
     * @var iterable<SplFileInfo> $lazyCommands
     */
    $lazyCommands = new CallbackFilterIterator(
        new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(__DIR__ . '/../src/Infrastructure/ConsoleCommand', FilesystemIterator::SKIP_DOTS)
        ),
        static fn(SplFileInfo $file) => $file->isFile() && str_ends_with($file->getFilename(), 'Command.php')
    );

    foreach ($lazyCommands as $command) {
        $file = $command->getRealPath();

        $beforeClassesCount = count(get_declared_classes());
        require $file;
        $newClasses = array_slice(get_declared_classes(), $beforeClassesCount);

        foreach ($newClasses as $class) {
            if (!is_a($class, Command::class, true)) {
                continue;
            }

            $classRef = new ReflectionClass($class);
            $attributeRefs = $classRef->getAttributes();

            foreach ($attributeRefs as $attributeRef) {
                if ($attributeRef->getName() === AsCommand::class) {
                    $attribute = $attributeRef->newInstance();

                    $app->add(new LazyCommand(
                        $attribute->name,
                        [],
                        $attribute->description,
                        false,
                        static fn (): Command => $container[$class],
                    ));
                }
            }
        }
    }
};
