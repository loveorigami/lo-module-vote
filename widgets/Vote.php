<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lo\modules\vote\widgets;

use lo\modules\vote\models\Rating;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;
use yii\web\JsExpression;
use yii\helpers\Json;
use Yii;

class Vote extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $voteUrl;
    public $favUrl;

    /**
     * @var bool
     */
    public $showAggregateRating = false;

    /**
     * @var string
     */
    public $jsBeforeVote = "
        $('#item-' + model + '-' + target).loading();
    ";

    /**
     * @var string
     */
    public $jsAfterVote = "
        $('#item-' + model + '-' + target).loading('stop');
    ";

    /**
     * @var string
     */
    public $jsCodeKey = 'vote';

    /**
     * @var string
     */
    public $jsErrorVote = "
        jQuery('#vote-response-' + model + '-' + target).html(errorThrown);
    ";

    /**
     * @var string
     */
    public $jsShowMessage = "
        var n = Noty('vote');
        $.noty.setText(n.options.id, data.content);
        $.noty.setType(n.options.id, data.type);
        //jQuery('#vote-response-' + model + '-' + target).html(data.content);
    ";

    /**
     * @var string
     */
    public $jsChangeCounters = "
        if (typeof(data.success) !== 'undefined') {

            var idFav = '#fav-' + model + '-' + target + ' span';
            var idUp = '#vote-up-' + model + '-' + target + ' span';
            var idDown = '#vote-down-' + model + '-' + target + ' span';

            if (act === 'fav-add') {
                jQuery(idFav).text(parseInt(jQuery(idFav).text()) + 1);
            } else if (act === 'fav-del') {
                jQuery(idUp).text(parseInt(jQuery(idFav).text()) - 1);
            } else if (act === 'like') {
                jQuery(idUp).text(parseInt(jQuery(idUp).text()) + 1);
            } else {
                jQuery(idDown).text(parseInt(jQuery(idDown).text()) + 1);
            }

            if (typeof(data.changed) !== 'undefined') {
                if (act === 'like') {
                    jQuery(idDown).text(parseInt(jQuery(idDown).text()) - 1);
                } else {
                    jQuery(idUp).text(parseInt(jQuery(idUp).text()) - 1);
                }
            }
        }
    ";

    public function init()
    {
        parent::init();
        VoteAsset::register($this->view);

        if (!isset($this->model)) {
            throw new InvalidParamException(Yii::t('vote', 'Model not configurated'));
        }

        if (!isset($this->voteUrl)) {
            $this->voteUrl = Yii::$app->getUrlManager()->createUrl(['vote/default/vote']);
        }

        if (!isset($this->favUrl)) {
            $this->favUrl = Yii::$app->getUrlManager()->createUrl(['vote/default/fav']);
        }

        $js = new JsExpression("
            function vote(model, target, act) {
                jQuery.ajax({ 
                    url: '$this->voteUrl', type: 'POST', dataType: 'json', cache: false,
                    data: { modelId: model, targetId: target, act: act },
                    beforeSend: function(jqXHR, settings) { $this->jsBeforeVote },
                    success: function(data, textStatus, jqXHR) { $this->jsChangeCounters },
                    complete: function(jqXHR, textStatus) { $this->jsAfterVote },
                    error: function(jqXHR, textStatus, errorThrown) { $this->jsErrorVote }
                });
            }

            function fav(model, target, act) {
                jQuery.ajax({
                    url: '$this->favUrl', type: 'POST', dataType: 'json', cache: false,
                    data: { modelId: model, targetId: target, act: act },
                    beforeSend: function(jqXHR, settings) { $this->jsBeforeVote },
                    success: function(data, textStatus, jqXHR) { $this->jsChangeCounters },
                    complete: function(jqXHR, textStatus) { $this->jsAfterVote },
                    error: function(jqXHR, textStatus, errorThrown) { $this->jsErrorVote }
                });
            }
        ");

        $this->view->registerJs($js, View::POS_END, $this->jsCodeKey);
    }

    public function run()
    {
        return $this->render('vote', [
            'modelId' => Rating::getModelIdByName($this->model->className()),
            'targetId' => $this->model->{$this->model->primaryKey()[0]},
            'likes' => $this->model->aggregate->likes ?: 0,
            'dislikes' => $this->model->aggregate->dislikes ?: 0,
            'favs' => $this->model->aggregate->favs ?: 0,
            'rating' => $this->model->aggregate->rating ?: 0,
            'showAggregateRating' => $this->showAggregateRating,
            //'voted' => $this->model->voted->id,
            'faved' => $this->model->faved->id,
        ]);
    }
}
