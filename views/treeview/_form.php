<?php

namespace dmstr\modules\pages\views\treeview;

/*
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2015
 * @package yii2-tree-manager
 * @version 1.5.0
 */

use dmstr\modules\pages\models\Tree;
use insolita\wgadminlte\Box;
use insolita\wgadminlte\SmallBox;
use kartik\form\ActiveForm;
use kartik\tree\TreeView;
use rmrevin\yii\fontawesome\FA;
use Yii;
use yii\helpers\Html;
use yii\helpers\Inflector;

/**
 * @var $this  \yii\web\View
 * @var $form \kartik\form\ActiveForm
 * @var $node \dmstr\modules\pages\models\Tree
 */

$this->registerJs(
    "$(function () {
        $('[data-toggle=\'tooltip\']').tooltip({'html': false});
    });"
);

// Extract $_POST to @vars
extract($params);

// Set isAdmin @var
$isAdmin = ($isAdmin == true || $isAdmin === 'true');

if (empty($parentKey)) {
    $parent = $node->parents(1)->one();
    $parentKey = empty($parent) ? '' : Html::getAttributeValue($parent, $keyAttribute);
}

$inputOpts = [];
$flagOptions = ['class' => 'kv-parent-flag'];

if (!$node->isNewRecord) {
    if ($node->isReadonly()) {
        $inputOpts['readonly'] = true;
    }
    if ($node->isDisabled()) {
        $inputOpts['disabled'] = true;
    }
    $flagOptions['disabled'] = $node->isLeaf();
}

/*
 * Begin active form
 * @controller NodeController
 */
$form = ActiveForm::begin(['action' => $action]);

// Get tree manager module
$treeViewModule = TreeView::module();

// create node Url
$nodeUrl = $node->createUrl();

// In case you are extending this form, it is mandatory to set
// all these hidden inputs as defined below.
echo Html::hiddenInput("Tree[{$keyAttribute}]", $node->id);
echo Html::hiddenInput('treeNodeModify', $node->isNewRecord);
echo Html::hiddenInput('parentKey', $parentKey);
echo Html::hiddenInput('currUrl', $currUrl);
echo Html::hiddenInput('modelClass', $modelClass);
echo Html::hiddenInput('softDelete', $softDelete);
?>
<div class="vertical-spacer"></div>

<?php if ($nodeUrl !== null) : ?>
    <?= SmallBox::widget(
        [
            'head'        => $node->name,
            'type' => SmallBox::TYPE_GRAY,
            'text'        => $nodeUrl,
            'icon'        => 'fa fa-' . $node->icon,
            'footer'      => 'Open',
            'footer_link' => $nodeUrl
        ]
    ) ?>
<?php endif; ?>
<div class="clearfix"></div>

