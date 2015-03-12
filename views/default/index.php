<?php
/* @var $this yii\web\View */

use kartik\tree\TreeView;
use dmstr\modules\pages\models\Tree;

echo "<h1>Pages</h1>";

echo TreeView::widget(
    [
        // single query fetch to render the tree
        'query'          => Tree::find()->addOrderBy('root, lft'),
        'headingOptions' => ['label' => 'Categories'],
        'fontAwesome'    => true,     // optional
        'isAdmin'        => true,         // optional (toggle to enable admin mode)
        'displayValue'   => 1,        // initial display value
        'softDelete'     => true,    // normally not needed to change
        //'cacheSettings' => ['enableCache' => true] // normally not needed to change
    ]
);

/**
 * Playground for generating structured menuItems array
 */

\yii\helpers\VarDumper::dump(Tree::getMenuItems('root-1'), 25, true);
