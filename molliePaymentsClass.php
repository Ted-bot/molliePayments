<?php

namespace Claystone\Dev\DevTeddy;

class MolliePaymentsListUtility extends \Claystone\Util\UtilityBaseController
{
    protected $childRelId = '';
    protected $invoiceId = '';
    protected $selMainQuery = '';
    protected $selPaymentsByDebCredId = '';
    protected $selStatusPaidByInvoiceId = [];
    protected $checkStatusPaidByInvoiceId = [];
    protected $selSingleRowOfAllRelPayments = [];
    protected $listName = '';
    protected $list = [];
    protected $qWhereProviderPaymentsDebCredId = '';
    protected $qWhereProviderPaymentsRelationId = '';
    protected $qWhereRelationsParentRelationId = '';
    protected $qWhereRelationsRelationId = '';
    protected $selDebCredId = '';
    protected $selMainQueryPaymentStatus = '';
    protected $selSubQueryPaymentStatus = '';
    protected $totalPaymentsRows = '';
    protected $checkMainQueryWhereDebCredid = '';
    protected $selMainQueryOrderBy = '';
    protected $selMainQueryGroupBy = '';
    protected $selColumnsMainQuery = '';
    protected $subQueryRelId = '';
    protected $subQuery = '';
    protected $paymentStatusIcon = "";

    /**
     * Use only for Back Offcie reports!!
     * prepare main query
     * @return $this
     */
    public function buildQueryBackOffice(): MolliePaymentsListUtility
    {
        if ($this->listName === 'completed'):

            $this->selColumnsMainQuery = ",
                    tbl_provider_payments.deb_cred_id,
                   (date_format(tbl_provider_payments.date_created,'%Y-%m-%d %H:%i')) AS date_created,
                   (date_format(tbl_provider_payments.date_status,'%Y-%m-%d %H:%i')) AS date_completed,     
                   (TO_DAYS(tbl_provider_payments.date_status) - TO_DAYS(tbl_provider_payments.date_created)) AS days_ago";

            $this->qWhereProviderPaymentsDebCredId = "";

            // type on new row to prevent issue with main query syntax
            $this->selMainQueryPaymentStatus = "
            AND   tbl_provider_payments.payment_status = 'paid'";

            $this->selMainQueryGroupBy = "";

            // type on new row to prevent issue with main query syntax
            $this->selMainQueryOrderBy = "
            ORDER BY tbl_provider_payments.date_status DESC";

        elseif ($this->listName === 'last'):

            $this->selColumnsMainQuery = ",
                    tbl_provider_payments.deb_cred_id,
                   (date_format(tbl_provider_payments.date_created,'%Y-%m-%d %H:%i')) AS date_created,
                   (date_format(tbl_provider_payments.date_status,'%Y-%m-%d %H:%i')) AS date_completed,
                   (TO_DAYS(tbl_provider_payments.date_status) - TO_DAYS(tbl_provider_payments.date_created)) AS days_ago";

            // type on new row to prevent issue with main query syntax
            $this->selMainQueryPaymentStatus = "";

            $this->qWhereProviderPaymentsDebCredId = "";

            $this->selMainQueryGroupBy = "";
            // type on new row to prevent issue with main query syntax
            $this->selMainQueryOrderBy = "
            ORDER BY tbl_provider_payments.date_created DESC";


            elseif ($this->listName === 'new'):

            $this->selColumnsMainQuery = ",
                    tbl_provider_payments.deb_cred_id,
                   (date_format(tbl_provider_payments.date_created,'%Y-%m-%d %H:%i')) AS date_created,
                   (date_format(tbl_provider_payments.date_status,'%Y-%m-%d %H:%i')) AS date_completed,
                   (TO_DAYS(tbl_provider_payments.date_status) - TO_DAYS(tbl_provider_payments.date_created)) AS days_ago";

            // type on new row to prevent issue with main query syntax
            $this->selMainQueryPaymentStatus = "
            AND   tbl_provider_payments.payment_status = 'new'";

            $this->qWhereProviderPaymentsDebCredId = "";

            $this->selMainQueryGroupBy = "";
            // type on new row to prevent issue with main query syntax
            $this->selMainQueryOrderBy = "
            ORDER BY tbl_provider_payments.date_created DESC";

        elseif ($this->listName === 'customer'):

            $this->selColumnsMainQuery = ",
                    tbl_provider_payments.deb_cred_id,
                   COUNT(*) AS total_rows,
                   COUNT((date_format(tbl_provider_payments.date_created,'%Y-%m-%d %H:%i'))) AS date_created,
                   TO_DAYS(tbl_provider_payments.date_status) / TO_DAYS(tbl_provider_payments.date_created) AS margin_perc,
                    COUNT((date_format(tbl_provider_payments.date_status,'%Y-%m-%d %H:%i'))) AS date_completed,  
                   (TO_DAYS(tbl_provider_payments.date_status) - TO_DAYS(tbl_provider_payments.date_created)) AS days_ago";

            // type on new row to prevent issue with main query syntax
//            $this->selMainQueryPaymentStatus = "
//            AND   tbl_provider_payments.payment_status = 'new'";

            // show all rows
            $this->selMainQueryPaymentStatus = "
            AND   tbl_provider_payments.payment_status != 'expired'";

            $this->selMainQueryGroupBy = "
            GROUP BY tbl_provider_payments.relation_id";

            // type on new row to prevent issue with main query syntax
            $this->selMainQueryOrderBy = "
            ORDER BY tbl_provider_payments.date_created ASC";

        endif;

//        $this->setSelMainQuery();

        return $this;
    }

