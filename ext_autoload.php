<?php

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('caretaker_mattermost');
$extensionClassesPath = $extensionPath . 'Classes/';

return [
    Tx_CaretakerMattermost_NotificationMattermmostExitPoint::class => $extensionClassesPath . 'Services/ExitPoints/class.Tx_CaretakerMattermost_NotificationMattermmostExitPoint.php',
];
