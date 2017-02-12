<?php

$extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['caretaker']);
$advancedNotificationsEnabled = $extConfig['notifications.']['advanced.']['enabled'] == '1';

if (!$advancedNotificationsEnabled) {
    return;
}

$tempColumns = [
    'tx_caretakermattermost_channel' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:caretaker_mattermost/Resources/Private/Language/locallang_db.xlf:tx_caretaker.tx_caretakermattermost_channel',
        'config' => [
            'type' => 'input',
            'size' => '30',
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_caretaker_instancegroup', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_caretaker_instancegroup',
    'tx_caretakermattermost_channel',
    '',
    'after:notification_strategies'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_caretaker_instance', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_caretaker_instance',
    'tx_caretakermattermost_channel',
    '',
    'after:notification_strategies'
);
