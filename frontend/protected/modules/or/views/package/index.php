<?php

/* @var $insurance EncounterInsurance */
/* @var $pharmacyAreas PharmacyArea */

    Yii::import('bootstrap.components.Bootstrap');
    Yii::import('bootstrap.widgets.TbSelect2');
    Yii::import('bootstrap.widgets.TbButton');
    Yii::import('bootstrap.widgets.TbGridView');
    Yii::import('bootstrap.widgets.TbActiveForm');
    
    Yii::app()->clientScript->registerScript('package-form',<<<JAVASCRIPT
        var val = "";
        var selected = 0;

        $('#packageSelectBtn').on('click', function(e){
            e.preventDefault();

            updateTable();
        });

        $('#packageSelect').on('change', function(){
            updateTable();
        });

        $('input[name="trans_type"]').on('change', function(){
            selected = $(this).val();

            if(selected == 1){
                $('#change_type').prop("disabled", true);
            }
            else{
                $('#change_type').prop("disabled", false);
            }

            if(val != "")
                updateTable();
        });

        function updateTable(){
            var l = window.location;
            var baseUrl = l.protocol + "//" + l.host + "/" + l.pathname.split('/')[1]+'/index.php?r=or/package';
            val = $('#packageSelect').val();

            $('#hiddenPackageId').val(val);

            $.fn.yiiGridView.update('package_details-grid', {
                type: 'GET',
                data: {'search': val, 'is_cash': selected},
                url: baseUrl,
                complete: function(){
                    $('#packageTotalPrice').html($('#package_details-grid').data('package-price'));
                } 
            })
        }



JAVASCRIPT
, CClientScript::POS_READY);

    $form = $this->beginWidget(
        'bootstrap.widgets.TbActiveForm',
        array(
            'id'=>'package-form',
            'method' => 'post',
        )
    );
?>
    <div class="row-fluid">
        <div class="span6" style="margin-bottom:5px">
            <?php
            echo CHtml::tag('label', array('for' => 'trans_type', 'style' => 'display: inline; margin-right: 5px;'), ' Transaction Type: ');
            echo CHtml::tag('input', array('type'=>'radio', 'name' => 'trans_type', 'value' => 1, 'style' => 'margin:0px'), ' cash ');
            echo CHtml::tag('input', array('type'=>'radio', 'name' => 'trans_type', 'value' => 0, 'style' => 'margin:0px', 'checked' => true), ' charge ');
            $charges = array("PERSONAL" => 'TPL', "LINGAP" => 'LINGAP', "CMAP" => 'MAP', "MISSION" => 'MISSION', "PCSO" => 'PCSO');
            if($insurance){
                $charges['PHIC'] = 'PHIC';
            }
            echo CHtml::dropDownList('charge_type', '', $charges, array('class'=>'span3', 'style' => 'margin-left: 5px;'));

            echo CHtml::tag('label', array('for' => 'trans_type', 'style' => 'display: inline; margin-right: 5px; margin-left: 5px'), ' Transaction Type: ');
            echo CHtml::dropDownList('pharmacy_area', '', CHtml::listData($pharmacyAreas,'area_code','area_name'), array('style' => 'margin-left: 5px;'));
            ?>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span6">
            <?php
                $this->widget(
                    'bootstrap.widgets.TbSelect2',
                    array(
                        'name'      => 'packageSelect',
                        'data'      => $packageList,
                        'options'   => array(
                            'minimumInputLength' => '3',
                            'placeholder' => 'Enter the package name.'
                        )
                    )
                );
                $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                        'id'          => 'packageSelectBtn',
                        'label'       => 'Go',
                        'url'         => '#',
                        'size'        => 'small',
                        'htmlOptions' => array(
                            'style' => 'margin-left: 1em'
                        )
                    )
                );
            ?>
        </div>
        <div class="span6">
            <?php
                echo CHtml::tag('h5', 
                    array('class' => 'pull-right'), 
                    'Total Price: <span id="packageTotalPrice">0.00</span>'
                );
            ?>
        </div>
    </div>
    <hr/>
<?php
    $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => 'Submit',
            'htmlOptions' => array(
                'class' => 'pull-right'
            )
        )
    );

    $template = "
        <div class='row-fluid'>
            <div class='pull-left'>{summary}</div>
            {items}
            <div class='pull-right'>{pager}</div>
        </div>
    ";

    $this->widget(
        'bootstrap.widgets.TbGridView', 
        array(
            'id' => 'package_details-grid',
            'type' => 'striped',
            'dataProvider' => $dataProvider,
            'template' => $template,
            'pagerCssClass' => 'pagination pull-right',
            'columns' => array(
                array(
                    'name' => 'item_code',
                    'header' => 'Item Code'
                ),
                array(
                    'name' => 'item_name', 
                    'header' => 'Item Name'
                ),
                array(
                    'name' => 'quantity', 
                    'header' => 'Quantity'
                ),
                array(
                    'value' => function($data, $row){
                        return number_format($data['price'], 2);
                    },
                    'header' => 'Unit Price',
                    'headerHtmlOptions' => array(
                        'style' => 'text-align: right;'
                    ),
                ),
                array(
                    'value' => function($data, $row){
                        return number_format(($data['price'] * $data['quantity']), 2);
                    },
                    'header' => 'Total Price',
                    'headerHtmlOptions' => array(
                        'style' => 'text-align: right;'
                    ),
                ),
            ),
            'htmlOptions' => array(
                'data-package-price' => number_format($package_price, 2)
            )
        )
    );
    echo CHtml::hiddenField('encounter_nr', $encounter_nr);
    $this->endWidget();
?>