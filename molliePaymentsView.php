<?php
use Claystone\Dev\DevTeddy\MolliePaymentsListUtility;

set_script_file_name("view_mollie_payments.inc.php");

$status_back_office = $_GET['bo'] ?? 'N'; //(Y/N)

$mollyPaymentsParamsArray = [
        'debCredId'     => $_GET['deb_cred_id']
];

/* @var $oMolliePaymentsListUtility MolliePaymentsListUtility */
$oMolliePaymentsListUtility = $cScontainer->create('\Claystone\Dev\DevTeddy\MolliePaymentsListUtility');
$oMolliePaymentsListUtility->setDebug('N');
$oMolliePaymentsListUtility->setListName($vp2);


if ($status_back_office === 'Y'):
    if(isset($mollyPaymentsParamsArray['debCredId'])):
        $oMolliePaymentsListUtility->setSelDebCredId($mollyPaymentsParamsArray['debCredId']);
    endif;
    $oMolliePaymentsListUtility->setAdminId('229');
    $oMolliePaymentsListUtility->buildQueryBackOffice();
    $oMolliePaymentsListUtility->setqWhereProviderPaymentsDebCredId();
    $oMolliePaymentsListUtility->setSelMainQuery();

else:
    $oMolliePaymentsListUtility->setSelDebCredId($mollyPaymentsParamsArray['debCredId']);
    $oMolliePaymentsListUtility->buildQueryAdministration();
    $oMolliePaymentsListUtility->setqWhereProviderPaymentsDebCredId();
    $oMolliePaymentsListUtility->setSelMainQuery();
endif;

$q_res = $oMolliePaymentsListUtility->setList()->getList();
//$oMolliePaymentsListUtility->setSelStatusPaidByInvoiceId();
//print_array($oMolliePaymentsListUtility->getSelStatusPaidByInvoiceId(), '$oMolliePaymentsListUtility->getSelStatusPaidByInvoiceId()');
/**
 * Method om status paid van een factuur te krijgen
 *
 * vb.
 * if ($oMolliePaymentsListUtility->(invoice id)-> status_paid() === 'Y'  then ...
 *
 */

// exit;
//
?>

<h1>Mollie Payments view</h1>
<br>

<div class="btn-group" role="group" aria-label="Basic example">
    <a
            href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=mollie_payments&vp2=completed&bo=Y"
            class="btn btn-secondary <?php if( $status_back_office === 'Y' ) { echo "active"; } ?>"
    >
        Back Office view
    </a>

    <a
            href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=mollie_payments&vp2=completed"
            class="btn btn-secondary <?php if( $status_back_office === 'N' ) { echo "active"; } ?>"
    >
        Administration View
    </a>
</div>

<!-- grouped relations -->
<?php

if ($status_back_office === 'Y'):

//    echo "BACK OFFICE VIEW <BR>";
    echo "<BR><BR>";
    ?>

    <div class="btn-group" role="group" aria-label="Basic example">
        <a
                href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=mollie_payments&vp2=completed&bo=Y&deb_cred_id=<?= $mollyPaymentsParamsArray['debCredId']; ?>"
                class="btn btn-secondary <?php if( $vp2 === 'completed' ) { echo "active"; } ?>"
        >
            Completed
        </a>

        <a
                href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=mollie_payments&vp2=new&bo=Y&deb_cred_id=<?= $mollyPaymentsParamsArray['debCredId']; ?>"
                class="btn btn-secondary <?php if( $vp2 === 'new' ) { echo "active"; } ?>">
            Uncompleted
        </a>

        <a
                href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=mollie_payments&vp2=last&bo=Y&deb_cred_id=<?= $mollyPaymentsParamsArray['debCredId']; ?>"
                class="btn btn-secondary <?php if( $vp2 === 'last' ) { echo "active"; } ?>"
        >
            Last
        </a>

        <a
                href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=mollie_payments&vp2=customer&bo=Y&deb_cred_id="
                class="btn btn-secondary <?php if( $vp2 === 'customer' || $vp2 === 'details' ) { echo "active"; } ?>"
        >
            Customers
        </a>

    </div>
    <?php

