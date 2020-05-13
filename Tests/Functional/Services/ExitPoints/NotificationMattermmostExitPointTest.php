<?php
namespace IchHabRecht\CaretakerMattermost\Tests\Functional\Services\ExitPoints;

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

use Nimut\TestingFramework\TestCase\FunctionalTestCase;

class NotificationMattermmostExitPointTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/caretaker',
        'typo3conf/ext/caretaker_mattermost',
    ];

    /**
     * @return array
     */
    public function configurationDataProvider()
    {
        return [
            'configuration without aggregation' => [
                [
                    'endpoint' => 'foo.bar',
                    'channel' => 'mattermost',
                ],
            ],
            'configuration with aggregation' => [
                [
                    'endpoint' => 'foo.bar',
                    'channel' => 'mattermost',
                    'aggregateNotifications' => true,
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider configurationDataProvider
     * @param array $configuration
     */
    public function preventMultipleMessagesForSameNotification(array $configuration)
    {
        /** @var \Tx_CaretakerMattermost_NotificationMattermmostExitPoint|\PHPUnit_Framework_MockObject_MockObject $subject */
        $subject = $this->getMockBuilder(\Tx_CaretakerMattermost_NotificationMattermmostExitPoint::class)
            ->setMethods(['sendNotification'])->getMock();
        $subject->expects($this->once())->method('sendNotification');

        $notification = $this->getNotification();

        $subject->addNotification($notification, $configuration);
        $subject->addNotification($notification, $configuration);
        $subject->execute();
    }

    /**
     * @test
     */
    public function preventMessagesToMutedChannels()
    {
        /** @var \Tx_CaretakerMattermost_NotificationMattermmostExitPoint|\PHPUnit_Framework_MockObject_MockObject $subject */
        $subject = $this->getMockBuilder(\Tx_CaretakerMattermost_NotificationMattermmostExitPoint::class)
            ->setMethods(['sendNotification'])->getMock();
        $subject->expects($this->once())->method('sendNotification');

        $configuration = [
            'endpoint' => 'foo.bar',
            'channel' => 'mattermost',
            'aggregateNotifications' => true,
        ];

        $notification = $this->getNotification();
        $testNode = $notification['node'];
        $instanceNode = $testNode->getParent();
        $instanceNode->setDbRow(
            [
                'tx_caretakermattermost_channel' => 'node, -mattermost',
            ]
        );

        $subject->addNotification($notification, $configuration);
        $subject->execute();
    }

    /**
     * @return array
     */
    private function getNotification()
    {
        $rootNode = new \tx_caretaker_RootNode();
        $instanceNode = new \tx_caretaker_InstanceNode(42, 'InstanceNode', $rootNode);

        return [
            'node' => new \tx_caretaker_TestNode(42, 'TestNode', $instanceNode, '', ''),
            'result' => new \tx_caretaker_TestResult(),
        ];
    }
}