    /**
     * Use for administration data only
     * prepare main query
     * @return $this
     */
    public function buildQueryAdministration(): MolliePaymentsListUtility
    {
        if ($this->listName === 'completed'):

            // type on new row to prevent issue with query syntax
            $this->selMainQueryPaymentStatus = "
            AND   tbl_provider_payments.payment_status = 'paid'";

            $this->selMainQueryGroupBy = "";

            // type on new row to prevent issue with query syntax
            $this->selMainQueryOrderBy = "
            ORDER BY tbl_provider_payments.date_status DESC";

            $this->selColumnsMainQuery = ",
                    tbl_provider_payments.deb_cred_id,
                   tbl_provider_payments.amount_payment,
                   (date_format(tbl_provider_payments.date_created,'%Y-%m-%d %H:%i')) AS date_created,
                   (date_format(tbl_provider_payments.date_status,'%Y-%m-%d %H:%i')) AS date_completed,
                   (TO_DAYS(tbl_provider_payments.date_status) - TO_DAYS(tbl_provider_payments.date_created)) AS days_ago";

        elseif ($this->listName === 'last'):

            // type on new row to prevent issue with query syntax
            $this->selMainQueryPaymentStatus = "
            AND   tbl_provider_payments.payment_status != 'expired'";
        //  AND   tbl_provider_payments.payment_status = ''

            $this->selMainQueryGroupBy = "";

            // type on new row to prevent issue with query syntax
            $this->selMainQueryOrderBy = "
            ORDER BY tbl_provider_payments.date_created DESC";

            $this->selColumnsMainQuery = ",
                    tbl_provider_payments.deb_cred_id,
                   tbl_provider_payments.amount_payment,
                   tbl_provider_payments.payment_status,
                   (date_format(tbl_provider_payments.date_created,'%Y-%m-%d %H:%i')) AS date_created,
                   (date_format(tbl_provider_payments.date_status,'%Y-%m-%d %H:%i')) AS date_completed,
                   (TO_DAYS(tbl_provider_payments.date_status) - TO_DAYS(tbl_provider_payments.date_created)) AS days_ago";

            elseif ($this->listName === 'new'): //unpaid

            // type on new row to prevent issue with query syntax
            $this->selMainQueryPaymentStatus = "
            AND   tbl_provider_payments.payment_status = 'new'";

            // type on new row to prevent issue with query syntax
            $this->selMainQueryOrderBy = "
            ORDER BY tbl_provider_payments.date_created DESC";

            $this->selColumnsMainQuery = ",
                    tbl_provider_payments.deb_cred_id,
                   tbl_provider_payments.amount_payment,
                   (date_format(tbl_provider_payments.date_created,'%Y-%m-%d %H:%i')) AS date_created,
                   (date_format(tbl_provider_payments.date_status,'%Y-%m-%d %H:%i')) AS date_completed,
                   (TO_DAYS(tbl_provider_payments.date_status) - TO_DAYS(tbl_provider_payments.date_created)) AS days_ago";

        elseif ($this->listName === 'customer'):

            // type on new row to prevent issue with query syntax
            $this->selMainQueryPaymentStatus = "
            AND   tbl_provider_payments.payment_status != 'expired'";

            $this->selMainQueryGroupBy = "
            GROUP BY tbl_relations.relation_name";

            // type on new row to prevent issue with query syntax
            $this->selMainQueryOrderBy = "
            ORDER BY tbl_relations.relation_name ASC";

            $this->selColumnsMainQuery = ",
            TO_DAYS(tbl_provider_payments.date_status) / TO_DAYS(tbl_provider_payments.date_created) AS margin_perc,
                   (TO_DAYS(tbl_provider_payments.date_status) - TO_DAYS(tbl_provider_payments.date_created)) AS days_ago,
                   tbl_provider_payments.deb_cred_id,
                   COUNT((date_format(tbl_provider_payments.date_created,'%Y-%m-%d %H:%i'))) AS total_dates_created,
                    COUNT((date_format(tbl_provider_payments.date_status,'%Y-%m-%d %H:%i'))) AS date_completed,                        
                   COUNT(*) AS total_rows";

        endif;

        return $this;
    }

