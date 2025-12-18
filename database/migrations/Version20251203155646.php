<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251203155646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $tableJobs = $schema->createTable('illuminate_jobs');
        $tableJobs->addColumn('id', 'bigint', ['autoincrement' => true]);
        $tableJobs->addColumn('queue', 'string');
        $tableJobs->addColumn('payload', 'text');
        $tableJobs->addColumn('attempts', 'integer');
        $tableJobs->addColumn('reserved_at', 'integer')->setNotnull(false);
        $tableJobs->addColumn('available_at', 'bigint');
        $tableJobs->addColumn('created_at', 'bigint')
            ->setDefault('round(extract(epoch from now()))');
        $tableJobs->addIndex(['queue']);

        $tableFailedJobs = $schema->createTable('illuminate_failed_jobs');
        $tableFailedJobs->addColumn('id', 'bigint', ['autoincrement' => true]);
        $tableFailedJobs->addColumn('uuid', 'string');
        $tableFailedJobs->addColumn('connection', 'text');
        $tableFailedJobs->addColumn('queue', 'text');
        $tableFailedJobs->addColumn('payload', 'text');
        $tableFailedJobs->addColumn('exception', 'text');
        $tableFailedJobs->addColumn('failed_at', 'bigint')
            ->setDefault('round(extract(epoch from now()))');
        $tableFailedJobs->addUniqueIndex(['uuid']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('illuminate_jobs');
        $schema->dropTable('illuminate_failed_jobs');
    }
}
