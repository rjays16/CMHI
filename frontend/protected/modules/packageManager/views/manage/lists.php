<?php

$this->beginWidget('bootstrap.widgets.TbBox', array(
    'title'=>'List of Packages'
));

$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $search->search(),
    'filter' => $search,
    'type' => 'bordered striped hover',
    'columns' => array(
        array(
            'name' => 'package_name',
            'headerHtmlOptions' => array(
                'style' => 'text-align: center;',
            ),
        ),
        array(
            'name' => 'is_er',
            'type' => 'raw',
            'filter' => false,
            'value' => function($data){
                $er = ($data->is_er == 0) ? '<span style="color: #990000"><b>NO</b></span>' : '<b>YES</b>';

                return $er;
            },
            'headerHtmlOptions' => array(
                'style' => 'text-align: center;',
            ),
            'htmlOptions' => array(
                'width' => '50px',
                'style' => 'text-align: center;',
            ),
        ),
        array(
            'name' => 'is_ipd',
            'type' => 'raw',
            'filter' => false,
            'value' => function($data){
                $ipd = ($data->is_ipd == 0) ? '<span style="color: #990000"><b>NO</b></span>' : '<b>YES</b>';

                return $ipd;
            },
            'headerHtmlOptions' => array(
                'style' => 'text-align: center;',
            ),
            'htmlOptions' => array(
                'width' => '50px',
                'style' => 'text-align: center;',
            ),
        ),
        array(
            'name' => 'is_opd',
            'type' => 'raw',
            'filter' => false,
            'value' => function($data){
                $opd = ($data->is_opd == 0) ? '<span style="color: #990000"><b>NO</b></span>' : '<b>YES</b>';

                return $opd;
            },
            'headerHtmlOptions' => array(
                'style' => 'text-align: center;',
            ),
            'htmlOptions' => array(
                'width' => '50px',
                'style' => 'text-align: center;',
            ),
        ),
        array(
            'name' => 'is_deleted',
            'type' => 'raw',
            'filter' => false,
            'value' => function($data){
                $del = ($data->is_deleted == 0) ? '<b>ACTIVE</b>' : '<span style="color: #990000"><b>DELETED</b></span>';

                return $del;
            },
            'headerHtmlOptions' => array(
                'style' => 'text-align: center;',
            ),
            'htmlOptions' => array(
                'width' => '80px',
                'style' => 'text-align: center;',
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view}',
            'header' => 'View',
        ),
    ),
));

$this->endWidget();