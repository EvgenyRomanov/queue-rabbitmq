<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251021211605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('trouble_tickets');
        $table->addColumn('id', 'bigint', ['autoincrement' => true]);
        $table->addColumn('title', 'string');
        $table->addColumn('status', 'string');
        $table->addColumn('message', 'string');
        $table->addColumn('created_at', 'datetime', ['notnull' => false]);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('trouble_tickets');
    }
}
