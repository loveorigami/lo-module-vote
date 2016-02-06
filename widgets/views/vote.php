<div class="text-center">
    <em>
        <div id="fav-<?=$modelId?>-<?=$targetId?>" class="btn btn-success btn-xs" onclick="fav(<?=$modelId?>, <?=$targetId ?>, 'fav'); return false;">
            <i class="fa fa-heart"></i> <span class="votes"> <?= $favs ?: 0 ?></span>
        </div>
        <div id="vote-up-<?= $modelId ?>-<?= $targetId ?>" class="btn btn-success btn-xs"
              onclick="vote(<?= $modelId ?>, <?= $targetId ?>, 'like'); return false;">
            <i class="fa fa-thumbs-up"></i><span class="votes"><?= $likes ?: 0 ?></span>
        </div>
        <div id="vote-down-<?= $modelId ?>-<?= $targetId ?>" class="btn btn-danger btn-xs"
              onclick="vote(<?= $modelId ?>, <?= $targetId ?>, 'dislike'); return false;">
            <i class="fa fa-thumbs-down"></i><span class="votes"><?= $dislikes ?: 0 ?></span>
        </div>
    
    <div id="vote-response-<?= $modelId ?>-<?= $targetId ?>">
        <?php if ($showAggregateRating) { ?>
            <?= Yii::t('vote', 'Aggregate rating') ?>: <?= $rating ?>
        <?php } ?>
    </div>
    </em>
</div>
