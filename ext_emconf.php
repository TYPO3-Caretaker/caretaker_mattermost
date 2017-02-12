<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "caretaker_mattermost".
 *
 * Auto generated 12-02-2017 14:56
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
  'version' => '0.1.0',
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
  '_md5_values_when_last_written' => 'a:1:{s:12:"ext_icon.png";s:4:"98a7";}',
);

