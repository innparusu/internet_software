<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>

<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $this->fetch('title_for_layout'); ?>
    </title>
    <?php
        echo $this->Html->meta('icon');
        // flocss
        echo $this->Html->css('foundations/normalize');
        echo $this->Html->css('objects/projects/chat');
        echo $this->Html->css('objects/utilities/margin');
        echo $this->Html->css('objects/utilities/padding');
        echo $this->Html->css('objects/utilities/text');
        echo $this->Html->css('objects/utilities/size');
        echo $this->Html->css('objects/utilities/position');
        // jquery
        echo $this->Html->script( 'jquery-2.1.1.min', array( 'inline' => false ));
        // Made javascript
        echo $this->Html->script( 'chat.js'.'?'.time(), array( 'inline' => false ));
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        echo $this->Js->writeBuffer(array( 'inline' => 'true' ));
        echo $this->Html->css('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css');
        echo $this->Html->script( '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js');
    ?>
</head>
<body>
    <div id="container" class="container">
        <div class="l-header">
            <h1><?php echo $this->Html->link($cakeDescription, 'http://cakephp.org'); ?></h1>
        </div>
        <div class="p-content">

            <?php echo $this->Session->flash(); ?>

            <?php echo $this->fetch('content'); ?>
        </div>
        <div class="l-footer">
<?php echo $this->Html->link(
    $this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
    'http://www.cakephp.org/',
    array('target' => '_blank', 'escape' => false)
);
?>
        </div>
    </div>
    <?php echo $this->element('sql_dump'); ?>
</body>
</html>
