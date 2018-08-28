<?php

/**
 * @var $comments \reketaka\comments\models\Comments[]
 * @var $item
 */

?>


<?php foreach($comments as $comment):
/**
 * @var $comment \reketaka\comments\models\Comments
 */
    ?>

    <li class="comments__item">
        <div class="comments__inner">
            <header class="comment__header">
                <div class="comment__author">
                    <figure class="comment__author-avatar">
                        <img src="/diz/assets/images/samples/avatar-9.jpg" alt="">
                    </figure>
                    <div class="comment__author-info">
                        <h5 class="comment__author-name">Jake Casspon</h5>
                        <time class="comment__post-date" datetime="2016-08-23">2 hours ago</time>
                    </div>
                </div>
            </header>
            <div class="comment__body">
                <?=$comment->content?>
            </div>
        </div>
    </li>

<?php endforeach; ?>