//    exit;

    if($vp2 === 'last' || $vp2 === 'completed' || $vp2 === 'new'):
        ?>
        <div class="card cs-card">
            <div class="card-body cs-card-body p-0 mt-3 mb-3">

                <table id="elvy_relations" class="table table-striped table-hover table-sm mb-0 <?= $oUtil->getThemeClass('table_responsive') ?>">
                    <thead class="<?= $oUtil->getThemeClass('thead') ?>">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>relation name</th>
                        <th>payment status</th>
                        <th>created</th>
                        <th>completed</th>
                        <th>days</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php
                    $row_nr = 1;

                    if(isset($q_res)):
                        foreach ($q_res as $value_):
                            ?>
                            <tr>
                                <td>
                                    <?php
                                    echo $row_nr;
                                    $row_nr++;
                                    ?>
                                </td>
                                <td><?php get_table_data('tbl_provider_payments', "provider_payment_id," . $value_['provider_payment_id'], 'build_link',
                                                         array('set_short_link'=>'Y')); ?>
                                </td>
                                <td><?= $value_['relation_name']; ?></td>
                                <td><?php
//                                        echo $value_['payment_status']

                                    $oMolliePaymentsListUtility->setPaymentStatusIcon($value_['payment_status']);

                                    echo $oMolliePaymentsListUtility->getPaymentStatusIcon();

//                                        if($value_['payment_status'] === 'paid'):
                                            ?>
<!--                                                <i class="fas fa-check"-->
<!--                                                   style="color:#32cd32;  text-align: center;-->
<!--                width: 100%;"></i>-->
                                        <?php
//                                            elseif($value_['payment_status'] === 'expired'):
                                        ?>
<!--                                                <i class="fas fa-calendar-times"-->
<!--                                                    style="color:#808080;  text-align: center;-->
<!--                width: 100%;"></i>-->
                                        <?php
//                                        else:
                                            ?>
<!--                                            <i class="fas fa-times"-->
<!--                                               style="color:#ff6e4a;  text-align: center;-->
<!--                width: 100%;"></i>-->
                                            <?php
//                                        endif;
                                ?> </i></td>
                                <td><?= $value_['date_created']; ?></td>
                                <td><?php
                                    // check date and set correct syntax
//                                    $value_['date_completed'];
                                    if($value_['date_completed'] === '0000-00-00 00:00'):
                                        echo '-';
                                    else:
                                        echo $value_['date_completed'];
                                    endif;
                                ?></td>
                                <td class="cs-amount"><?= $value_['days_ago']; ?></td>
                            </tr>

                        <?php
                        endforeach;
                    endif;
                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    <?php
    endif;

    if($vp2 === 'customer'):
        ?>
        <div class="card cs-card">
            <div class="card-body cs-card-body p-0 mt-3 mb-3">
                <table id="elvy_relations" class="table table-striped table-hover table-sm mb-0 <?= $oUtil->getThemeClass('table_responsive') ?>">
                    <thead class="<?= $oUtil->getThemeClass('thead') ?>">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>relation name</th>
                        <th>total</th>
                        <th>completed</th>
                        <th>%</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $row_nr = 1;
                    $total_payment_status_completed = 0;
                    $total_payment_status_all = 0;

                    if(isset($q_res)):

                        foreach ($q_res as $value_):

                        // use childRelId to get correct count of completed
                            $oMolliePaymentsListUtility->setChildRelid($value_['relation_id'])->setSelSubQueryPaymentStatus('paid')->setTotalPaymentRows();

                            //count all completed in total row completed
                            $set = $oMolliePaymentsListUtility->getTotalPaymentsRows();
                            $total_payment_status_completed += $set;

                            //count all completed in total row uncompleted
                            $total_payment_status_all += $value_['total_rows'];
                            ?>
                            <tr class="cs-row-link">
                                <td>
                                    <?php
                                    echo $row_nr;
                                    $row_nr++;
                                    ?>
                                </td>
                                <td class="cs-nowrap">
                                    <a class="cs-link-list"
                                       href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=<?= $vp; ?>&vp2=last&deb_cred_id=<?= $value_['relation_id']; ?>&bo=Y">
                                        <?= set_link_icon(); ?>
                                    </a>

                                    <input name="product_id[<?= $rnr; ?>]"
                                           type="hidden"
                                           id="product_id"
                                           value="<?= $value_['provider_payment_id']; ?>">
                                </td>

                                <td ><?= $value_['relation_name']; ?></td>
                                <td class="cs-amount"><?= $value_['total_rows']; ?></td>

                                <td class="cs-amount"><?= $oMolliePaymentsListUtility->getTotalPaymentsRows(); ?></td>
                                <td class="cs-amount"><?= round($oMolliePaymentsListUtility->getTotalPaymentsRows()/$value_['date_created'], 2) * 100 . ' %'; ?></td>
                            </tr>

                        <?php
                        endforeach;
                    endif;
                    ?>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Totals</th>
                        <th class="cs-amount"><?= $total_payment_status_all; ?></th>
                        <th class="cs-amount"><?= $total_payment_status_completed; ?></th>
                        <th class="cs-amount"><?= round($total_payment_status_completed / $total_payment_status_all, 2) * 100 . ' %';  ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php
    endif;