    /**
     *  this func creates a dynamic additional query depending on relid for the main Query
     * @param string $qWhereProviderPaymentsDebCredId
     * @return MolliePaymentsListUtility
     */
    public function setqWhereProviderPaymentsDebCredId(): MolliePaymentsListUtility
    {


        // check BO
        if($this->adminId === '229'):

                // BO view
                if($this->listName === 'customer'):

                        // customers view
                        $this->qWhereRelationsParentRelationId = "AND   tbl_relations.parent_relation_id = '229'";

                        $this->qWhereRelationsRelationId = "
              AND   tbl_relations.relation_id=tbl_provider_payments.relation_id";

                    $this->qWhereProviderPaymentsRelationId = "";

                    $this->qWhereProviderPaymentsDebCredId = "";

                else:
//                        if($this->childRelId):
                        if($this->selDebCredId): // see view 216 relid set as debCredId in param

                            $this->qWhereRelationsParentRelationId = "AND   tbl_relations.parent_relation_id = '229'";

                            $this->qWhereRelationsRelationId = "
                            AND     tbl_relations.relation_id = $this->selDebCredId";

                            $this->qWhereProviderPaymentsRelationId = "
                            AND     tbl_provider_payments.relation_id = tbl_relations.relation_id";


                            $this->qWhereProviderPaymentsDebCredId = "
                            ";

                        // AND     tbl_relations.relation_id=tbl_provider_payments.relation_id

                        else:

                            $this->qWhereRelationsParentRelationId = "AND   tbl_relations.parent_relation_id = '229'";

                            $this->qWhereRelationsRelationId = "";

                            $this->qWhereProviderPaymentsRelationId = "
                        AND     tbl_provider_payments.relation_id > 0
                        AND     tbl_provider_payments.relation_id = tbl_relations.relation_id";

                            $this->qWhereProviderPaymentsDebCredId = "";

                        endif;
                endif;
            
        else:

                // admin view
                if($this->listName === 'customer'):

                        // customer view
                        $this->qWhereRelationsParentRelationId = "AND tbl_relations.parent_relation_id='" . $this->oUtil->getAdminId() . "'";

                        $this->qWhereRelationsRelationId = "
                        AND     tbl_relations.relation_id=tbl_provider_payments.deb_cred_id";

                        $this->qWhereProviderPaymentsRelationId = "";

                        $this->qWhereProviderPaymentsDebCredId = "";

                else:
                        // completed or last view
                        if(empty($this->selDebCredId)):

                            $this->qWhereRelationsParentRelationId = "";

                            $this->qWhereRelationsRelationId = "AND tbl_relations.relation_id='" . $this->oUtil->getAdminId() . "'";

                            $this->qWhereProviderPaymentsRelationId = "
                            AND   tbl_provider_payments.relation_id=tbl_relations.relation_id";

                            $this->qWhereProviderPaymentsDebCredId = "";

                        else:

                            $this->qWhereRelationsParentRelationId = "";

                            $this->qWhereRelationsRelationId = "
                            AND tbl_relations.relation_id = '" . $this->oUtil->getAdminId() . "'";

                            $this->qWhereProviderPaymentsRelationId = "";

                            $this->qWhereProviderPaymentsDebCredId = "
                            AND tbl_provider_payments.deb_cred_id='" . $this->selDebCredId . "' ";

                        endif;

                endif;

        endif;

        return $this;
    }

