<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.10.16
 * Time: 11:49
 *
 * @var $model \app\models\Category
 * @var $this \yii\web\View
 */

use yii\helpers\Url;
use yii\widgets\DetailView;
use app\components\Helper;
use yii\helpers\Html;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?php echo Yii::t("app", "Details"); ?>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-9">
                        <?php echo DetailView::widget([
                            "model"=>$model,
                            "attributes"=>[
                                'id',
                                'title',
                                [
                                    'attribute'=>'created_at',
                                    'value'=>Helper::covertToUserDate($model->created_at),
                                ],
                                [
                                    'attribute'=>'modified_at',
                                    'value'=>Helper::covertToUserDate($model->modified_at),
                                ],
                            ]
                        ]) ?>
                    </div>
                    <div class="col-sm-3">
                        <div class="list-group">
                            <a class="list-group-item active">
                                <?php echo Html::encode($this->title) ?>
                            </a>
                            <a href="<?php echo Url::to(["update", "id"=>$model->id])?>" class="list-group-item">
                                <?php echo Yii::t("app", "Update Category"); ?>
                            </a>
                            <a href="<?php echo Url::to(["delete", "id"=>$model->id])?>" class="list-group-item" onclick="return confirm('<?php echo Yii::t('yii', 'Are you sure you want to delete this item?') ?>');">
                                <?php echo Yii::t("app", "Delete Category"); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>