else:

//    echo "ADMINISTRATION VIEW <BR>";
    echo "<BR><BR>";
    ?>

    <div class="btn-group" role="group" aria-label="Basic example">
        <a
                href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=mollie_payments&vp2=completed&deb_cred_id=<?= $mollyPaymentsParamsArray['debCredId']; ?>"
                class="btn btn-secondary <?php if( $vp2 === 'completed' ) { echo "active"; } ?>"
        >
            Completed
        </a>

        <a
                href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=mollie_payments&vp2=new&deb_cred_id=<?= $mollyPaymentsParamsArray['debCredId']; ?>"
                class="btn btn-secondary <?php if( $vp2 === 'new' ) { echo "active"; } ?>"
        >
            Uncompleted
        </a>

        <a
                href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=mollie_payments&vp2=last&deb_cred_id=<?= $mollyPaymentsParamsArray['debCredId']; ?>"
                class="btn btn-secondary <?php if( $vp2 === 'last' ) { echo "active"; } ?>"
        >
            Last
        </a>

        <a
                href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=mollie_payments&vp2=customer"
                class="btn btn-secondary <?php if( $vp2 === 'customer' || $vp2 === 'details' ) { echo "active"; } ?>"
        >
            Customers
        </a>

    </div>
    <?php

    if($vp2 === 'last' || $vp2 === 'completed' || $vp2 === 'new'):
        ?>
        <div class="card cs-card">
            <div class="card-body cs-card-body p-0 mt-3 mb-3">

                <table  class="table table-striped table-hover table-sm mb-0 <?= $oUtil->getThemeClass('table_responsive') ?>">
                    <thead class="<?= $oUtil->getThemeClass('thead') ?>">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>relation / invoice</th>
                        <th>amount</th>
                        <th>payment status</th>
                        <th>created</th>
                        <th>completed</th>
                        <th>days</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php
                    $row_nr = 1;

                    if(isset($q_res)):
                        foreach ($q_res as $value_):

                            $oMolliePaymentsListUtility->setSelSingleRowOfAllRelPayments($value_['provider_payment_id']);

                            $selRowAdminPayments = $oMolliePaymentsListUtility->getSelSingleRowOfAllRelPayments();

                            // only preview payment status 'new' and 'paid'
                            if($value_['payment_status'] === 'new'  || $value_['payment_status'] === 'paid'):
                            ?>
                            <tr>
                                <td>
                                    <?php
                                    echo $row_nr;
                                    $row_nr++;
                                    ?>
                                </td>
                                <td class="cs-nowrap">
                                    <a class="cs-link-list"
                                       href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=<?= $vp; ?>&vp2=completed&deb_cred_id=<?php foreach($selRowAdminPayments as $val_2_){ echo $val_2_['deb_cred_id'];} ?>">
                                        <?= set_link_icon(); ?>
                                    </a>
                                </td>
                                <td>
                                    <?php
                                    $oMolliePaymentsListUtility->setBooleanDebCredId('false')->checkBooleanDebCredId();

                                    //use 'false' for deb_cred_id -> relation_name
                                    // else use true to set debcollector name
                                    //check if there is a debCredId  specified then show Debt Claimer name
                                        if($oMolliePaymentsListUtility->getqWhereProviderPaymentsDebCredid() == 'true'):
                                            echo $value_['relation_name'];
                                        endif;

                                    foreach( $selRowAdminPayments as $value_2_):

                                        //check if no debCredId specified show all names of debtors
                                        if($oMolliePaymentsListUtility->getqWhereProviderPaymentsDebCredid() == 'false'):
                                            echo $value_2_['relation_name'] . ' : ' .'<br> ' ;
                                        endif;

                                        $oMolliePaymentsListUtility->setInvoiceId($value_2_['invoice_id'])->setSelStatusPaidByInvoiceId();
                                        $paidStat = $oMolliePaymentsListUtility->getSelStatusPaidByInvoiceId(); // previews: 1 if paid

                                        echo $value_2_['custom_invoice_id'] . ', date: ' . $value_2_['calc_date'] . '<br>debt €: ' . $value_['amount_payment']