    /**
     * @param string $selSingleRowOfAdministrationFromAllRelPayments
     * @return MolliePaymentsListUtility
     */
    public function setSelSingleRowOfAllRelPayments($provider_payment_id = ''): MolliePaymentsListUtility
    {
        $_sel = "
                SELECT	
                        tbl_provider_payments_rows.invoice_id,
                        tbl_provider_payments_rows.amount_open,
                        tbl_invoices.custom_invoice_id,
                        tbl_invoices.calc_date,
                        tbl_invoices.deb_cred_id,
                       tbl_relations.relation_name
                       
                FROM	tbl_provider_payments_rows,
                        tbl_invoices,
                        tbl_relations
                WHERE	tbl_provider_payments_rows.relation_id ='" . $this->oUtil->getAdminId() . "'
                AND     tbl_provider_payments_rows.provider_payment_id ='" . $provider_payment_id . "'
                AND     tbl_invoices.relation_id = tbl_provider_payments_rows.relation_id
                AND     tbl_invoices.invoice_id = tbl_provider_payments_rows.invoice_id
                AND     tbl_relations.parent_relation_id = tbl_invoices.relation_id
                AND     tbl_relations.relation_id = tbl_invoices.deb_cred_id
                        ";

        $this->selSingleRowOfAllRelPayments = $this->oDbConn->selectAll($_sel, $this->setPrint);

        return $this;
    }

    /**
     * get status paid by invoice_id
     * @param string $selStatusPaidByInvoiceId
     * @return MolliePaymentsListUtility
     */
    public function setSelStatusPaidByinvoiceId(): MolliePaymentsListUtility
    {

        $_sel = "SELECT tbl_provider_payments.payment_status
                FROM	tbl_provider_payments_rows,
                        tbl_provider_payments,
                        tbl_relations
                WHERE   tbl_relations.relation_id = '" . $this->oUtil->getAdminId() . "' 
                AND     tbl_provider_payments_rows.relation_id = tbl_relations.relation_id
                AND     tbl_provider_payments_rows.invoice_id ='" . $this->invoiceId . "' AND
                        tbl_provider_payments.provider_payment_id = tbl_provider_payments_rows.provider_payment_id
                         ";

        $this->selStatusPaidByInvoiceId = $this->oDbConn->selectRow($_sel, $this->setPrint);

        return $this;
    }

