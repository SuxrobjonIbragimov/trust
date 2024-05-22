<?php

namespace backend\widgets;

use Yii;
use yii\bootstrap\Widget;
use kartik\growl\Growl;

/**
 * Alert widget renders a message from session flash for AdminLTE alerts. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 */
class AlertGrowl extends Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the array:
     *       - type of alert type (i.e. danger, success, info, warning)
     *       - icon for alert AdminLTE
     */
    public $alertTypes = [
        'error' => [
            'type' => Growl::TYPE_DANGER,
            'icon' => 'fa fa-ban fa-2x',
        ],
        'danger' => [
            'type' => Growl::TYPE_DANGER,
            'icon' => 'fa fa-ban fa-2x',
        ],
        'success' => [
            'type' => Growl::TYPE_SUCCESS,
            'icon' => 'fa fa-check-circle fa-2x',
        ],
        'info' => [
            'type' => Growl::TYPE_INFO,
            'icon' => 'fa fa-info-circle fa-2x',
        ],
        'warning' => [
            'type' => Growl::TYPE_WARNING,
            'icon' => 'fa fa-warning fa-2x',
        ],
    ];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $data = (array)$data;
                foreach ($data as $message) {

                    echo Growl::widget([
                        'type' => $this->alertTypes[$type]['type'],
                        'icon' => $this->alertTypes[$type]['icon'],
                        'title' => '&nbsp;',
                        'body' => $message,
                        'showSeparator' => true,
                        'pluginOptions' => [
                            'showProgressbar' => true,
                            'mouse_over' => 'pause',
                            'offset' => [
                                'y' => 60,
                                'x' => 10
                            ],
                            'placement' => [
                                'from' => 'bottom',
                                'align' => 'right',
                            ]
                        ],
                        'progressBarOptions' => [
                            'style' => 'width: 0%;',
                        ],
                    ]);

                }

                $session->removeFlash($type);
            }
        }
    }
}
