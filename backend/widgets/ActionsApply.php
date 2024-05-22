<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 4/26/17
 * Time: 1:59 PM
 */

namespace backend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use backend\modules\admin\components\Helper;

/**
 *
 * To use this widget add this widget to the page:
 *
 * ~~~
 * <?php \backend\widgets\ApplyActions::begin(); ?>
 * ...
 * <?= GridView::widget() ?>
 * ...
 * <?php \backend\widgets\ApplyActions::end(); ?>
 * ~~~
 *
 * and add to Controller
 * ~~~
 * public function actionApply()
 * {
 *    if ($post = Yii::$app->request->post()) {
 *       if (isset($post['selection'])) {
 *          $condition = ['id' => array_map(function ($value) {
 *              return (int)$value;
 *          }, $post['selection'])];
 *          switch ($post['action']) {
 *              case 'deactivate':
 *                  Pages::updateAll(['status' => Pages::STATUS_INACTIVE], $condition);
 *                  Yii::$app->session->setFlash('warning', Yii::t('yii', 'Selected items <b>DEACTIVATED</b>.'));
 *                  break;
 *              case 'activate':
 *                  Pages::updateAll(['status' => Pages::STATUS_ACTIVE], $condition);
 *                  Yii::$app->session->setFlash('success', Yii::t('yii', 'Selected items <b>ACTIVATED</b>.'));
 *                  break;
 *              case 'delete':
 *                  Pages::deleteAll($condition);
 *                  Yii::$app->session->setFlash('error', Yii::t('yii', 'Selected items <b>DELETED</b>.'));
 *                  break;
 *          }
 *       }
 *    }
 *
 * return $this->redirect(['index']);
 * }
 * ~~~
 *
 */
class ActionsApply extends Widget
{
    /**
     * @var array|string the form action URL.
     */
    public $action = ['apply'];

    /**
     * @var string
     */
    public $defaultAction = '';

    /**
     * @var array the list of actions
     */
    public $actions = [];

    /**
     * @var string the template to be used for rendering the output.
     */
    public $template = '<div class="form-inline margin-bottom-5">{list}{button}</div>';

    /**
     * @var array the list of options for the drop down list.
     */
    public $options;

    /**
     * @var array the list of options for the label
     */
    public $buttonOptions;

    /**
     * @var string
     */
    public $buttonText;

    /**
     * Initializes the widget.
     * This renders the form open tag.
     */
    public function init()
    {
        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        if (empty($this->options['id'])) {
            $this->options['id'] = $this->id;
        }

        $this->options['class'] = 'form-control';
        $this->buttonOptions['class'] = 'btn btn-primary';

        if (empty($this->actions)) {
            $this->actions = [
                'deactivate' => Yii::t('yii', 'Deactivate'),
                'activate' => Yii::t('yii', 'Activate'),
                'delete' => Yii::t('yii', 'Delete'),
            ];
        }

        if (empty($this->buttonText))
            $this->buttonText = Yii::t('yii', 'Apply');

        $listHtml = Html::dropDownList('action', $this->defaultAction, $this->actions, $this->options);
        $buttonHtml = Html::submitButton($this->buttonText, $this->buttonOptions);

        $output = str_replace(['{list}', '{button}'], [$listHtml, $buttonHtml], $this->template);

        $content = ob_get_clean();

        if (Helper::checkRoute(is_array($this->action) ? $this->action[0] : $this->action)) {
            echo Html::beginForm($this->action, 'POST')
                . $output
                . $content
                . Html::endForm();
        } else {
            echo $content;
        }

    }
}