    /**
     * @param $selAdminOnlyAdministrationPaymentsByProviderPaymentId
     * @return MolliePaymentsListUtility
     */
    public function setTotalPaymentRows(): MolliePaymentsListUtility
    {
        if($this->adminId === '229'):

        $this->subQuery = "
                SELECT	count(*) AS total_rows
                FROM	tbl_provider_payments
                WHERE	tbl_provider_payments.relation_id = '" . $this->childRelId . "'
                AND     tbl_provider_payments.payment_status = '$this->selSubQueryPaymentStatus' ";

            $this->selPaymentsByDebCredId =  $this->oDbConn->selectRow($this->subQuery, $this->setPrint);

        else:

            $this->subQuery = "
                SELECT	count(*) AS total_rows
                FROM	tbl_provider_payments
                WHERE	tbl_provider_payments.deb_cred_id = '$this->selDebCredId'
                AND     tbl_provider_payments.payment_status = '$this->selSubQueryPaymentStatus' ";

              $this->selPaymentsByDebCredId = $this->oDbConn->selectRow($this->subQuery, $this->setPrint);

        endif;

        return $this;
    }

    /**
     * @param string $checkStatusPaidByInvoiceId
     * @return MolliePaymentsListUtility
     */
    public function checkStatusPaidByInvoiceId(): MolliePaymentsListUtility
    {
        $_sel = "
                SELECT 
                    CASE
                        WHEN payment_status = 'paid' THEN 'Yes is paid'
                        ELSE 'Not paid'
                    END AS paid_status
                FROM	tbl_provider_payments_rows,
                        tbl_provider_payments,
                        tbl_relations
                WHERE   tbl_relations.relation_id = '" . $this->oUtil->getAdminId() . "'
                AND     tbl_provider_payments_rows.relation_id = tbl_relations.relation_id
                AND	    tbl_provider_payments_rows.invoice_id ='" . $this->invoiceId . "'
                AND     tbl_provider_payments.provider_payment_id = tbl_provider_payments_rows.provider_payment_id";

        $this->checkStatusPaidByInvoiceId = $this->oDbConn->selectRow($_sel, $this->setPrint);

        return $this;
    }

    /**
     * @param $checkMainQueryWhereDebCredid
     * @return MolliePaymentsListUtility
     */
    public function checkBooleanDebCredId(): MolliePaymentsListUtility
    {
        // set correct relation name search in view (if no debCredId specified show all names of debtors)
        if(!empty($this->selDebCredId)):
            $this->setBooleanDebCredId('true');
        else:
            $this->setBooleanDebCredId('false');
        endif;

        return $this;
    }

    /**
     * @param $selMainQuery
     * @return MolliePaymentsListUtility
     */
    public function setSelMainQuery(): MolliePaymentsListUtility
    {

        $this->selMainQuery = "
            SELECT  tbl_provider_payments.provider_payment_id,
                   tbl_provider_payments.date_status,
                   tbl_provider_payments.payment_status,   
                   tbl_provider_payments.relation_id,
                   tbl_relations.relation_name" . $this->selColumnsMainQuery . "
            FROM    tbl_provider_payments,
                    tbl_relations
            WHERE   tbl_provider_payments.provider = 'mollie'
            " . $this->qWhereRelationsParentRelationId
            . $this->qWhereRelationsRelationId
            . $this->qWhereProviderPaymentsRelationId
            . $this->qWhereProviderPaymentsDebCredId
            . $this->selMainQueryPaymentStatus
            . $this->selMainQueryGroupBy
            . $this->selMainQueryOrderBy . "
            LIMIT 100 
            ";

//        echo $this->selMainQuery;
//        exit;

        return $this;
    }

    /**
     * @param $subQueryRelId
     * @return MolliePaymentsListUtility
     */
    public function setSubQueryRelId(): MolliePaymentsListUtility
    {

//        $_sel = "
        $this->subQuery = "
                SELECT	count(*) AS total_rows
                FROM	tbl_provider_payments,
                        tbl_relations
                WHERE	tbl_relations.parent_relation_id = '$this->subQueryRelId'
                AND     tbl_provider_payments.relation_id = tbl_relations.relation_id
                AND     tbl_provider_payments.payment_status = '$this->selSubQueryPaymentStatus' ";

        $this->selPaymentsByDebCredId = $this->oDbConn->selectRow($this->subQuery, $this->setPrint);

        return $this;
    }

