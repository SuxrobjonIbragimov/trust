<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 4/25/17
 * Time: 4:59 PM
 */

namespace backend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * PageSize widget is an addition to the \yii\grid\GridView that enables
 * changing the size of a page on GridView.
 *
 * To use this widget with a GridView, add this widget to the page:
 *
 * ~~~
 * <?php echo \backend\widgets\PageSize::widget(); ?>
 * ~~~
 *
 * and set the `filterSelector` property of GridView as shown in
 * following example.
 *
 * ~~~
 * <?= GridView::widget([
 *      'dataProvider' => $dataProvider,
 *      'filterModel' => $searchModel,
 *      'filterSelector' => 'select[name="per_page"]',
 *      'columns' => [
 *          ...
 *      ],
 *  ]); ?>
 * ~~~
 *
 * add this condition to SearchModel
 * ~~~
 * if (isset($params['per_page']))
 *      $dataProvider->pagination->pageSize = $params['per_page'];
 * ~~~
 *
 * Please note that `per_page` here is the string you use for `pageSizeParam` setting of the PageSize widget.
 *
 */
class PageSize extends Widget
{

    /**
     * @var integer the default page size. This page size will be used when the $_GET['per_page'] is empty.
     */
    public $defaultPageSize = 20;

    /**
     * @var string the name of the GET request parameter used to specify the size of the page.
     * This will be used as the input name of the dropdown list with page size options.
     */
    public $pageSizeParam = 'per_page';

    /**
     * @var array the list of page sizes
     */
    public $sizes = [
        5 => 5,
        10 => 10,
        20 => 20,
        50 => 50,
        100 => 100,
        200 => 200,
    ];

    /**
     * @var string the template to be used for rendering the output.
     */
    public $template = '<div class="form-inline margin-bottom-5">{list}&nbsp;{label}</div>';

    /**
     * @var array the list of options for the drop down list.
     */
    public $options;

    /**
     * @var array the list of options for the label
     */
    public $labelOptions;

    /**
     * Runs the widget and render the output
     */
    public function run()
    {
        if (empty($this->options['id'])) {
            $this->options['id'] = $this->id;
        }

        $this->options['class'] = 'form-control';
        $this->labelOptions['class'] = 'control-label';

        $perPage = !empty($_GET[$this->pageSizeParam]) ? $_GET[$this->pageSizeParam] : $this->defaultPageSize;

        $listHtml = Html::dropDownList($this->pageSizeParam, $perPage, $this->sizes, $this->options);
        $labelHtml = Html::label(Yii::t('yii', 'items'), $this->options['id'], $this->labelOptions);

        $output = str_replace(['{list}', '{label}'], [$listHtml, $labelHtml], $this->template);

        return $output;
    }
}