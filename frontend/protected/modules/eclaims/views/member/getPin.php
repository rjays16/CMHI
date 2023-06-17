<?php
/* @var $this Controller */
/*this view is for getpin module to have tabs that separate searching from patient and others*/

$this->setPageTitle('PIN Verification Utility');

$encounter = $person->latestEncounter;

if (!empty($_REQUEST['pid'])) {
    if (!empty($encounter->isNewRecord)) {
        $errorMessages[] = 'This patient currently does not have an encounter';
    }
}
// CVarDumper::dump($encounter->isNewRecord);die;
if (!empty($errorMessages)) {
    $listMessages = '<ul><li>' . implode('</li><li>', $errorMessages) . '</li></ul>';
    Yii::app()->user->setFlash('warning', '<strong>Warning!</strong> ' . $listMessages);
}

Yii::app()->getClientScript()->registerScript('member.pin.walkin', <<<JAVASCRIPT
$('.service-form').submit(function() {
    Alerts.loading({ content: 'Contacting PHIC web service. Please wait...' });
});

$('#check-pin').click(function(e) {
    e.preventDefault();
    $('.service-form:visible').submit();
});

$('#go-to-eligibility').click(function() {
    var _button = $(this);
    if(_button.hasClass('disabled')) 
        return false;
    Alerts.loading({ content: 'Redirecting to Verify Eligibity Page. Please wait...' });
});

$('#reflect-insurance-billing').click(function(e) {
    var _button = $(this);
    if(_button.hasClass('disabled')) 
        return false;
    e.preventDefault();

    Alerts.confirm({
        title: "Are you sure?",
        content: _button.data('alert-message'),
        callback: function(result) {
            if(result) {
                window.location = _button.attr('href');
                Alerts.loading({ content: 'Adding insurance to the billing. Please wait...' });
            }
        }
    });
});

$('#walkin-tab > a').on('click', function() {
    $('#go-to-eligibility').hide();
});
$('#search-tab > a').on('click', function() {
    $('#go-to-eligibility').show();
});
JAVASCRIPT
    , CClientScript::POS_READY);
?>


<div class="row-fluid">
    <div class="span12">

        <?php
        Yii::import('bootstrap.widgets.TbButton');
        $insuranceButton = array(
            'class' => 'bootstrap.widgets.TbButton',
            'id' => 'reflect-insurance-billing',
            'buttonType' => TbButton::BUTTON_LINK,
            'type' => TbButton::TYPE_INFO,
            'icon' => 'fa fa-plus',
            'label' => 'Add Insurance',
            'url'   => $this->createUrl('manageInsuranceToBilling', array(
                'pid' => $person->pid,
                'action' => 'add'
            )),
            'disabled' => empty($person->currentEncounter) || $hasFinalBill,
            'htmlOptions' => array(
                'data-alert-message' => 'Add this insurance to the billing record of the patient.'
            )
        );
        if(!empty($person->currentEncounter->encounterInsurance)) {
            $insuranceButton = CMap::mergeArray($insuranceButton, array(
                'type'        => TbButton::TYPE_DANGER,
                'icon'        => 'fa fa-minus',
                'label'       => 'Remove Insurance',
                'url'         => $this->createUrl('manageInsuranceToBilling', array(
                    'pid' => $person->pid,
                    'action' => 'remove'
                )),
                'htmlOptions' => array(
                    'data-alert-message' => 'Remove this insurance from the billing record of the patient.'
                )
            ));
        }
        $box = $this->beginWidget(
            'application.widgets.SegBox',
            array(
                'title' => 'Get PIN',
                'headerIcon' => 'icon-cog',
                'htmlOptions' => array('class' => ''),
                'headerButtons' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbButton',
                        'id' => 'go-to-eligibility',
                        'buttonType' => TbButton::BUTTON_LINK,
                        'type' => TbButton::TYPE_PRIMARY,
                        'icon' => 'fa fa-link',
                        'label' => 'Go To Eligibility',
                        'url'   => $this->createUrl('eligibility/index', array(
                            'id' => $person->pid
                        )),
                        'disabled' => empty($person->pid)
                    ),
                    $insuranceButton,
                    array(
                        'class' => 'bootstrap.widgets.TbButton',
                        'id' => 'check-pin',
                        'buttonType' => TbButton::BUTTON_BUTTON,
                        'type' => TbButton::TYPE_SUCCESS,
                        'icon' => 'fa fa-check',
                        'loadingText' => 'Checking PIN ...',
                        'label' => 'Check PIN',
                        'htmlOptions' => array(
                            'class' => 'getpinButton',
                        )
                    ),
                ),
            )
        );

        ?>


        <?php
        $this->widget('bootstrap.widgets.TbTabs', array(
            'tabs' => array(
                array(
                    'label' => 'Search records',
                    'active' => (@$_POST['tab'] !== 'walkin'),
                    'itemOptions' => array(
                        'id' => 'search-tab'
                    ),
                    'content' => $this->renderPartial('tabs/searchPatient', array(
                        'model' => $model,
                        'member' => $member,
                        'person' => $person,
                        'hasFinalBill' => $hasFinalBill,
                    ), true)
                ),
                array(
                    'label' => 'Walk-in',
                    'active' => (@$_POST['tab'] == 'walkin'),
                    'itemOptions' => array(
                        'id' => 'walkin-tab'
                    ),
                    'content' => $this->renderPartial('tabs/walkin', array(
                        'model' => $model,
                        'member' => $member,
                        'person' => $person,
                    ), true),
                )
            )
        ));
        ?>

        <?php $this->endWidget(); /* box */ ?>

    </div>
</div>