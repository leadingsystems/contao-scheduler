<?php

namespace Merconis\ContaoScheduler;

use Contao\System;
use LeadingSystems\ContaoSchedulerBundle\Models\SchedulerJobModel;
use Symfony\Component\HttpFoundation\Request;

if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create('')))
{
	$GLOBALS['TL_CSS'][] = 'bundles/leadingsystemscontaoscheduler/be/css/style.css';
}

$GLOBALS['BE_MOD']['ls_contaoScheduler'] = array(
	'ls_contao_scheduler' => array(
		'tables' => array('tl_ls_scheduler_job')
	),
);

// MODELS
$GLOBALS['TL_MODELS']['tl_ls_scheduler_job'] = SchedulerJobModel::class;

// API
$GLOBALS['LS_API_HOOKS']['apiReceiver_processRequest'][] = array('LeadingSystems\ContaoSchedulerBundle\API\APIGeneral', 'processRequest');
