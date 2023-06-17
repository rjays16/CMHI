<?php
/* @var $this ReferralController */
/* @var $model SocialReferrals */

$baseUrl = Yii::app()->request->baseUrl;
$cs = Yii::app()->clientScript;
$cs->registerCss('sservice-added-css',<<<CSS
				body ul.breadcrumb{
                    margin-top: -48px;
                }
                body div#padding{
                    padding:10px;
                }

                table tbody tr td, table thead tr th{
                    font-size: 12px;
                }
CSS
	);

$js = <<<JS

function printReferral(id, enc){
	window.open("modules/reports/reports/PDPU_Assessment_Tool.php?id="+id+"&enc_nr="+enc);
}

JS;

$cs->registerScript('js', $js, CClientScript::POS_HEAD);


$this->breadcrumbs=array(
	'PDPU' => $baseUrl . '/modules/pdpu/pdpu_main.php',
	'Assessment and Referral Form'
);
$this->pageTitle = 'PDPU - Assessment and Referral Form';
?>

<h3 align="center">Assessment and Referral Form</h3>
<hr/>

<?php
$this->beginWidget('application.widgets.SegBox', array(
	'title' => 'List of Referrals',
	'headerIcon' => 'fa fa-files-o',
	'headerButtons' => array(
		array(
			'class' => 'bootstrap.widgets.TbButton',
			'label' => 'New Referral',
			'type' => 'success',
			'icon' => 'fa fa-file-o',
			'url' => 'index.php?r=pdpu/referral/create',
		),
	),
));

//$data = $model->search();
$this->widget('bootstrap.widgets.TbGridView', array(
	'dataProvider' => $model->search(),
	'filter' => $model,
	'type' => 'bordered',
	'columns' => array(
		array(
			'name' => 'refer_dt',
			'header' => 'Date Referred',
			'value'=> 'Yii::app()->dateFormatter->format("MM/dd/yyyy",strtotime($data->refer_dt))',
			'headerHtmlOptions' => array(
				'style' => 'text-align: center;width: 150px;'
			),
			'htmlOptions' => array(
				'style' => 'text-align: center;'
			),
		),
		array(
			'name' => 'pid',
			'header' => 'HRN',
			'headerHtmlOptions' => array(
				'style' => 'text-align: center; width: 150px;'
			),
			'htmlOptions' => array(
				'style' => 'text-align: center;'
			),
		),
		array(
			'name' => 'encounter_nr',
			'header' => 'Encounter #',
			'headerHtmlOptions' => array(
				'style' => 'text-align: center;width: 200px;'
			),
			'htmlOptions' => array(
				'style' => 'text-align: center;'
			),
		),
		array(
			'name' => 'person.fullName',
			'header' => 'Name of Patient',
			'headerHtmlOptions' => array(
				'style' => 'text-align: center;'
			),
		),
		array(
			'class' => 'pdpu.widgets.CustomButton',
			'header' => 'Actions',
			'template' => '{Print}{view}',
			'buttons' => array(
				'Print' => array(
					'icon' => 'fa fa-eye',
					'label' => 'Assessment Form',
					'visible' => '($data->mss_no !== "0")',
					'options' => array(
						'class' => 'btn btn-small',
						'onclick' => '',
						'id' => '$data->refer_id',
						'enc' => '$data->encounter_nr',
						'function' => 'printReferral',
						'style' => 'margin-right: 5px;',
					),
				),
				'view' => array(
					'icon' => 'fa fa-pencil',
					'label' => 'Referral Form',
					'options' => array(
						'class' => 'btn btn-small',
					),
				),
			),
			'htmlOptions'=>array(
				'style'=>'width: 110px; text-align: center',
			),
			'headerHtmlOptions' => array(
				'style' => 'text-align: center;'
			),
		),
	)
));

$this->endWidget();