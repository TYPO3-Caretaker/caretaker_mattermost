<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (!class_exists('ThibaudDauce\\Mattermost\\Mattermost')) {
    require \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('caretaker_mattermost') . 'Resources/Php/vendor/autoload.php';
}

tx_caretaker_ServiceHelper::registerNotificationExitPoint(
    'caretaker_mattermost',
    'Classes/Services/ExitPoints',
    'Tx_CaretakerMattermost_NotificationMattermmost',
    'Mattermost',
    'Sends results to Mattermost channels'
);
