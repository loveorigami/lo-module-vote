<div class="text-center">
    <em>
        <?php if (!$faved): ?>
            <div id="fav-<?= $modelId ?>-<?= $targetId ?>"
                 class="btn btn-success btn-xs"
                 data-original-title="добавить в избранное"
                 data-toggle="tooltip"
                 onclick="fav(<?= $modelId ?>, <?= $targetId ?>, 'fav-add'); return false;">
                <i class="fa fa-star"></i> <span class="votes"> <?= $favs ?></span>
            </div>
        <?php else: ?>
            <div id="fav-<?= $modelId ?>-<?= $targetId ?>"
                 class="btn btn-warning btn-xs"
                 data-original-title="удалить из избранного"
                 data-toggle="tooltip"
                 onclick="return confirm('вы уверены?') ? fav(<?= $modelId ?>, <?= $targetId ?>, 'fav-del') : false;">
                <i class="fa fa-star-o"></i> <span class="votes"> <?= $favs ?></span>
            </div>
        <?php endif; ?>
        <div id="vote-up-<?= $modelId ?>-<?= $targetId ?>"
             class="btn btn-success btn-xs"
             data-original-title="+ 1"
             data-toggle="tooltip"
             onclick="vote(<?= $modelId ?>, <?= $targetId ?>, 'like'); return false;">
            <i class="fa fa-thumbs-up"></i><span class="votes"><?= $likes ?></span>
        </div>
        <div id="vote-down-<?= $modelId ?>-<?= $targetId ?>"
             class="btn btn-danger btn-xs"
             data-original-title="- 1"
             data-toggle="tooltip"
             onclick="vote(<?= $modelId ?>, <?= $targetId ?>, 'dislike'); return false;">
            <i class="fa fa-thumbs-down"></i><span class="votes"><?= $dislikes ?></span>
        </div>

        <div id="vote-response-<?= $modelId ?>-<?= $targetId ?>">
            <?php if ($showAggregateRating) { ?>
                <?= Yii::t('vote', 'Aggregate rating') ?>: <?= $rating ?>
            <?php } ?>
        </div>
    </em>
</div>
