<?php
/** @var $model \app\models\User */

use app\core\Application;
use app\core\CSRFProtector;

?>

<h2 class="text-big"><?=Application::$app->getText("Create an account")?></h2>

<?php $form = \app\core\form\Form::begin('',"post") ?>
    <?php echo $form->getCSRFField(CSRFProtector::getToken()) ?>
    <?php echo $form->field($model, 'email') ?>
    <?php echo $form->field($model, 'password')->passwordField() ?>
    <?php echo $form->field($model, 'confirmPassword')->passwordField() ?>
    <button type="submit" class="btn"><?=Application::$app->getText("Submit")?></button>
<?php \app\core\form\Form::end() ?>
