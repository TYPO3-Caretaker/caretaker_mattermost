<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Nicole Cordes <typo3@cordes.co>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use IchHabRecht\CaretakerMattermost\Mattermost\CaretakerMessage;
use Psr\Log\LoggerInterface;
use ThibaudDauce\Mattermost\Mattermost;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

include_once 'phar://' . __DIR__ . '/../../../Resources/Php/thibaud-dauce-mattermost-php.phar/vendor/autoload.php';

class Tx_CaretakerMattermost_NotificationMattermmostExitPoint extends tx_caretaker_NotificationBaseExitPoint
{
    /**
     * @var array
     */
    private $aggregatedNotifications = [];

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        if (null !== $logger) {
            $this->logger = $logger;
        } else {
            $logManager = GeneralUtility::makeInstance(LogManager::class);
            $this->logger = $logManager->getLogger(get_class($this));
        }
    }

    /**
     * @param array $notification
     * @param array $overrideConfig
     * @return void
     */
    public function addNotification($notification, $overrideConfig)
    {
        $config = $this->getConfig($overrideConfig);
        if (empty($config['endpoint'])) {
            return;
        }

        /** @var tx_caretaker_TestNode $node */
        $node = $notification['node'];

        $channels = GeneralUtility::trimExplode(',', $config['channel'], true);
        $channels = array_unique(array_merge($channels, $this->getChannelsFromNodes($node)));
        if (empty($channels)) {
            return;
        }

        foreach ($channels as $channel) {
            $nodeId = $node->getCaretakerNodeId();
            $instanceId = $node->getInstance()->getCaretakerNodeId();
            $resultState = $notification['result']->getState();

            if (!isset($this->aggregatedNotifications[$channel][$instanceId][$resultState][$nodeId])) {
                if (empty($this->aggregatedNotifications[$channel][$instanceId][$resultState])) {
                    $this->aggregatedNotifications = array_replace_recursive(
                        $this->aggregatedNotifications,
                        [
                            $channel => [
                                $instanceId => [
                                    $resultState => [],
                                ],
                            ],
                        ]
                    );
                }
                if (empty($this->config['aggregateNotifications'])) {
                    $this->sendNotification(new CaretakerMessage([$notification], $channel, $this->config['username'], $this->config['icon']));
                    $this->aggregatedNotifications[$channel][$instanceId][$resultState][$nodeId] = true;
                } else {
                    $this->aggregatedNotifications[$channel][$instanceId][$resultState][$nodeId] = $notification;
                }
            }
        }
    }

    /**
     * @return void
     */
    public function execute()
    {
        if (!empty($this->aggregatedNotifications)) {
            foreach ($this->aggregatedNotifications as $channel => $instanceNotifications) {
                ksort($instanceNotifications);
                foreach ($instanceNotifications as $aggregatedNotifications) {
                    ksort($aggregatedNotifications);
                    foreach ($aggregatedNotifications as $notifications) {
                        $this->sendNotification(new CaretakerMessage($notifications, $channel, $this->config['username'], $this->config['icon']));
                    }
                }
            }
        }
    }

    /**
     * @param tx_caretaker_AbstractNode $node
     * @return array
     */
    private function getChannelsFromNodes(tx_caretaker_AbstractNode $node)
    {
        $channels = [];

        if ($node instanceof tx_caretaker_InstanceNode || $node instanceof tx_caretaker_InstancegroupNode) {
            $channels = GeneralUtility::trimExplode(',', $node->getProperty('tx_caretakermattermost_channel'), true);
        }

        if (!$node->getParent() instanceof tx_caretaker_RootNode) {
            $channels = array_unique(array_merge($channels, $this->getChannelsFromNodes($node->getParent())));
        }

        return $channels;
    }

    /**
     * @param CaretakerMessage $message
     * @return void
     */
    private function sendNotification(CaretakerMessage $message)
    {
        $mattermost = new Mattermost(new Client());
        try {
            $mattermost->send($message, $this->config['endpoint']);
        } catch (RequestException $exception) {
            $this->logger->error(
                $exception->getMessage(),
                [
                    'config' => $this->config,
                    'message' => $message,
                ]
            );
        }
    }
}
