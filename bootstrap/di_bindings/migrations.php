<?php

use Illuminate\Container\Container;
use Doctrine\Migrations\Tools\Console\Command;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Laminas\ConfigAggregator\ConfigAggregator;
use Doctrine\DBAL\DriverManager;

return static function (Container $container): void {

    $container->singleton(DependencyFactory::class, function (Container $container) {
        /** @var ConfigAggregator $configAggregator */
        $configAggregator = $container[ConfigAggregator::class];
        /** @var array{
         *     migrations: array<string, mixed>,
         *     db: array{database: string, username: string, password: string, host: string, driver: string, port: int}
         *     } $config
         */
        $config = $configAggregator->getMergedConfig();
        /** @var array{
         *     application_name?: string,
         *     charset?: string,
         *     dbname?: string,
         *     defaultTableOptions?: array<string, mixed>,
         *     driver?: 'ibm_db2'|'mysqli'|'oci8'|'pdo_mysql'|'pdo_oci'|'pdo_pgsql'|'pdo_sqlite'|'pdo_sqlsrv'|'pgsql'|'sqlite3'|'sqlsrv',
         *     driverClass?: class-string<Doctrine\DBAL\Driver>,
         *     driverOptions?: array<array-key, mixed>,
         *     host?: string,
         *     keepReplica?: bool,
         *     memory?: bool,
         *     password?: string,
         *     path?: string,
         *     persistent?: bool,
         *     port?: int,
         *     primary?: array{application_name?: string, charset?: string, dbname?: string, defaultTableOptions?: array<string, mixed>, driver?: 'ibm_db2'|'mysqli'|'oci8'|'pdo_mysql'|'pdo_oci'|'pdo_pgsql'|'pdo_sqlite'|'pdo_sqlsrv'|'pgsql'|'sqlite3'|'sqlsrv', driverClass?: class-string<Doctrine\DBAL\Driver>, driverOptions?: array<array-key, mixed>, host?: string, memory?: bool, password?: string, path?: string, persistent?: bool, port?: int, serverVersion?: string, sessionMode?: int, unix_socket?: string, user?: string, wrapperClass?: class-string<Doctrine\DBAL\Connection>},
         *     replica?: array<array-key, array{application_name?: string, charset?: string, dbname?: string, defaultTableOptions?: array<string, mixed>, driver?: 'ibm_db2'|'mysqli'|'oci8'|'pdo_mysql'|'pdo_oci'|'pdo_pgsql'|'pdo_sqlite'|'pdo_sqlsrv'|'pgsql'|'sqlite3'|'sqlsrv', driverClass?: class-string<Doctrine\DBAL\Driver>, driverOptions?: array<array-key, mixed>, host?: string, memory?: bool, password?: string, path?: string, persistent?: bool, port?: int, serverVersion?: string, sessionMode?: int, unix_socket?: string, user?: string, wrapperClass?: class-string<Doctrine\DBAL\Connection>}>,
         *     serverVersion?: string,
         *     sessionMode?: int,
         *     unix_socket?: string,
         *     user?: string,
         *     wrapperClass?: class-string<Doctrine\DBAL\Connection>
         *  } $params */
        $params = [
            'dbname' => $config['db']['database'],
            'user' => $config['db']['username'],
            'password' => $config['db']['password'],
            'host' => $config['db']['host'],
            'driver' => $config['db']['driver'],
            'charset' => 'utf8',
            'port' => $config['db']['port'],
            'schema' => 'public',
        ];

        return DependencyFactory::fromConnection(
            new ConfigurationArray($config['migrations']),
            new ExistingConnection(DriverManager::getConnection($params))
        );
    });

    /** @var DependencyFactory $depFactory */
    $depFactory = $container[DependencyFactory::class];

    $container->singleton(Command\DumpSchemaCommand::class, function () use ($depFactory) {
        return new Command\DumpSchemaCommand($depFactory);
    });

    $container->singleton(Command\ExecuteCommand::class, function () use ($depFactory) {
        return new Command\ExecuteCommand($depFactory);
    });

    $container->singleton(Command\GenerateCommand::class, function () use ($depFactory) {
        return new Command\GenerateCommand($depFactory);
    });

    $container->singleton(Command\LatestCommand::class, function () use ($depFactory) {
        return new Command\LatestCommand($depFactory);
    });

    $container->singleton(Command\ListCommand::class, function () use ($depFactory) {
        return new Command\ListCommand($depFactory);
    });

    $container->singleton(Command\MigrateCommand::class, function () use ($depFactory) {
        return new Command\MigrateCommand($depFactory);
    });

    $container->singleton(Command\RollupCommand::class, function () use ($depFactory) {
        return new Command\RollupCommand($depFactory);
    });

    $container->singleton(Command\StatusCommand::class, function () use ($depFactory) {
        return new Command\StatusCommand($depFactory);
    });

    $container->singleton(Command\SyncMetadataCommand::class, function () use ($depFactory) {
        return new Command\SyncMetadataCommand($depFactory);
    });

    $container->singleton(Command\VersionCommand::class, function () use ($depFactory) {
        return new Command\VersionCommand($depFactory);
    });
};
