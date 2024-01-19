<?php

namespace app\components;

use yii\helpers\ArrayHelper;

final class GridView extends \yii\grid\GridView {

    /**
     * {@inheritdoc}
     */
    public function __construct($config = []) {
        $options = [
            'layout' => '<div class="table-responsive">{items}<div class="text-muted m-2">{summary}</div></div>',
            'tableOptions' => ['class' => 'table table-vcenter card-table'],
//            'filterSelector' => '.gridview-pagesize',
//            'pager' => [
//                'options' => ['class' => 'pagination m-0 float-right'],
//                'linkOptions' => ['class' => 'page-link'],
//                'linkContainerOptions' => ['class' => 'page-item'],
//                'disabledListItemSubTagOptions' => [
//                    'tag' => 'a',
//                    'href' => '#',
//                    'class' => 'page-link'
//                ]
//            ]
        ];

        parent::__construct(ArrayHelper::merge($options, $config));
    }
}