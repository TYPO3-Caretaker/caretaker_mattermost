<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

tx_caretaker_ServiceHelper::registerNotificationExitPoint(
    'caretaker_mattermost',
    'Classes/Services/ExitPoints',
    'Tx_CaretakerMattermost_NotificationMattermmost',
    'Mattermost',
    'Sends results to Mattermost channels'
);
