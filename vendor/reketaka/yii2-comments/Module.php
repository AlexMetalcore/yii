<?php

namespace reketaka\comments;

use reketaka\comments\widgets\CommentFormWidget;
use Yii;

class Module extends \yii\base\Module{

    /**
     * Пытаться увеличить счетчик комментариев в дочерних моделях
     * @var bool
     */
    public $increaseCounters = true;
    /**
     * Могут ли гости вообще комментировать
     * @var bool
     */
    public $guestCanComment = false;
    /**
     * Активны ли гостевые комментарии сразу без проверки
     * @var bool
     */
    public $isGuestActive = false;

    /**
     * Актвны ли пользовательские комментарии сразу без провери
     * @var bool
     */
    public $isUserActive = true;

    /**
     * Настройки виджета, вывода формы для заполнения комментария
     * @var array
     */
    public $commentFormWidget = [
        'viewPath'=>'@reketaka/comments/views/widgets/CommentFormWidget/index'
    ];

    public $commentListWidget = [
        'viewPath'=>'@reketaka/comments/views/widgets/CommentListWidget/index'
    ];

    /**
     * Имя ключа из массива настроек Yii::$app->params['dateControlSave']
     * @var string
     */
    public $dateControlSaveKey = 'dateControlSave';

    public $identifyUserClass = '\app\common\UserWeb';

    public $controllerNamespace = 'reketaka\comments\controllers';

    /**
     * Название поведения
     * @var string
     */
    public $commentBehaviorName = 'comments';

    public function init(){
        parent::init();
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        if ( !isset(Yii::$app->i18n->translations['modules/comments/*']) )
        {
            Yii::$app->i18n->translations['modules/comments/*'] = [
                'class'          => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath'       => '@reketaka/comments/messages',
                'fileMap'        => [
                    'modules/comments/base' => 'base.php',
                    'modules/comments/errors'=>'errors.php'
                ],
            ];
        }

        return Yii::t('modules/comments/' . $category, $message, $params, $language);
    }

}

?>