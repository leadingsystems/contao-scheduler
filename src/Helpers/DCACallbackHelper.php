<?php

namespace LeadingSystems\ContaoSchedulerBundle\Helpers;

use Contao\DataContainer;
use Contao\Input;
use Cron\CronExpression;
use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DCACallbackHelper
{
    private iterable $schedulableServices;
    private Connection $connection;
    private ContainerInterface $container;

    public function __construct(iterable $schedulableServices, Connection $connection, ContainerInterface $container)
    {
        $this->schedulableServices = $schedulableServices;
        $this->connection = $connection;
        $this->container = $container;
    }

    public function cronExpressionOnBeforeSubmit($values): array
    {
        $encodedScriptClass = \Contao\Input::post('scriptToExecute');
        $scriptClass = html_entity_decode((string) $encodedScriptClass);

        $cronValue = \Contao\Input::post('cronExpression');

        if ($scriptClass && $this->container->has($scriptClass)) {
            $serviceInstance = $this->container->get($scriptClass);

            if (method_exists($serviceInstance, 'getCronExpression')) {
                $cronValue = $serviceInstance->getCronExpression();
            }
        }

        if (empty($cronValue)) {
            /*
             * after we switch to an editable cronExpression field this will be saved at least one time without any
             * chance to edit it so we cant throw an error here. We set a default value of one time a day.
            */
            $cronValue = "0 0 * * *";
        }

        if (!\Cron\CronExpression::isValidExpression($cronValue)) {
            throw new \Exception($GLOBALS['TL_LANG']['tl_ls_scheduler_job']['misc']['invalidCronExpressionErrorMessage']);
        }

        $values['cronExpression'] = $cronValue;

        return $values;
    }

    public function getSchedulerJobBackendListLabel(array $row, string $label, DataContainer $dc, array $labels): string
    {
        ob_start();
        ?>
        <div<?= !$row['active'] ? ' style="opacity: 0.3"' : '' ?>>
            <h2 style="margin-bottom: 1rem; text-decoration: underline;"><?= $row['title'] ?></h2>
            <?php
            if ($row['description']) {
                ?>
                <p><?= $row['description'] ?></p>
                <?php
            }
            ?>
            <p>
                <strong><?php echo $GLOBALS['TL_LANG']['tl_ls_scheduler_job']['scriptToExecute'][0]; ?>:</strong> <?= $row['scriptToExecute'] ?>
            </p>
            <p>
                <strong><?php echo $GLOBALS['TL_LANG']['tl_ls_scheduler_job']['cronExpression'][0]; ?>:</strong> <?= $row['cronExpression'] ?> (<?= $row['tstampLastRun'] <= 0 ? $GLOBALS['TL_LANG']['tl_ls_scheduler_job']['misc']['notYetRun'] : $GLOBALS['TL_LANG']['tl_ls_scheduler_job']['misc']['lastRunLabel'] . ' ' . date('d.m.Y H:i', $row['tstampLastRun']) ?>)
            </p>
            <?php
            if ($row['currentlyRunning']) {
                ?>
                <p style="color: #00AA00; font-weight: bold;">... Currently running!</p>
                <?php
            }
            ?>
            <?php
            if ($row['lastExecutionResult']) {
                ?>
                <pre style="white-space: pre-wrap;"><?= $row['lastExecutionResult'] ?></pre>
                <?php
            }
            ?>
        </div>
        <?php
        $label = ob_get_clean();
        return $label;
    }

    public function getScriptsForSchedulerExecutionAsOptions(): array
    {
        $arr_scriptFiles = [];

        foreach ($this->schedulableServices as $schedulableService) {
            $arr_scriptFiles[] = $schedulableService::class;
        }

        return $arr_scriptFiles;
    }

    public function modifyPalette(DataContainer $dc = null): void
    {
        if (!$dc || !$dc->id) {
            return;
        }

        $scriptClass = $this->connection->fetchOne(
            "SELECT scriptToExecute FROM tl_ls_scheduler_job WHERE id = ?",
            [$dc->id]
        );

        if ($scriptClass && method_exists($scriptClass, 'getCronExpression')) {
            $palette = &$GLOBALS['TL_DCA']['tl_ls_scheduler_job']['palettes']['default'];

            $palette = str_replace([',cronExpression', ';cronExpression'], '', $palette);
        }
    }

}