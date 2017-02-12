<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "caretaker_mattermost".
 *
 * Auto generated 13-02-2017 00:06
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => 'Caretaker Mattermost',
  'description' => 'Mattermost exitpoint for advanced notification system of EXT:caretaker',
  'category' => 'misc',
  'author' => 'Nicole Cordes',
  'author_email' => 'typo3@cordes.co',
  'author_company' => 'CPS-IT GmbH',
  'state' => 'stable',
  'version' => '1.0.0',
  'uploadfolder' => 0,
  'createDirs' => '',
  'clearCacheOnLoad' => 0,
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '6.2.0-7.6.99',
      'php' => '5.5.0-7.1.99',
      'caretaker' => '0.3.0-0.0.0',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  'autoload' => 
  array (
    'classmap' => 
    array (
      0 => 'Classes/',
    ),
  ),
  '_md5_values_when_last_written' => 'a:13:{s:9:"ChangeLog";s:4:"2c3c";s:9:"README.md";s:4:"b042";s:13:"composer.json";s:4:"a120";s:16:"ext_autoload.php";s:4:"ec50";s:12:"ext_icon.png";s:4:"98a7";s:17:"ext_localconf.php";s:4:"ed94";s:14:"ext_tables.sql";s:4:"491d";s:39:"Classes/Mattermost/CaretakerMessage.php";s:4:"679a";s:93:"Classes/Services/ExitPoints/class.Tx_CaretakerMattermost_NotificationMattermmostExitPoint.php";s:4:"420e";s:90:"Classes/Services/ExitPoints/ds.Tx_CaretakerMattermost_NotificationMattermmostExitPoint.xml";s:4:"b6b5";s:44:"Configuration/TCA/Overrides/tx_caretaker.php";s:4:"1c28";s:47:"Resources/Php/thibaud-dauce-mattermost-php.phar";s:4:"d626";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"9504";}',
);

