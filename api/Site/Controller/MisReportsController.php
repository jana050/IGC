<?php

namespace Site\Controller;

use Core\BaseController;

use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartData;
use Site\Helpers\CommanComplaintHelper;
use Site\Helpers\ElectricalHelper;
use Site\Helpers\WorkshopHelper;
use Site\Helpers\MeetRoomHelper;
use Site\Helpers\TemporaryAdvanceHelper;
use Site\Helpers\MeetProposalHelper;
use Site\Helpers\DocumentHelper;
use Site\Helpers\TelephoneHelper;
use Site\Helpers\NetworkHelper;
use Site\Helpers\MbookEntryHelper;
use Site\Helpers\MbookIssueHelper;
use Site\Helpers\TableHelper;
use Site\Helpers\RfidCardHelper;
use Site\Helpers\CommiteeIibccHelper;
use Site\Helpers\CommiteeLpcHelper;
use Site\Helpers\CommiteeLpcSubHelper;
use Site\Helpers\GemDirectHelper;

class MisReportsController extends BaseController
{

    private CommanComplaintHelper $_commanComplaint_helper;
    private ElectricalHelper $_electrical_helper;
    private WorkshopHelper $_workshop_helper;
    private MeetRoomHelper $_meetRoom_helper;
    private TemporaryAdvanceHelper $_temporaryAdvance_helper;
    private MeetProposalHelper $_meetProposal_helper;
    private DocumentHelper $_document_helper;
    private TelephoneHelper $_telephone_helper;
    private NetworkHelper $_network_helper;
    private MbookEntryHelper $_mbookEntry_helper;
    private RfidCardHelper $_rfidCard_helper;
    private CommiteeIibccHelper $_commitee_iibcc_helper;
    private CommiteeLpcHelper $_commitee_lpc_helper;
    private CommiteeLpcSubHelper $_commitee_lpc_sub_helper;
    private MbookIssueHelper $_mbookIssue_helper;
    private GemDirectHelper $_gem_direct_helper;


    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_commanComplaint_helper = new CommanComplaintHelper($this->db);
        $this->_electrical_helper = new ElectricalHelper($this->db);
        $this->_workshop_helper = new WorkshopHelper($this->db);
        $this->_meetRoom_helper = new MeetRoomHelper($this->db);
        $this->_temporaryAdvance_helper = new TemporaryAdvanceHelper($this->db);
        $this->_meetProposal_helper = new MeetProposalHelper($this->db);
        $this->_document_helper = new DocumentHelper($this->db);
        $this->_telephone_helper = new TelephoneHelper($this->db);
        $this->_network_helper = new NetworkHelper($this->db);
        $this->_mbookEntry_helper = new MbookEntryHelper($this->db);
        $this->_rfidCard_helper = new RfidCardHelper($this->db);
        $this->_commitee_iibcc_helper = new CommiteeIibccHelper($this->db);
        $this->_commitee_lpc_helper = new CommiteeLpcHelper($this->db);
        $this->_commitee_lpc_sub_helper = new CommiteeLpcSubHelper($this->db);
        $this->_mbookIssue_helper = new MbookIssueHelper($this->db);
        $this->_gem_direct_helper = new GemDirectHelper($this->db);
    }


    /**
     * 
     */
    //Complaint REPORT
    public function getComplaintReport()
    {
        $start_date = SmartData::post_data("start_date", "DATE");
        $end_date = SmartData::post_data("end_date", "DATE");
        $subcategory = SmartData::post_select_value("subcategory");

        if (empty($start_date) || empty($end_date)) {
            \CustomErrorHandler::triggerInvalid("Start date and End date are required");
        }

        if (empty($subcategory)) {
            \CustomErrorHandler::triggerInvalid("Subcategory is required");
        }

        $ComplaintReport = $this->_commanComplaint_helper->ComplaintReport($start_date, $end_date, $subcategory);

        $this->response($ComplaintReport);
    }
    //Electrical Complaint REPORT
    public function getElectricalComplaintReport()
    {
        $start_date = SmartData::post_data("start_date", "DATE");
        $end_date = SmartData::post_data("end_date", "DATE");

        if (empty($start_date) || empty($end_date)) {
            \CustomErrorHandler::triggerInvalid("Start date and End date are required");
        }

        $ElectricalComplaintReport = $this->_electrical_helper->ElectricalComplaintReport($start_date, $end_date);
         // Loop through each record & add status label
        foreach ($ElectricalComplaintReport as &$item) {

        $currentStatus = $item->status;
        $createdBy     = $item->created_by ?? null;

        // Get status tracker to derive label
        $statusTracker = $this->_electrical_helper->getStatusTrackerNew($currentStatus, $createdBy);

        // We need only the label of CURRENT STATUS
        $currentLabel = '';
        foreach ($statusTracker['status_list'] as $row) {
            if ($row['is_current']) {
                $currentLabel = $row['label'];     // username for 10, HOS, HOD, etc.
                break;
            }
        }

        // Add new field
        $item->status_label = $currentLabel;
    }

        $this->response($ElectricalComplaintReport);
    }


    //Telephone Complaint REPORT
    public function getTelephoneComplaintReport()
    {
        $start_date = SmartData::post_data("start_date", "DATE");
        $end_date = SmartData::post_data("end_date", "DATE");

        if (empty($start_date) || empty($end_date)) {
            \CustomErrorHandler::triggerInvalid("Start date and End date are required");
        }

        $TelephoneComplaintReport = $this->_telephone_helper->TelephoneComplaintReport($start_date, $end_date);
           // Loop through each record & add status label
        foreach ($TelephoneComplaintReport as &$item) {

        $currentStatus = $item->status;
        $createdBy     = $item->created_by ?? null;

        // Get status tracker to derive label
        $statusTracker = $this->_telephone_helper->getStatusTrackerNew($currentStatus, $createdBy);

        // We need only the label of CURRENT STATUS
        $currentLabel = '';
        foreach ($statusTracker['status_list'] as $row) {
            if ($row['is_current']) {
                $currentLabel = $row['label'];     // username for 10, HOS, HOD, etc.
                break;
            }
        }

        // Add new field
        $item->status_label = $currentLabel;
    }

        $this->response($TelephoneComplaintReport);
    }
    //Network Complaint REPORT
    public function getNetworkComplaintReport()
    {
        $start_date = SmartData::post_data("start_date", "DATE");
        $end_date = SmartData::post_data("end_date", "DATE");

        if (empty($start_date) || empty($end_date)) {
            \CustomErrorHandler::triggerInvalid("Start date and End date are required");
        }

        $NetworkComplaintReport = $this->_network_helper->NetworkComplaintReport($start_date, $end_date);
         // Loop through each record & add status label
        foreach ($NetworkComplaintReport as &$item) {

        $currentStatus = $item->status;
        $createdBy     = $item->created_by ?? null;

        // Get status tracker to derive label
        $statusTracker = $this->_network_helper->getStatusTrackerNew($currentStatus, $createdBy);

        // We need only the label of CURRENT STATUS
        $currentLabel = '';
        foreach ($statusTracker['status_list'] as $row) {
            if ($row['is_current']) {
                $currentLabel = $row['label'];     // username for 10, HOS, HOD, etc.
                break;
            }
        }

        // Add new field
        $item->status_label = $currentLabel;
    }


        $this->response($NetworkComplaintReport);
    }
    //Requisition REPORT
    public function getRequisitionReport()
    {
        $start_date = SmartData::post_data("start_date", "DATE");
        $end_date = SmartData::post_data("end_date", "DATE");

        if (empty($start_date) || empty($end_date)) {
            \CustomErrorHandler::triggerInvalid("Start date and End date are required");
        }

        $RequisitionReport = $this->_workshop_helper->RequisitionReport($start_date, $end_date);

        $this->response($RequisitionReport);
    }
    //MeetingRoomReport REPORT
    public function getMeetingRoomReport()
    {
        $start_date = SmartData::post_data("start_date", "DATE");
        $end_date = SmartData::post_data("end_date", "DATE");
        $subcategory = SmartData::post_select_value("subcategory");

        if (empty($start_date) || empty($end_date)) {
            \CustomErrorHandler::triggerInvalid("Start date and End date are required");
        }

        if (empty($subcategory)) {
            \CustomErrorHandler::triggerInvalid("Subcategory is required");
        }

        $MeetingRoomReport = $this->_meetRoom_helper->MeetingRoomReport($start_date, $end_date, $subcategory);

        $this->response($MeetingRoomReport);
    }
    //TemporaryAdvance REPORT
    public function getTemporaryAdvanceReport()
   {
    $start_date = SmartData::post_data("start_date", "DATE");
    $end_date   = SmartData::post_data("end_date", "DATE");

    if (empty($start_date) || empty($end_date)) {
        \CustomErrorHandler::triggerInvalid("Start date and End date are required");
    }

    // Fetch report
    $TemporaryAdvanceReport = $this->_temporaryAdvance_helper->TemporaryAdvanceReport($start_date, $end_date);

    // Add status label to each row
    foreach ($TemporaryAdvanceReport as &$item) {

        $currentStatus = $item->status;
        $createdBy     = $item->created_by ?? null;

        // get label using tracker
        $statusTracker = $this->_temporaryAdvance_helper->getTAStatusTrackerNew($currentStatus, $createdBy);

        // extract CURRENT label only
        $label = '';
        foreach ($statusTracker['status_list'] as $row) {
            if ($row['is_current']) {
                $label = $row['label'];
                break;
            }
        }

        // Add new field into response
        $item->status_label = $label;
    }

    $this->response($TemporaryAdvanceReport);
    }

    //Committee MOM REPORT
     public function getCommitteeMomReport()
    {
        $start_date = SmartData::post_data("start_date", "DATE");
        $end_date = SmartData::post_data("end_date", "DATE");
        $subcategory = SmartData::post_select_string("subcategory");
        if (empty($start_date) || empty($end_date)) {
            \CustomErrorHandler::triggerInvalid("Start date and End date are required");
        }
        if (empty($subcategory)) {
            \CustomErrorHandler::triggerInvalid("Subcategory is required");
        }
        $CommitteeMomReport = $this->_meetProposal_helper->CommitteeMomReport($start_date, $end_date, $subcategory);
        $this->response($CommitteeMomReport);
    }
    //
    //Document REPORT
     public function getDocumentReport()
    {
        $start_date = SmartData::post_data("start_date", "DATE");
        $end_date = SmartData::post_data("end_date", "DATE");
        $subcategory = SmartData::post_select_value("subcategory");
        if (empty($start_date) || empty($end_date)) {
            \CustomErrorHandler::triggerInvalid("Start date and End date are required");
        }
        if (empty($subcategory)) {
            \CustomErrorHandler::triggerInvalid("Subcategory is required");
        }
        $DocumentReport = $this->_document_helper->DocumentReport($start_date, $end_date);
        $this->response($DocumentReport);
    }
  
    //Mbook Issue REPORT
    public function getMbookReport()
{
    $start_date  = SmartData::post_data("start_date", "DATE");
    $end_date    = SmartData::post_data("end_date", "DATE");
    $file_type   = SmartData::post_data("file_type", "STRING");
    $budget_type = SmartData::post_data("budget_type", "STRING");
    $technical_sanction_number = SmartData::post_data("technical_sanction_number", "INTEGER");
    $contact_name = SmartData::post_data("contact_name", "STRING");

    // Fetch report data
    $report = $this->_mbookEntry_helper->MbookEntryReport(
        $start_date,
        $end_date,
        $file_type,
        $budget_type,
        $technical_sanction_number,
        $contact_name
    );

    // Loop through each record & add status label
    foreach ($report as &$item) {

        $currentStatus = $item->entry_status;          // 🔥 use entry_status (not status)
        $createdBy     = $item->created_by ?? null;

        // Get status tracker
        $statusTracker = $this->_mbookEntry_helper->getTAStatusTrackerNew(
            $currentStatus,
            $createdBy
        );

        // Extract only current status label
        $currentLabel = '';
        foreach ($statusTracker['status_list'] as $row) {
            if ($row['is_current']) {
                $currentLabel = $row['label'];   // username for 10, HOS/HOD/AD/GD for others
                break;
            }
        }

        // Add to object
        $item->status_label = $currentLabel;
    }

    $this->response($report);
}

    

    //RFID CARD EXPIRY DATE
    public function getRFIDCardExpiryReport()
   {
    $to_date = SmartData::post_data("to_date", "DATE");

    if (empty($to_date)) {
        \CustomErrorHandler::triggerInvalid("to_date is required");
    }

    $rfidReport = $this->_rfidCard_helper->RfidCardExpiryReport($to_date);

    $this->response($rfidReport);
     }
     //RfidCardReport REPORT
   public function getRfidCardReport()
   {
    $from_date = SmartData::post_data("start_date", "DATE");
    $to_date = SmartData::post_data("end_date", "DATE");

    if (empty($from_date) || empty($to_date)) {
        \CustomErrorHandler::triggerInvalid("Start date and End date are required");
    }

    // Fetch report data
    $rfidcardReport = $this->_rfidCard_helper->RfidCardReport($from_date, $to_date);

    // Loop through each record & add status label
    foreach ($rfidcardReport as &$item) {

        $currentStatus = $item->status;
        $createdBy     = $item->created_by ?? null;

        // Get status tracker to derive label
        $statusTracker = $this->_rfidCard_helper->getStatusTrackerNew($currentStatus, $createdBy);

        // We need only the label of CURRENT STATUS
        $currentLabel = '';
        foreach ($statusTracker['status_list'] as $row) {
            if ($row['is_current']) {
                $currentLabel = $row['label'];     // username for 10, HOS, HOD, etc.
                break;
            }
        }

        // Add new field
        $item->status_label = $currentLabel;
    }

    $this->response($rfidcardReport);
    }
    //committee iibcc REPORT
      public function getCommitteeIibccReport()
    {
        $start_date = SmartData::post_data("start_date", "DATE");
        $end_date = SmartData::post_data("end_date", "DATE");
        $iibcc_no = SmartData::post_data("iibcc_no", "STRING");
        $head_of_account = SmartData::post_data("head_of_account", "STRING");
        $indent_no = SmartData::post_data("indent_no", "STRING");
        $estimate_source = SmartData::post_data("estimate_source", "STRING");
        $iibcc_no = SmartData::post_data("iibcc_no", "STRING");

        $item_to_purchased = SmartData::post_data("item_to_purchased", "STRING");
        $CommiteeIibccReport = $this->_commitee_iibcc_helper->CommiteeIibccReport($start_date, $end_date, $iibcc_no, $head_of_account, $indent_no, $estimate_source, $item_to_purchased);
         // Loop through each record & add status label
        foreach ($CommiteeIibccReport as &$item) {

        $currentStatus = $item->status;
        $createdBy     = $item->created_by ?? null;

        // Get status tracker to derive label
        $statusTracker = $this->_commitee_iibcc_helper->getStatusTrackerNew($currentStatus, $createdBy);

        // We need only the label of CURRENT STATUS
        $currentLabel = '';
        foreach ($statusTracker['status_list'] as $row) {
            if ($row['is_current']) {
                $currentLabel = $row['label'];     // username for 10, HOS, HOD, etc.
                break;
            }
        }

        // Add new field
        $item->status_label = $currentLabel;
    }

        $this->response($CommiteeIibccReport);
    }
    //
    //committee lpc REPORT
    /*
    public function getCommitteeLpcReport()
    {
        $start_date = SmartData::post_data("start_date", "DATE");
        $end_date = SmartData::post_data("end_date", "DATE");
        $head_of_account = SmartData::post_data("head_of_account", "STRING");
        $indent_no = SmartData::post_data("indent_no", "STRING");
        $item_category = SmartData::post_data("item_category", "STRING");


        $CommiteeLpcReport = $this->_commitee_lpc_helper->CommiteeLpcReport($start_date, $end_date, $head_of_account, $indent_no, $item_category);  
         // Loop through each record & add status label
    foreach ($CommiteeLpcReport as &$item) {

        $currentStatus = $item->status;
        $createdBy     = $item->created_by ?? null;

        // Get status tracker to derive label
        $statusTracker = $this->_commitee_lpc_helper->getStatusTrackerNew($currentStatus, $createdBy);

        // We need only the label of CURRENT STATUS
        $currentLabel = '';
        foreach ($statusTracker['status_list'] as $row) {
            if ($row['is_current']) {
                $currentLabel = $row['label'];     // username for 10, HOS, HOD, etc.
                break;
            }
        }

        // Add new field
        $item->status_label = $currentLabel;
    }              
        $this->response($CommiteeLpcReport);
    }
    */
    public function getCommitteeLpcReport()
   {
    $start_date      = SmartData::post_data("start_date", "DATE");
    $end_date        = SmartData::post_data("end_date", "DATE");
    $head_of_account = SmartData::post_data("head_of_account", "STRING");
    $indent_no       = SmartData::post_data("indent_no", "STRING");
    $item_category   = SmartData::post_data("item_category", "STRING");

    $CommiteeLpcReport = $this->_commitee_lpc_helper
        ->CommiteeLpcReport($start_date, $end_date, $head_of_account, $indent_no, $item_category);

    foreach ($CommiteeLpcReport as &$item) {

        /* ---------- STATUS LABEL ---------- */
        $currentStatus = $item->status;
        $createdBy     = $item->created_by ?? null;

        $statusTracker = $this->_commitee_lpc_helper
            ->getStatusTrackerNew($currentStatus, $createdBy);

        $currentLabel = '';
        foreach ($statusTracker['status_list'] as $row) {
            if ($row['is_current']) {
                $currentLabel = $row['label'];
                break;
            }
        }
        $item->status_label = $currentLabel;

        /* ---------- SUB DATA (ITEMS) ---------- */
        $committeeLpcId = $item->ID;   // main table ID

        $subItems = $this->_commitee_lpc_sub_helper
            ->getAllWithCommitteeLpcId($committeeLpcId);

        // attach sub data
        $item->items = $subItems ?? [];
    }

    $this->response($CommiteeLpcReport);
    }

    //
    //Mbook Issue REPORT
    public function getMbookIssueReport()
{
    $start_date  = SmartData::post_data("start_date", "DATE");
    $end_date    = SmartData::post_data("end_date", "DATE");
    $file_type   = SmartData::post_data("file_type", "STRING");
    $work_order_value = SmartData::post_data("work_order_value", "STRING");
    $head_of_account = SmartData::post_data("head_of_account", "STRING");

    // Fetch report data
    $report = $this->_mbookIssue_helper->MbookIssueReport(
        $start_date,
        $end_date,
        $file_type,
        $work_order_value,
        $head_of_account
    );

    // Loop through each record & add status label
    foreach ($report as &$item) {

        $currentStatus = $item->status;          // 🔥 use entry_status (not status)
        $createdBy     = $item->created_by ?? null;

        // Get status tracker
        $statusTracker = $this->_mbookIssue_helper->getTAStatusTrackerNew(
            $currentStatus,
            $createdBy
        );

        // Extract only current status label
        $currentLabel = '';
        foreach ($statusTracker['status_list'] as $row) {
            if ($row['is_current']) {
                $currentLabel = $row['label'];   // username for 10, HOS/HOD/AD/GD for others
                break;
            }
        }

        // Add to object
        $item->status_label = $currentLabel;
    }

    $this->response($report);
}

//gem direct report
public function getGemDirectReport()
{
    $start_date      = SmartData::post_data("start_date", "DATE");
    $end_date        = SmartData::post_data("end_date", "DATE");
    $indent_no       = SmartData::post_data("indent_no", "STRING");
    $head_of_account = SmartData::post_data("indent_no", "STRING");


    $report = $this->_gem_direct_helper->GemDirectReport(
        $start_date,
        $end_date,
        $indent_no,
        $head_of_account
    );

    // Add Status Label
    foreach ($report as &$item) {

        $currentStatus = $item->status;
        $createdBy     = $item->created_by ?? null;

        $statusTracker = $this->_gem_direct_helper
            ->getStatusTrackerNew($currentStatus, $createdBy);

        $currentLabel = '';
        foreach ($statusTracker['status_list'] as $row) {
            if ($row['is_current']) {
                $currentLabel = $row['label'];
                break;
            }
        }

        $item->status_label = $currentLabel;
    }

    $this->response($report);
}

}
