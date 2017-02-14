<?php
namespace IchHabRecht\CaretakerMattermost\Mattermost;

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

use ThibaudDauce\Mattermost\Attachment;
use ThibaudDauce\Mattermost\Message;

class CaretakerMessage extends Message
{
    /**
     * @var array
     */
    private $colors = [
        \tx_caretaker_Constants::state_ok => '#008000',
        \tx_caretaker_Constants::state_error => '#FF0000',
        \tx_caretaker_Constants::state_warning => '#FFA500',
        \tx_caretaker_Constants::state_undefined => '#808080',
        \tx_caretaker_Constants::state_ack => '#0000FF',
        \tx_caretaker_Constants::state_due => '#EE82EE',
    ];

    /**
     * @var string
     */
    private $defaultUsername = 'caretaker';

    /**
     * @var string
     */
    private $defaultIcon = 'https://raw.githubusercontent.com/TYPO3-Caretaker/caretaker/master/ext_icon.gif';

    /**
     * @var string
     */
    private $titleTemplate = '%s %s in instance: "%s" [%s]';

    /**
     * @var string
     */
    private $markdownTextTemplate = '
**Test: %s**
State before: %s
Test result:
%s';

    /**
     * @var string
     */
    private $plainTextTemplate = '
Test: %s
State before: %s
Test result:
%s';

    /**
     * @param array $notifications
     * @param string $channel
     * @param string $username
     * @param string $icon
     */
    public function __construct(array $notifications, $channel, $username = '', $icon = '')
    {
        $this->channel = $channel;
        $this->username = !empty($username) ? $username : $this->defaultUsername;
        $this->iconUrl = !empty($icon) ? $icon : $this->defaultIcon;

        $color = '';
        $title = '';
        $plainText = [];
        $markdownText = [];

        foreach ($notifications as $notification) {
            /** @var \tx_caretaker_TestNode $node */
            $node = $notification['node'];
            /** @var \tx_caretaker_TestResult $result */
            $result = $notification['result'];
            /** @var \tx_caretaker_TestResult $prevResult */
            $prevResult = $node->getPreviousDifferingResult($result);

            if (empty($title)) {
                $color = $this->colors[$result->getState()];
                $title = sprintf($this->titleTemplate,
                    count($notifications),
                    $result->getStateInfo(),
                    $node->getInstance()->getTitle(),
                    $node->getInstance()->getCaretakerNodeId()
                );
            }

            $plainText[] = sprintf($this->plainTextTemplate,
                $node->getTitle(),
                strtolower($prevResult->getStateInfo()),
                $result->getLocallizedInfotext()
            );
            $markdownText[] = sprintf($this->markdownTextTemplate,
                $node->getTitle(),
                strtolower($prevResult->getStateInfo()),
                $result->getLocallizedInfotext()
            );
        }

        $plainText = implode("\n\n", $plainText);
        $markdownText = implode("\n***\n", $markdownText);

        $attachment = new Attachment();
        $attachment->fallback($title . "\n" . $plainText)
            ->title($title)
            ->text($markdownText)
            ->color($color);

        $this->attachments = [$attachment];
    }
}
