<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.10.09
 * Time: 11:19
 */

/**
 * @var $model \app\models\Category
 * @var $this \yii\web\View
 */

use yii\helpers\Html;
?>

<?php echo Html::beginForm("", "post", [
    "class"=>"form-horizontal",
]); ?>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?php echo Html::encode($this->title); ?>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo Html::errorSummary($model, array(
                            'class' => 'alert alert-danger',
                        )); ?>
                        <div class="form-group<?php echo $model->hasErrors("title") ? ' has-error' : null ?>">
                            <label for="category-title" class="col-sm-3 control-label"><?php echo Yii::t("app", "Title") ?></label>
                            <div class="col-sm-9">
                                <?php echo Html::activeTextInput($model, "title", [
                                    "class"=>"form-control",
                                ]); ?>
                            </div>
                        </div>
                        <?php echo Html::activeHiddenInput($model, "user_id", ["value"=>Yii::$app->getUser()->getId()]); ?>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-info pull-right"><?php echo $model->isNewRecord ? Yii::t("app", "Create") : Yii::t("app", "Update"); ?></button>
                    </div>
                </div>
            </div><!-- /.box-footer -->
        </div>
    </div>
</div>
<?php echo Html::endForm(); ?>