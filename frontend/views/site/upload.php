<?php

namespace frontend\models;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use Yii;
use yii\bootstrap4\Html;
use yii\captcha\Captcha;
use Noweh\TwitterApi\Client;
use yii\widgets\ActiveForm;
use yii\base\Model;
use yii\helpers\FileHelper;

$this->title = 'Post on Twitter';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Use the form bellow to post on Twitter. Thank you.
    </p>

    <div class="row">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <button>Submit</button>
    <?php
    //Twitter
    function tweet($message,$image) {
        // add the codebird library
        require_once('libraries/codebird/src/codebird.php');
        // note: consumerKey, consumerSecret, accessToken, and accessTokenSecret all come from your twitter app at https://apps.twitter.com/
        \Codebird\Codebird::setConsumerKey('zlXzEjHgzpGeNmbD6atjQSd7R', 'moqrLzIoPhs0Nosmgx0PgR4uU7X8mNSEWbhm2dKn0RwP9LTb9Y');
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken('189616185-10tUfntMOGLpOzDfLmKSPzNtHR2DhzO2BD8qMv6H', 'nu5vfKuKzMjv9rqKqplO2j4QohSIrtsuspFCsrgUtECdL');
        //build an array of images to send to twitter
        $reply = $cb->media_upload(array(
            'media' => $image
        ));
        //upload the file to your twitter account
        $mediaID = $reply->media_id_string;
        //build the data needed to send to twitter, including the tweet and the image id
        $params = array(
            'status' => $message,
            'media_ids' => $mediaID
        );
        //post the tweet with codebird
        $reply = $cb->statuses_update($params);
        }
        ?>

    <?php 
    
    //Sort by filetime and post the latest
    $files = glob('uploads/*');
    usort($files, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });

    //print_r($files);
    tweet('Image uploaded!', 'http://frontend.yii/'.$files[0]); ?>
    
    <?php ActiveForm::end() ?>

    </div>
</div>