//                                       .  ' paid_status by invoiceId :'  . $oMolliePaymentsListUtility->checkStatusPaidByInvoiceId()->getCheckStatusPaidByInvoiceId() . '<br>'
                                    ;
                                endforeach;
                                    ?>

                                <td class="cs-amount"><?=$value_['amount_payment'];?></td>
<!--                                <td>--><?//= $value_['payment_status']; ?><!--</td>-->
                                <td><?php
//                                    echo $value_['payment_status'];

                                    if($value_['payment_status'] === 'paid'):
                                        ?>
                                        <i class="fas fa-check"
                                           style="color:#32cd32;  text-align: center;
                width: 100%;"></i>
                                    <?php
                                    elseif($value_['payment_status'] === 'new'):
                                        ?>
                                        <i class="fas fa-times"
                                           style="color:#ff6e4a;  text-align: center;
                width: 100%;"></i>
                                    <?php
                                    else:
                                        ?>
                                        <i class="fas fa-calendar-times"
                                           style="color:#808080;  text-align: center;
                width: 100%;"></i>
                                    <?php
                                    endif;
                                    ?> </i></td>
                                <td><?= $value_['date_created']; ?></td>
                                <td><?= $value_['date_completed']; ?></td>
                                <td class="cs-amount"><?= $value_['days_ago']; ?></td>
                            </tr>

                        <?php
                            endif; // check if payments status is paid or new before loading data
                        endforeach;
                    endif;
                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    <?php
    endif;

    if($vp2 === 'customer'):
        ?>
        <div class="card cs-card">
            <div class="card-body cs-card-body p-0 mt-3 mb-3">
                <table id="elvy_relations" class="table table-striped table-hover table-sm mb-0 <?= $oUtil->getThemeClass('table_responsive') ?>">
                    <thead class="<?= $oUtil->getThemeClass('thead') ?>">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>relation name</th>
                        <th>total</th>
                        <th>completed</th>
                        <th>%</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $row_nr = 1;
                    $total_payment_status_completed = 0;
                    $total_payment_status_all = 0;

                    if(isset($q_res)):

                        foreach ($q_res as $value_):

                            // here short query counts total paid deb_cred_id
                        $oMolliePaymentsListUtility->setSelDebCredId($value_['deb_cred_id'])->setSelSubQueryPaymentStatus('paid')->setTotalPaymentRows();

                            //count all completed in total row completed
                            $set = $oMolliePaymentsListUtility->getTotalPaymentsRows();
                            $total_payment_status_completed += $set;

                            //count all completed in total row uncompleted
                            $total_payment_status_all += $value_['total_rows'];


                            ?>
                            <tr class="cs-row-link">
                                <td>
                                    <?php
                                    echo $row_nr;
                                    $row_nr++;
                                    ?>
                                </td>
                                <td class="cs-nowrap">
                                    <a class="cs-link-list"
                                       href="<?= _CS_ROOT_PATH_; ?>/bo/bo_reports/index.php?vp=<?= $vp; ?>&vp2=last&deb_cred_id=<?= $value_['deb_cred_id']; ?>">
                                        <?= set_link_icon(); ?>
                                    </a>
                                </td>

                                <td ><?= $value_['relation_name']; ?></td>
                                <td class="cs-amount"><?= $value_['total_rows']; ?></td>
                                <td class="cs-amount"><?= $oMolliePaymentsListUtility->getTotalPaymentsRows(); ?></td>
                                <td class="cs-amount"><?= round($oMolliePaymentsListUtility->getTotalPaymentsRows()/$value_['total_dates_created'], 2) * 100 . ' %'; ?></td>
                            </tr>
                        <?php
                        endforeach;

                    endif;
                    ?>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Totals</th>
                        <th class="cs-amount"><?= $total_payment_status_all; ?></th>
                        <th class="cs-amount"><?= $total_payment_status_completed; ?></th>
                        <th class="cs-amount"><?= round($total_payment_status_completed / $total_payment_status_all, 2) * 100 . ' %';  ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php
    endif;

endif;

?>