<?php if ($iconsList == 'text' || $iconsList == 'none') : ?>
        <?php Box::begin(
            [
                'title'    => Yii::t('kvtree', 'General'),
                'collapse' => true
            ]
        ) ?>
        <div class="row">
            <div class="col-sm-6">

                <?= $form->field(
                    $node,
                    $nameAttribute,
                    [
                        'addon' => ['prepend' => ['content' => Inflector::titleize('menu_name')]],
                    ]
                )->textInput($inputOpts)->label(false) ?>
            </div>

            <div class="col-sm-6">
                <?php if (isset($treeViewModule->treeViewSettings['fontAwesome']) && $treeViewModule->treeViewSettings['fontAwesome'] == true): ?>
                    <?= $form->field($node, $iconAttribute)->widget(
                        \kartik\select2\Select2::classname(),
                        [
                            'name' => 'Tree['.$iconAttribute.']',
                            'model' => $node,
                            'attribute' => $iconAttribute,
                            'addon' => [
                                'prepend' => [
                                    'content' => Inflector::titleize($iconAttribute),
                                ],
                            ],
                            'data' => FA::getConstants(true),
                            'options' => [
                                'id' => 'tree-'.$iconAttribute,
                                'placeholder' => Yii::t('pages', 'Type to autocomplete'),
                                'multiple' => false,
                            ],
                            'pluginOptions' => [
                                'escapeMarkup' => new \yii\web\JsExpression('function(m) { return m; }'),
                                'allowClear' => true,
                            ],
                        ]
                    )->label(false); ?>
                <?php else: ?>
                    <?= $form->field(
                        $node,
                        $iconAttribute,
                        [
                            'addon' => ['prepend' => ['content' => Inflector::titleize($iconAttribute)]],
                        ]
                    )->textInput($inputOpts)->label(false) ?>
                <?php endif; ?>
            </div>

        </div>
        <?php Box::end() ?>

        <?php Box::begin(
            [
                'title'           => Yii::t('kvtree', 'Options'),
                'collapse'          => true,
                'collapse_remember' => false,
                'collapseDefault'   => true
            ]
        ) ?>
        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <?= $form->field($node, 'visible')->checkbox() ?>
            </div>
            <div class="col-xs-12 col-sm-2">
                <?= $form->field($node, 'disabled')->checkbox() ?>
            </div>
            <div class="col-xs-12 col-sm-2">
                <?= $form->field($node, 'collapsed')->checkbox($flagOptions) ?>
            </div>
        </div>
        <?php Box::end() ?>
        <?php if (true) : ?>
            <?php Box::begin(
                [
                    'title'    => Yii::t('kvtree', Yii::t('kvtree', 'Route')),
                    'collapse'          => true,
                    'collapse_remember' => false,
                    'collapseDefault'   => !$node->isPage()
                ]
            ) ?>
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <?= $form->field(
                        $node,
                        Tree::ATTR_ACCESS_DOMAIN,
                        [
                            'addon' => ['prepend' => ['content' => 'Access Domain']],
                        ]
                    )->dropDownList(Tree::optsAccessDomain())->label(false) ?>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <?= $form->field($node, Tree::ATTR_ROUTE)->widget(
                        \kartik\select2\Select2::classname(),
                        [
                            'name' => Html::getInputName($node, Tree::ATTR_ROUTE),
                            'model' => $node,
                            'attribute' => Tree::ATTR_ROUTE,
                            'addon' => [
                                'prepend' => [
                                    'content' => 'Route',
                                ],
                            ],
                            'data' => Tree::optsRoute(),
                            'options' => [
                                'placeholder' => Yii::t('pages', 'Select route'),
                                'multiple' => false,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]
                    )->label(false);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <?= $form->field($node, Tree::ATTR_VIEW)->widget(
                        \kartik\select2\Select2::classname(),
                        [
                            'name' => Html::getInputName($node, Tree::ATTR_VIEW),
                            'model' => $node,
                            'attribute' => Tree::ATTR_VIEW,
                            'addon' => [
                                'prepend' => [
                                    'content' => 'Available Views',
                                ],
                            ],
                            'data' => Tree::optsView(),
                            'options' => [
                                'id' => 'tree-views',
                                'placeholder' => Yii::t('pages', 'Type to autocomplete'),
                                'multiple' => false,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]
                    )->label(false); ?>
                </div>

            </div>
            <?php Box::end() ?>

            <?php Box::begin(
                [
                    'title'           => Yii::t('kvtree', Yii::t('kvtree', 'SEO')),
                    'collapse'          => true,
                    'collapse_remember' => false,
                    'collapseDefault'   => !$node->isPage()
                ]
            ) ?>
            <div class="row">
                <div class="col-xs-12">
                    <?= $form->field(
                        $node,
                        'page_title',
                        [
                            'addon' => ['prepend' => ['content' => Inflector::titleize('page_title')]],
                        ]
                    )->textInput($inputOpts)->label(false) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-lg-12">
                    <?= $form->field(
                        $node,
                        'default_meta_keywords',
                        [
                            'addon' => ['prepend' => ['content' => 'Keywords']],
                        ]
                    )->textInput()->label(false) ?>
                </div>
                <div class="col-xs-12 col-lg-12">
                    <?= $form->field(
                        $node,
                        'default_meta_description',
                        [
                            'addon' => ['prepend' => ['content' => 'Description']],
                        ]
                    )->textarea(['rows' => 5])->label(false) ?>
                </div>
            </div>
            <?php if ($node->route && $nodeUrl !== null) : ?>
                <div class="row">
                    <div class="col-xs-12 col-lg-12">
                        <?= $form->field(
                            $node,
                            'slug',
                            [
                                'addon' => [
                                    'prepend' => [
                                        'content' => \Yii::t('crud', 'Page URL'),
                                    ],
                                ],
                            ]
                        )->textInput(
                            [
                                'value' => $nodeUrl,
                                'disabled' => true,
                            ]
                        )->label(false)->hint(
                            FA::icon('info-circle').' '.
                            \Yii::t(
                                'crud',
                                'Automatically generated from page title.'
                            ).' '.
                            \Yii::t(
                                'crud',
                                'To change URL change page title above.'
                            ),
                            ['class' => 'hints']
                        ) ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php Box::end() ?>

            <?php Box::begin(
                [
                    'title'             => Yii::t('kvtree', Yii::t('kvtree', 'Advanced')),
                    'collapse'          => true,
                    'collapse_remember' => false,
                    'collapseDefault'   => true
                ]
            ) ?>
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <?= $form->field(
                        $node,
                        Tree::ATTR_DOMAIN_ID,
                        [
                            'addon' => ['prepend' => ['content' => 'Local Domain ID']],
                        ]
                    )->textInput()->label(false) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field(
                        $node,
                        'name_id',
                        [
                            'addon' => ['prepend' => ['content' => 'Name ID']],
                        ]
                    )->textInput(['value' => $node->getNameId(), 'disabled' => 'disabled'])->label(false) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($node, $iconTypeAttribute)->widget(
                        \kartik\select2\Select2::classname(),
                        [
                            'name' => 'Tree['.$iconTypeAttribute.']',
                            'model' => $node,
                            'attribute' => $iconTypeAttribute,
                            'addon' => [
                                'prepend' => [
                                    'content' => Inflector::titleize($iconTypeAttribute),
                                ],
                            ],
                            'data' => [
                                TreeView::ICON_CSS => 'CSS Suffix',
                                TreeView::ICON_RAW => 'Raw Markup',
                            ],
                            'options' => [
                                    'id' => 'tree-'.$iconTypeAttribute,
                                    'placeholder' => Yii::t('pages', 'Select'),
                                    'multiple' => false,
                                ] + $inputOpts,
                            'pluginOptions' => [
                                'allowClear' => false,
                            ],
                        ]
                    )->label(false);
                    ?>
                </div>
                <div class="col-xs-12">
                    <?= $form->field(
                        $node,
                        'request_params',
                        [
                            'addon' => ['prepend' => ['content' => Inflector::titleize('request_params')]],
                        ]
                    )->widget(\devgroup\jsoneditor\Jsoneditor::className(), ['model' => $node, 'attribute' => 'request_params'])->label(false) ?>
                </div>
            </div>
            <?php Box::end() ?>
        <?php endif; ?>


    <?php Box::begin(
        [
            'title' => Yii::t('kvtree', Yii::t('kvtree', 'Access')),
            'collapse' => true,
            'collapse_remember' => false,
            'collapseDefault' => true,
        ]
    ) ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?=
            $form
                ->field($node,
                    'access_read',
                    [
                        'addon' => ['prepend' => ['content' => 'Access Read']],
                    ]
                )
                ->dropDownList($node->getUsersAuthItems())
                ->label(false)
            ?>
        </div>
    </div>
    <?php Box::end() ?>


<?php else : ?>
    <div class="row">
        <div class="col-sm-6">
            <?= Html::activeHiddenInput($node, $iconTypeAttribute) ?>
            <?= $form->field(
                $node,
                $nameAttribute,
                [
                    'addon' => ['prepend' => ['content' => Inflector::titleize($iconTypeAttribute)]],
                ]
            )->textArea(['rows' => 2] + $inputOpts)->label(false) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field(
                $node,
                $iconAttribute,
                [
                    'addon' => ['prepend' => ['content' => Inflector::titleize($iconTypeAttribute)]],
                ]
            )->multiselect(
                $iconsList,
                [
                    'item' => function ($index, $label, $name, $checked, $value) use ($inputOpts) {
                        if ($index == 0 && $value == '') {
                            $checked = true;
                            $value = '';
                        }

                        return '<div class="radio">'.Html::radio(
                            $name,
                            $checked,
                            [
                                'value' => $value,
                                'label' => $label,
                                'disabled' => !empty($inputOpts['readonly']) || !empty($inputOpts['disabled']),
                            ]
                        ).'</div>';
                    },
                    'selector' => 'radio',
                ]
            )->label(false) ?>
        </div>
    </div>
<?php endif; ?>

<?php if (empty($inputOpts['disabled']) || ($isAdmin && $showFormButtons)): ?>
    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton(
                '<i class="glyphicon glyphicon-floppy-disk"></i> '.Yii::t('kvtree', 'Save'),
                ['class' => 'btn btn-lg btn-primary']
            ) ?>
            <?= Html::resetButton(
                '<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('kvtree', 'Reset'),
                ['class' => 'btn btn-lg btn-default']
            ) ?>
        </div>
    </div>
<?php endif; ?>

<?php ActiveForm::end() ?>