    /**
     * @param $paymentStatusIcon
     * @return MolliePaymentsListUtility
     */
    public function setPaymentStatusIcon($paymentStatusIcon): MolliePaymentsListUtility
    {
        if($paymentStatusIcon === 'paid'):

            $this->paymentStatusIcon = <<<DELIMETER
            <i class="fas fa-check"
               style="color:#32cd32;  text-align: center;
               width: 100%;"></i>
DELIMETER;

        elseif($paymentStatusIcon === 'new'):

            $this->paymentStatusIcon = <<<DELIMETER
            <i class="fas fa-times"
               style="color:#ff6e4a;  text-align: center;
               width: 100%;"></i>
DELIMETER;

        else:

            $this->paymentStatusIcon = <<<DELIMETER
            <i class="fas fa-calendar-times"
               style="color:#808080;  text-align: center;
               width: 100%;"></i>
DELIMETER;

        endif;

        return $this;
    }

    /**
     *
     * --- this function is needed to create subQuery ---
     *
     * @param string $relid
     * @return MolliePaymentsListUtility
     */
    public function setChildRelid($rel_id): MolliePaymentsListUtility
    {
        $this->childRelId = $rel_id;

        return $this;
    }

    /**
     * get data with selected query from func buildQueryAdministration
     * @return $this
     */
    public function setList(): MolliePaymentsListUtility
    {
        $this->list = $this->oDbConn->selectAll($this->selMainQuery, $this->setPrint);

        return $this;
    }

    /**
     * set query type
     * @param string $listName
     * @return MolliePaymentsListUtility
     */
    public function setListName(string $vp2 = ''): MolliePaymentsListUtility
    {
        $this->listName = $vp2;
        return $this;
    }

    /**
     * @param string $invoiceId
     * @return MolliePaymentsListUtility
     */
    public function setInvoiceId(string $invoiceId = ''): MolliePaymentsListUtility
    {
        $this->invoiceId = $invoiceId;
        return $this;
    }

    /**
     * @param $selDebCredId
     * @return MolliePaymentsListUtility
     */
    public function setSelDebCredId($selDebCredId = ''): MolliePaymentsListUtility
    {
        $this->selDebCredId = $selDebCredId;
        return $this;
    }

    /**
     * @param string $selSubQueryPaymentStatus
     * @return MolliePaymentsListUtility
     */
    public function setSelSubQueryPaymentStatus(string $selSubQueryPaymentStatus = ''): MolliePaymentsListUtility
    {
        $this->selSubQueryPaymentStatus = $selSubQueryPaymentStatus;
        return $this;
    }

    /**
     * @param $checkMainQueryWhereDebCredid
     * @return MolliePaymentsListUtility
     */
    public function setBooleanDebCredId($boolean = 'false'): MolliePaymentsListUtility
    {
        $this->checkMainQueryWhereDebCredid = $boolean;
        return $this;
    }

    /**
     * @return string
     */
    public function getqWhereProviderPaymentsDebCredid(): string
    {
        return $this->checkMainQueryWhereDebCredid;
    }

    /**
     * get Total payments rows per admin from meth selAdminOnlyAdministrationPaymentsByCredId
     * @return string
     */
    public function getTotalPaymentsRows(): string
    {
        return $this->totalPaymentsRows = $this->selPaymentsByDebCredId['total_rows'];
    }

    /**
     * @return string
     */
    public function getCheckStatusPaidByinvoiceId(): string
    {
        return $this->checkStatusPaidByInvoiceId['paid_status'];
    }

    /**
     * get all records of admin with selected payment status
     * @return string
     */
    public function getSelAdministrationPayments(): string
    {
        return $this->selAdministrationPayments['total_rows'];
    }

    /**
     * get query results
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     * @return string
     */
    public function getSelSingleRowOfAllRelPayments()
    {
        return $this->selSingleRowOfAllRelPayments;
    }

    /**
     * @return bool
     */
    public function getSelStatusPaidByInvoiceId(): bool
    {
        return $this->selStatusPaidByInvoiceId['payment_status'];
    }

    /**
     * @return string
     */
    public function getPaymentStatusIcon(): string
    {
        return $this->paymentStatusIcon;
    }

}