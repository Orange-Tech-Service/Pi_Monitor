<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.10.28
 * Time: 19:20
 */

namespace app\controllers;


use app\components\AppController;
use app\components\ConsoleCommandRunner;
use app\components\Cron;
use app\components\ScheduleProcess;
use app\models\Log;
use app\models\Schedule;
use app\models\search\LogSearch;
use app\models\search\ScheduleSearch;
use app\models\Stat;
use app\models\User;
use Curl\Curl;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;
use yii\data\ArrayDataProvider;
use yii\db\Connection;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use Yii;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\web\HttpException;
use yii\web\Response;
use \DateTimeZone;
use \DateTime;
use yii\filters\AccessControl;

class CronJobController extends AppController
{
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class'=>AccessControl::className(),
            'rules' => [
                [
                    'allow'=>true,
                    'actions'=>['exec'],
                    'roles'=>['?'],
                ],
                [
                    'allow'=>true,
                    'roles'=>['@'],
                ]
            ],
        ];
        return $behaviors;
    }

    public function actionCreate() {
        $this->getView()->title = \Yii::t("app", "Create New Cron Job");
        $type = in_array(Yii::$app->request->get("type"), array_keys(Schedule::getTypeArray())) ? Yii::$app->request->get("type") : Schedule::SCHEDULE_TYPE_HTTP;

        $this->breadcrumbs = [
            [
                "url"=>Url::to(["index"]),
                "label"=>Yii::t("app", "My Cron Jobs"),
            ],
            [
                "label"=>$this->getView()->title
            ]
        ];

        $model = new Schedule();
        $model->command_type = $type;
        $model->loadDefaultValues();
        $model->setScenario(Schedule::SCENARIO_MANAGE);

        if(Yii::$app->getRequest()->getIsPost()) {
            $request = Yii::$app->getRequest();
            $doTest = (boolean) $request->post("test");
            $model->setUser(Yii::$app->getUser()->getIdentity());

            if($model->load($request->post()) AND $model->validate()) {
                $model->save(false);
                Yii::$app->getSession()->setFlash("success", Yii::t("app", "Cron job has been added to tasks queue"));
                return $doTest ? $this->redirect(["cron-job/run", "id"=>$model->id]) : $this->redirect(["cron-job/index"]);
            }
        }

        $this->registerJs($model);

        $months = include Yii::getAlias("@app/config/month.php");
        $days = include Yii::getAlias("@app/config/day.php");

        return $this->render("create_edit", [
            "months"=>$months,
            "days"=>$days,
            "model"=>$model,
            "selectedExpr"=>Yii::$app->request->post("cron_alias"),
            "customExpr"=>Yii::$app->request->post("cron_expression"),
            "btnText"=>Yii::t("app", "Create"),
        ]);
    }

    public function actionUpdate($id) {
        /**
         * @var $model Schedule
         */
        $model = $this->loadModel(Schedule::className(), $id);
        $model->setScenario(Schedule::SCENARIO_MANAGE);

        $this->getView()->title = Yii::t("app", "Edit Cron Job | {title}", [
            "title"=>$model->getOldAttribute('title'),
        ]);

        $this->breadcrumbs = [
            [
                "url"=>Url::to(["index"]),
                "label"=>Yii::t("app", "My Cron Jobs"),
            ],
            [
                "label"=>$this->getView()->title
            ]
        ];

        $months = include Yii::getAlias("@app/config/month.php");
        $days = include Yii::getAlias("@app/config/day.php");

        if(Yii::$app->getRequest()->getIsPost()) {
            $request = Yii::$app->getRequest();
            $model->setUser(Yii::$app->getUser()->getIdentity());
            $doTest = (boolean) $request->post("test");

            if($model->load($request->post()) AND $model->validate()) {
                $model->save(false);
                Yii::$app->getSession()->setFlash("success", Yii::t("app", "Cron job has been modified"));
                return $doTest ? $this->redirect(["cron-job/run", "id"=>$model->id]) : $this->refresh();
            }
        } else {
            $model->buildAdditionalParams();
        }

        $this->registerJs($model);

        return $this->render("create_edit", [
            "months"=>$months,
            "days"=>$days,
            "model"=>$model,
            "selectedExpr"=>$model->isAlias() ? $model->expression : null,
            "customExpr"=>$model->isExpression() ? $model->expression : null,
            "btnText"=>Yii::t("app", "Update"),
        ]);
    }

    public function actionClone($id) {
        /**
         * @var $model Schedule
         */
        if(Yii::$app->getRequest()->getIsPost()) {
            $oldModel = $this->loadModel(Schedule::className(), $id);

            $model = new Schedule();
            $model->setScenario(Schedule::SCENARIO_MANAGE);
            $model->setUser(Yii::$app->getUser()->getIdentity());
            $model->command_type = $oldModel->command_type;

            $request = Yii::$app->getRequest();
            $doTest = (boolean) $request->post("test");

            if($model->load($request->post()) AND $model->validate()) {
                $model->save(false);
                Yii::$app->getSession()->setFlash("success", Yii::t("app", "Cron Job has been cloned"));
                return $doTest ? $this->redirect(["cron-job/run", "id"=>$model->id]) : $this->redirect(["cron-job/index"]);
            }
        } else {
            $model = $this->loadModel(Schedule::className(), $id);
            $model->buildAdditionalParams();
        }

        $this->getView()->title = Yii::t("app", "Clone Cron Job");

        $this->breadcrumbs = [
            [
                "url"=>Url::to(["index"]),
                "label"=>Yii::t("app", "My Cron Jobs"),
            ],
            [
                "label"=>$this->getView()->title
            ]
        ];

        $months = include Yii::getAlias("@app/config/month.php");
        $days = include Yii::getAlias("@app/config/day.php");

        $this->registerJs($model);

        return $this->render("create_edit", [
            "months"=>$months,
            "days"=>$days,
            "model"=>$model,
            "selectedExpr"=>$model->isAlias() ? $model->expression : null,
            "customExpr"=>$model->isExpression() ? $model->expression : null,
            "btnText"=>Yii::t("app", "Clone"),
        ]);
    }

    public function actionDelete($id) {
        $model = $this->loadModel(Schedule::className(), $id);
        if($model->delete()) {
            Yii::$app->getSession()->setFlash("success", Yii::t("app", "Cron job has been deleted."));
        }
        return $this->goToPreviousPage(["index"]);
    }

    public function actionRun($id) {
        /**
         * @var $model Schedule
         */
        $model = $this->loadModel(Schedule::className(), $id);

        if(Yii::$app->request->getIsAjax()) {
            $handler = $model->initHandler();
            $startTime = microtime(true);
            $model->runHandler($handler);
            $finishTime = microtime(true);
            $responseInfo = $model->responseInfo($handler);

            return $this->renderPartial("exec-{$model->command_type}", [
                "handler"=>$handler,
                "startTime"=>$startTime,
                "finishTime"=>$finishTime,
                "responseInfo"=>$responseInfo,
                "model"=>$model,
            ]);
        }

        $this->getView()->title = Yii::t("app", "Cron Job Manual Execution Test");

        $this->breadcrumbs = [
            [
                "url"=>Url::to(["index"]),
                "label"=>Yii::t("app", "My Cron Jobs"),
            ],
            [
                "label"=>$this->getView()->title
            ]
        ];

        $this->getView()->registerJs("WebCronApp.cronRun({
            execUrl: ".Json::encode(Url::to(["run", "id"=>$model->id]))."
        })");

        return $this->render("run-{$model->command_type}", [
            "model"=>$model,
            "cookies"=>$model->decodeCookies(),
            "post"=>$model->decodePost(),
            "headers"=>$model->decodeHeaders(),
        ]);
    }

    protected function registerJs(Schedule $model) {
        $this->getView()->registerJs("WebCronApp.cronJob({
            predictionUrl:".Json::encode(Url::to(["ajax-prediction"])).",
            ui: {
                ". ($model->isGui() ? "command: ". Json::encode($model->expression) : null) ."
            },
            params: {
                cookieTmpl: ".Json::encode($this->renderPartial("_additional_params", [
                "placeholderKey"=>Yii::t("app", "Cookie name"),
                "placeholderValue"=>Yii::t("app", "Cookie value"),
                "modelAttr"=>"cookieParams",
                "model"=>$model,
            ])).",
                postTmpl: ".Json::encode($this->renderPartial("_additional_params", [
                "placeholderKey"=>Yii::t("app", "Post name"),
                "placeholderValue"=>Yii::t("app", "Post value"),
                "modelAttr"=>"postParams",
                "model"=>$model,
            ])).",
                headersTmpl: ".Json::encode($this->renderPartial("_additional_params", [
                "placeholderKey"=>Yii::t("app", "Header name"),
                "placeholderValue"=>Yii::t("app", "Header value"),
                "modelAttr"=>"headerParams",
                "model"=>$model,
            ]))."
            }
        });");
    }

    public function actionIndex() {
        if(Yii::$app->getRequest()->getIsPost()) {
            $method = "bulk".Yii::$app->getRequest()->post('bulk-group');
            $ids = (array) Yii::$app->getRequest()->post('selection');
            $value = Yii::$app->getRequest()->post('bulk-value');
            if(method_exists($this, $method) AND !empty($ids)) {
                return $this->$method($value, $ids);
            }
        }

        $this->getView()->title = Yii::t("app", "My Cron Jobs");
        $jsUi = [
            "loading"=>Yii::t("app", "Loading..."),
        ];
        $this->getView()->registerJs("WebCronApp.setUi(".Json::encode($jsUi).")");
        $searchModel = new ScheduleSearch();

        $formName = $searchModel->formName();
        $reset = (bool) Yii::$app->request->get("reset");
        if($reset) {
            Yii::$app->response->cookies->remove($formName);
        }
        $queryParams = Yii::$app->request->queryParams;
        if(isset($queryParams[$formName])) {
            Yii::$app->response->cookies->add(new Cookie([
                'name' => $formName,
                'value' => $queryParams[$formName]
            ]));
            $searchParams = Yii::$app->request->get();
        } else {
            $searchParams = (Yii::$app->request->cookies->has($formName) AND !$reset) ? [$formName => (array) Yii::$app->request->cookies->get($formName)->value] : [];
        }


        $searchModel->setUser(Yii::$app->getUser()->getIdentity());
        $dataProvider = $searchModel->search($searchParams);

        $this->breadcrumbs = [
            [
                "label"=>$this->getView()->title
            ]
        ];

        $this->getView()->registerJs("WebCronApp.cronIndex({})");

        return $this->render("index", [
            "searchModel"=>$searchModel,
            "dataProvider"=>$dataProvider,
        ]);
    }


    public function actionStatistic($id) {
        $schedule = $this->loadModel(Schedule::className(), $id);
        $stats = Stat::find()
            ->select("SUM(success) as success, SUM(failed) as failed")
            ->asArray()
            ->where([
                "schedule_id"=>$schedule->id
            ])
            ->one();

        $lc = $stats['success'] + $stats['failed'];
        if($lc) {
            $success = $stats['success'];
            $successPercent = round(100*$success/$lc, 2);
            $failurePercent = round(100*$stats['failed']/$lc, 2);

            $totalPhrase = Yii::t("app", "Total executions: {0}", "<span class='label label-info'><strong>".number_format($lc)."</strong></span>");
            $successPhrase = Yii::t("app", "Succeed: {0} ({1} %)", ["<span class='label label-success'><strong>".number_format($success)."</strong></span> ", $successPercent]);
            $failPhrase = Yii::t("app", "Failed: {0} ({1} %)", ["<span class='label label-danger'><strong>".number_format($stats['failed'])."</strong></span>", $failurePercent]);
        } else {
            $success = $successPercent = $failurePercent = $totalPhrase = $successPhrase = $failPhrase = null;
        }



        $this->getView()->title = Yii::t("app", "{title} | Schedule Statistic", [
            "title"=>$schedule->title,
        ]);

        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search($schedule->id, Yii::$app->request->get());

        $this->breadcrumbs = [
            [
                "url"=>Url::to(["index"]),
                "label"=>Yii::t("app", "My Cron Jobs"),
            ],
            [
                "label"=>$this->getView()->title
            ]
        ];

        $this->getView()->registerJs("WebCronApp.cronLog({})");

        return $this->render("statistic", [
            "searchModel"=>$searchModel,
            "dataProvider"=>$dataProvider,
            "schedule"=>$schedule,
            "stats"=>$stats,
            "lc"=>$lc,
            "totalPhrase"=>$totalPhrase,
            "successPhrase"=>$successPhrase,
            "failPhrase"=>$failPhrase,
            "successPercent"=>$successPercent,
            "failurePercent"=>$failurePercent,
        ]);
    }

    public function actionSwitch($id) {
        /**
         * @var $model Schedule
         */
        $model = $this->loadModel(Schedule::className(), $id);
        $status = Yii::$app->getRequest()->get('status');
        Yii::$app->response->format = Response::FORMAT_JSON;

        if(!in_array($status, array_keys(Schedule::getStatusArray()))) {
            return [
                "error"=>Yii::t("app", "Internal server error"),
            ];
        }

        $model->status = $status;
        $model->setUser(Yii::$app->getUser()->getIdentity());

        if($model->save()) {
            if($model->isEnabled()) {
                $msg =Yii::t("app", "Cron job (ID: {id}) has been enabled.", [
                    "id"=>$model->id,
                ]);
            } else {
                $msg = Yii::t("app", "Cron job (ID: {id}) has been disabled.", [
                    "id"=>$model->id,
                ]);
            }
            return [
                "success"=>$msg
            ];
        } else {
            return [
                "error"=>Html::errorSummary($model, [
                    "header"=>Yii::t("app", "The above error occurred while the Web server was processing your request."),
                ]),
            ];
        }
    }

    public function actionPredict($id) {
        $model = $this->loadModel(Schedule::className(), $id);
        $user = Yii::$app->getUser()->getIdentity();

        $postStartDate = Yii::$app->getRequest()->get('startDate');

        $cron = Cron::factory($model->expression);

        if(strtotime($postStartDate) > time()) {
            $startDate = new DateTime($postStartDate, new DateTimeZone($user->settings->timezone));
            $currentDate = $user->getDateObject();
        } else {
            $startDate = $user->getDateObject();
            $currentDate = $startDate;
        }

        try {
            $prediction = $cron->getMultipleNextRunDate($startDate, Yii::$app->params['schedulePrediction']);
        } catch(\Exception $e) {
            $prediction = $cron->getStack();
        }

        $this->getView()->title = Yii::t("app", "Cron Job Prediction");
        $this->breadcrumbs = [
            [
                'label'=>Yii::t("app", "Cron Jobs"),
                'url'=>Url::to(["index"]),
            ],
            [
                "label"=>$this->getView()->title
            ]
        ];
        $provider = new ArrayDataProvider([
            'allModels'=>$prediction,
            "pagination"=>[
                'pageSize'=>20,
            ]
        ]);

        return $this->render("predict", compact('provider', 'model', 'startDate', 'currentDate'));
    }

    public function actionAjaxPrediction() {
        $expr = Yii::$app->getRequest()->get('expression');
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;

        if(!Cron::isValidExpression($expr)) {
            $response = $this->renderPartial("ajax_invalid_expr");
        } else {
            $user = Yii::$app->getUser()->getIdentity();
            $cron = Cron::factory($expr);
            try {
                $prediction = $cron->getMultipleNextRunDate($user->getDateObject(), Yii::$app->params['ajaxPrediction']);
                $response = $this->renderPartial("ajax_prediction", [
                    "predictions"=>$prediction,
                    "expression"=>$expr,
                ]);
            } catch(\Exception $e) {
                $stack = $cron->getStack();
                if(!empty($stack)) {
                    $response = $this->renderPartial("ajax_prediction", [
                        "predictions"=>$stack,
                        "expression"=>$expr,
                    ]);
                } else {
                    $response = $this->renderPartial("ajax_invalid_expr");
                }
            }
        }

        return [
            'output'=>$response,
        ];
    }

    public function actionTest() {}

    public function actionReset($id) {
        Yii::$app->db->transaction(function($db) use($id) {
            /**
             * @var $db Connection
             */
            Log::deleteAll([
                "user_id"=>Yii::$app->getUser()->getId(),
                "schedule_id"=>$id,
            ]);
            Stat::deleteAll([
                "user_id"=>Yii::$app->getUser()->getId(),
                "schedule_id"=>$id,
            ]);
        });
        Yii::$app->getSession()->setFlash("success", Yii::t("app", "Successfully reset the logs and stat. of #{id} cron job", [
            "id"=>$id
        ]));
        $this->goToPreviousPage(["index"]);
    }

    public function actionExamples() {
        $this->getView()->title = Yii::t("app", "What cron expression does {appName} support?", [
            "appName"=>Yii::$app->params['longAppName'],
        ]);

        $this->breadcrumbs = [
            [
                'label'=>Yii::t("app", "Cron Jobs"),
                'url'=>Url::to(["index"]),
            ],
            [
                "label"=>$this->getView()->title
            ]
        ];
        return $this->render("example");
    }

    public function actionExec() {
        $key = ArrayHelper::getValue(Yii::$app->params, "webHandlerKey");
        if(empty($key) OR $key != Yii::$app->getRequest()->get("key")) {
            throw new HttpException(403, "You are not allowed to perform this action");
        }
        @ini_set('max_execution_time', 0);
        @ini_set('max_input_time', -1);

        $runner = new ConsoleCommandRunner();
        $runner->run("exec");
        return $runner->getExitCode()."\n\n".$runner->getOutput();
    }

    public function actionLog($id) {
        $log = $this->loadModel(Log::className(), $id);
        $this->getView()->title = Yii::t("app", "Cron Job Execution Log | {title}", [
            "title"=>$log->schedule->title
        ]);

        $this->breadcrumbs = [
            [
                'label'=>Yii::t("app", "Cron Jobs"),
                'url'=>Url::to(["index"]),
            ],
            [
                "label"=>$this->getView()->title
            ]
        ];

        return $this->render("log", [
            "log"=>$log,
        ]);
    }

    /**
     * Enable or disable cron jobs in bulk
     *
     * @param $value
     * @param array $ids
     * @return boolean|void
     *
     */
    protected function bulkStatus($value, array $ids) {
        if(!in_array($value, array_keys(Schedule::getStatusArray()))) {
            return false;
        }

        $schedules = Schedule::find()->where([
            "user_id"=>Yii::$app->user->id,
            "id"=>$ids,
        ])->all();

        $html = '';

        foreach($schedules as $schedule) {
            $schedule->setUser(Yii::$app->getUser()->getIdentity());
            $schedule->status = $value;
            if($schedule->save()) {
                if($value == Schedule::STATUS_ENABLED) {
                    $msg = Yii::t("app", "Cron job (ID: {id}) has been enabled.", ["id"=>$schedule->id]);
                } else {
                    $msg = Yii::t("app", "Cron job (ID: {id}) has been disabled.", ["id"=>$schedule->id]);
                }
                $html .= '<div class="alert alert-success margin-r-20">'.$msg.'</div>';
            } else {
                $html .= '<div class="alert alert-danger margin-r-20">'.Html::errorSummary($schedule, [
                    "header"=>Yii::t("app", "Error") . " | " . Html::encode($schedule->title)
                ]).'</div>';
            }
        }

        Yii::$app->getSession()->setFlash("neutral", $html);
        return $this->refresh();
    }

    /**
     * Enable or disable cron jobs in bulk
     *
     * @param $value
     * @param array $ids
     * @return boolean|void
     *
     */
    protected function bulkNotify($value, array $ids) {
        if(!in_array($value, array_keys(Schedule::getNotifyArray()))) {
            return false;
        }

        Schedule::updateAll([
            "notify"=>$value,
        ], [
            "user_id"=>Yii::$app->user->id,
            "id"=>$ids,
        ]);

        $msg = Yii::t("app", "Notification status of the selected cron jobs has been changed");

        Yii::$app->getSession()->setFlash("success", $msg);
        return $this->refresh();
    }

    protected function bulkGeneral($value, array $ids) {
        switch ($value) {
            case "reset":
                Yii::$app->db->transaction(function($db) use($ids) {
                    /**
                     * @var $db Connection
                     */
                    Log::deleteAll(['and', 'user_id=:user_id', ['in', 'schedule_id', $ids]], [
                        "user_id"=>Yii::$app->getUser()->getId(),
                    ]);
                    Stat::deleteAll(['and', 'user_id=:user_id', ['in', 'schedule_id', $ids]], [
                        "user_id"=>Yii::$app->getUser()->getId(),
                    ]);
                });
                Yii::$app->getSession()->setFlash("success", Yii::t("app", "Successfully reset the logs and stat. of {ids} cron job", [
                    "ids"=>implode(",", $ids)
                ]));
            break;
            case "delete":
                Schedule::deleteAll(['and', 'user_id=:user_id', ['in', 'id', $ids]], [
                    "user_id"=>Yii::$app->getUser()->getId(),
                ]);
                Yii::$app->getSession()->setFlash("success", Yii::t("app", "Selected cron jobs have been successfully deleted: {ids}", [
                    "ids"=>implode(",", $ids)
                ]));
            break;
        }
        return $this->refresh();
    }
}