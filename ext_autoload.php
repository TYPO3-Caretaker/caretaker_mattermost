<?php

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('caretaker_mattermost');
$extensionClassesPath = $extensionPath . 'Classes/';

return [
    \IchHabRecht\CaretakerMattermost\Mattermost\CaretakerMessage::class => $extensionClassesPath . 'Mattermost/CaretakerMessage.php',
    Tx_CaretakerMattermost_NotificationMattermmostExitPoint::class => $extensionClassesPath . 'Services/ExitPoints/class.Tx_CaretakerMattermost_NotificationMattermmostExitPoint.php',
];
