<?php

namespace LeadingSystems\ContaoScheduler;

use Contao\DataContainer;
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_ls_scheduler_job'] = array(
	'config' => array(
		'dataContainer' => DC_Table::class,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),
	
	'list' => array(
		'sorting' => array(
            'mode' => DataContainer::MODE_SORTED,
			'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
			'fields' => array('title'),
			'disableGrouping' => false,
			'panelLayout' => 'filter;sort,search,limit'
		),
		
		'label' => array(
			'fields' => array('title'),
			'format' => '%s'
		),
		
		'global_operations' => array(
			'all'
		),
		
		'operations' => array(
			'edit',
			'copy',
			'delete',
            'toggle',
			'show'
		)	
	),
	
	'palettes' => array(
		'default' => '{title_legend},title;{description_legend},description;{execution_legend},active,cronExpression,tstampLastRun,scriptToExecute,currentlyRunning,lastExecutionResult'
	),
	
	'fields' => array(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_ls_scheduler_job']['title'],
			'exclude' => true,
			'search' => true,
			'sorting' => true,
			'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
			'inputType' => 'text',
			'eval' => array(
				'mandatory' => true,
				'maxlength' => 64,
				'tl_class'=>'w50'
			),
			'sql' => "varchar(64) NULL"
		),

		'description' => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_ls_scheduler_job']['description'],
			'exclude' => true,
			'inputType' => 'textarea',
			'sql' => "text NULL"
		),

        'active' => [
            'label' => &$GLOBALS['TL_LANG']['tl_ls_scheduler_job']['active'],
            'exclude' => true,
            'toggle' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => array('tl_class'=>'clr m12'),
            'sql' => "char(1) NOT NULL default ''"
        ],

		'cronExpression' => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_ls_scheduler_job']['cronExpression'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => array(
				'mandatory' => true,
				'maxlength' => 64,
				'tl_class'=>'w50'
			),
			'sql' => "varchar(64) NULL"
		),

        'tstampLastRun' => [
            'label' => &$GLOBALS['TL_LANG']['tl_ls_scheduler_job']['tstampLastRun'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'rgxp' => 'datim', 'datepicker' => true],
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],

        'scriptToExecute' => array
        (
            'label' =>  &$GLOBALS['TL_LANG']['tl_ls_scheduler_job']['scriptToExecute'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'eval'					  => array('tl_class' => 'clr', 'includeBlankOption' => true),
            'sql'                     => "varchar(255) NULL"
        ),

        'currentlyRunning' => [
            'label' => &$GLOBALS['TL_LANG']['tl_ls_scheduler_job']['currentlyRunning'],
            'exclude' => true,
            'toggle' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => array('tl_class'=>'clr m12'),
            'sql' => "char(1) NOT NULL default ''"
        ],

        'lastExecutionResult' => [
            'label' =>  &$GLOBALS['TL_LANG']['tl_ls_scheduler_job']['lastExecutionResult'],
            'exclude' => true,
            'inputType' => 'textarea',
            'eval' => ['tl_class' => 'clr', 'allowHtml' => true],
            'sql' => "longtext NULL"
        ],
	)
);