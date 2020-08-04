<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.09.19
 * Time: 21:18
 */

namespace app\controllers;


use app\components\AppController;
use app\models\Category;
use app\models\search\CategorySearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;

class CategoryController extends AppController
{
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class'=>AccessControl::className(),
            'rules' => [
                [
                    'allow'=>true,
                    'roles'=>['@'],
                ]
            ],
        ];
        return $behaviors;
    }

    /**
     * @var Category;
     */
    protected $category;

    public function actionIndex() {
        $this->getView()->title = Yii::t("app", "Categories");

        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render("index", [
            "dataProvider"=>$dataProvider,
            "searchModel"=>$searchModel
        ]);
    }

    public function actionCreate() {
        $model = new Category();
        $model->setScenario(Category::SCENARIO_OWNER);

        if($model->load(Yii::$app->request->post()) AND $model->save()) {
            Yii::$app->getSession()->setFlash("success", Yii::t("app", "Category has been created"));
            return $this->redirect(["category/index"]);
        }

        $this->getView()->title = Yii::t("app", "Create new category");

        $this->breadcrumbs = [
            [
                'label'=>Yii::t("app", "Categories"),
                'url'=>Yii::$app->getUrlManager()->createUrl("category/index"),
            ],
            [
                'label'=>Yii::t("app", "New category"),
            ]
        ];

        return $this->render("create_edit", [
            "model"=>$model,
        ]);
    }

    public function actionUpdate($id) {
        $this->category = $this->loadModel(Category::className(), $id);
        $this->category->setScenario(Category::SCENARIO_OWNER);

        if($this->category->load(Yii::$app->request->post()) AND $this->category->save()) {
            Yii::$app->getSession()->setFlash("success", Yii::t("app", "Category has been updated"));
            return $this->redirect(["category/index"]);
        }

        $this->getView()->title = Yii::t("app", "Update Category | {categoryName}", [
            "categoryName"=>$this->category->getOldAttribute("title"),
        ]);

        $this->breadcrumbs = [
            [
                'label'=>Yii::t("app", "Categories"),
                'url'=>Yii::$app->getUrlManager()->createUrl("category/index"),
            ],
            [
                'label'=>$this->getView()->title,
            ]
        ];

        return $this->render("create_edit", [
            "model"=>$this->category,
        ]);
    }

    public function actionView($id) {
        $this->category = $this->loadModel(Category::className(), $id);
        $this->getView()->title = Yii::t("app", "View Category | {categoryName}", [
            "categoryName"=>$this->category->getOldAttribute("title"),
        ]);
        $this->breadcrumbs = [
            [
                'label'=>Yii::t("app", "Categories"),
                'url'=>Yii::$app->getUrlManager()->createUrl("category/index"),
            ],
            [
                'label'=>$this->getView()->title,
            ]
        ];
        return $this->render("view", [
            "model"=>$this->category
        ]);
    }

    public function actionDelete($id) {
        $this->category = $this->loadModel(Category::className(), $id);
        if($this->category->delete()) {
            Yii::$app->getSession()->setFlash("success", Yii::t("app", "Category has been deleted."));
        }
        return $this->redirect(["category/index"]);
    }
}