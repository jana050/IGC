<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartAuthHelper;
// site helpers
use Site\Helpers\NetworkHelper;
use Site\Helpers\ACVShutDownHelper;
use Site\Helpers\ElectricalShutDownHelper;
use Site\Helpers\UserHelper;
use Site\Helpers\DocumentHelper;
use Site\Helpers\ElectricalHelper;
use Site\Helpers\MeetRoomHelper;
use Site\Helpers\TelephoneHelper;
use Site\Helpers\MechanicalHelper;
use Site\Helpers\DocTypeHelper;
use Site\Helpers\WorkshopHelper;
use Site\Helpers\UserRoleHelper;
use Site\Helpers\CommanComplaintHelper;
use Site\Helpers\MbookIssueHelper;
use Site\Helpers\MbookEntryHelper;
use Site\Helpers\TemporaryAdvanceHelper;
use Site\Helpers\RadiologicalWorkHelper;
use Site\Helpers\WorkPermitHelper;
use Site\Helpers\RfidCardHelper;
use Site\Helpers\GemDirectHelper;
use Site\Helpers\GemDirectPaymentHelper;
//DashboardController

class DashboardController extends BaseController
{

    private UserHelper $_user_helper;
    private DocumentHelper $_doc_helper;
    private ElectricalHelper $_electrical_helper;
    private MeetRoomHelper $_meetroom_helper;
    private TelephoneHelper $_telephone_helper;
    private NetworkHelper $_network_helper;
    private MechanicalHelper $_mechanical_helper;
    private DocTypeHelper $_doc_type_helper;
    private WorkshopHelper $_workshop_helper;
    private ACVShutDownHelper $_acv_shutdown_helper;
    private ElectricalShutDownHelper $_elec_shutdown_helper;
    private UserRoleHelper $_user_role_helper;
    private CommanComplaintHelper $_common_complaint_helper;
    private MbookIssueHelper $_mbookIssue_helper;
    private MbookEntryHelper $_mbookEntry_helper;
    private TemporaryAdvanceHelper $_temporaryAdvance_helper;
    private RadiologicalWorkHelper $_radiologicalWork_helper;
    private WorkPermitHelper $_workPermit_helper;
    private RfidCardHelper $_rfidCard_helper;
    private GemDirectHelper $_gem_direct_helper;
    private GemDirectPaymentHelper $_gem_direct_payment_helper;
    //


    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_user_helper = new UserHelper($this->db);
        $this->_doc_helper = new DocumentHelper($this->db);
        $this->_electrical_helper = new ElectricalHelper($this->db);
        $this->_meetroom_helper = new MeetRoomHelper($this->db);
        $this->_telephone_helper = new TelephoneHelper($this->db);
        $this->_network_helper = new NetworkHelper($this->db);
        $this->_mechanical_helper = new MechanicalHelper($this->db);
        $this->_doc_type_helper = new DocTypeHelper($this->db);
        $this->_workshop_helper = new WorkshopHelper($this->db);
        $this->_acv_shutdown_helper = new ACVShutDownHelper($this->db);
        $this->_elec_shutdown_helper = new ElectricalShutDownHelper($this->db);
        $this->_user_role_helper = new UserRoleHelper($this->db);
        $this->_common_complaint_helper = new CommanComplaintHelper($this->db);
        $this->_mbookIssue_helper = new MbookIssueHelper($this->db);
        $this->_mbookEntry_helper = new MbookEntryHelper($this->db);
        $this->_temporaryAdvance_helper = new TemporaryAdvanceHelper($this->db);
        $this->_radiologicalWork_helper = new RadiologicalWorkHelper($this->db);
        $this->_workPermit_helper = new WorkPermitHelper($this->db);
        $this->_rfidCard_helper = new RfidCardHelper($this->db);
        $this->_gem_direct_helper = new GemDirectHelper($this->db);
        $this->_gem_direct_payment_helper = new GemDirectPaymentHelper($this->db);
        //$this->_user_role_helper = new UserRoleHelper($this->db);
    }

    public function getShutDownMsg()
    {
        $electrical_data = $this->_acv_shutdown_helper->scrollingData();
        $ac_shown_data = $this->_elec_shutdown_helper->scrollingData();
        //var_dump($electrical_data);
        // var_dump($ac_shown_data);
        $output = array_merge($electrical_data, $ac_shown_data);
        $this->response($output);
    }

    private function get_common_complaints_dashboard()
    {
        $id = SmartAuthHelper::getLoggedInId();
        $roles = $this->_user_role_helper->getSelectedRolesWithUserId($id);
        // var_dump($roles);
        if ($roles && count($roles) > 0) {
            $out_roles = [];
            foreach ($roles as $obj) {
                $out_roles[] = $obj->value;
            }
            $data = $this->_common_complaint_helper->getDashBoard($out_roles);
            return $data;
        } else {
            return [];
        }
    }
    /**
     * 
     */
    public function getCounts()
    {
        $db = new \stdClass();
        $db->user = $this->_user_helper->getLoginCount($this->params["type"]);
        $db->docs =  $this->_doc_helper->getCount($this->params["type"]);
        $db->electrical = $this->_electrical_helper->getCount($this->params["type"]);
        $db->meetcount = $this->_meetroom_helper->getCount($this->params["type"]);
        $db->telephone = $this->_telephone_helper->getCount($this->params["type"]);
        $db->network = $this->_network_helper->getCount($this->params["type"]);
        $db->mechanical = $this->_mechanical_helper->getCount($this->params["type"]);
        $db->workshop = $this->_workshop_helper->getCount($this->params["type"]);
        $db->acvShutDown = $this->_acv_shutdown_helper->getCount($this->params["type"]);
        $db->elecShutDown = $this->_elec_shutdown_helper->getCount($this->params["type"]);
        //
        $db->mbook_issue = $this->_mbookIssue_helper->getCount($this->params["type"]);
         $db->mbook_entry = $this->_mbookEntry_helper->getCount($this->params["type"]);
        $db->temporary_advance = $this->_temporaryAdvance_helper->getCount($this->params["type"]);
        $db->radiologicalWork = $this->_radiologicalWork_helper->getCount($this->params["type"]);
        $db->workPermit = $this->_workPermit_helper->getCount($this->params["type"]);
        //
        $db->rfidCard = $this->_rfidCard_helper->getCount($this->params["type"]);
        //added line for expiry count
        $db->rfidcard_expirycount = $this->_rfidCard_helper->getExpiredUnreturnedCards();
       //
        $db->docsByStatus =  $this->_doc_helper->getCountByStatus();
        $db->electricalByStatus = $this->_electrical_helper->getCountByStatus();
        $db->meetcountByStatus = $this->_meetroom_helper->getCountByStatus();
        $db->telephoneByStatus = $this->_telephone_helper->getCountByStatus();
        $db->networkByStatus = $this->_network_helper->getCountByStatus();
        $db->mechanicalByStatus = $this->_mechanical_helper->getCountByStatus();
        $db->workshopByStatus = $this->_workshop_helper->getCountByStatus();
        $db->acvShutDownByStatus = $this->_acv_shutdown_helper->getCountByStatus();
        $db->elecShutDownByStatus = $this->_elec_shutdown_helper->getCountByStatus();
        $db->mbookIssueByStatus = $this->_mbookIssue_helper->getCountByStatus();
        $db->mbookEntryByStatus = $this->_mbookEntry_helper->getCountByStatus();
        $db->temporaryAdvanceByStatus = $this->_temporaryAdvance_helper->getCountByStatus();
        //
        $db->rfidCardByStatus = $this->_rfidCard_helper->getCountByStatus();

        // head of issue count
        $db->mbook_entry_count_hos = $this->_mbookEntry_helper->getCountStatus("10");
        $db->mbook_entry_count_hod = $this->_mbookEntry_helper->getCountStatus("15");
        $db->mbook_entry_count_ad = $this->_mbookEntry_helper->getCountStatus("20");
        $db->mbook_entry_count_gd = $this->_mbookEntry_helper->getCountStatus("25");
        $db->mbook_issue_count_supervisor = $this->_mbookIssue_helper->getCountStatus("10");

        //temporary advance count 
        $db->temporary_advance_count_supervisor = $this->_temporaryAdvance_helper->getCountStatus("5");
        $db->temporary_advance_count_hos = $this->_temporaryAdvance_helper->getCountStatus("10");
        $db->temporary_advance_count_hod = $this->_temporaryAdvance_helper->getCountStatus("15");
        $db->temporary_advance_count_ad = $this->_temporaryAdvance_helper->getCountStatus("20");
        $db->temporary_advance_count_gd = $this->_temporaryAdvance_helper->getCountStatus("25");
        //
        $db->radiologicalWork_count_labincharge = $this->_radiologicalWork_helper->getCountStatus("10");
        $db->radiologicalWork_count_hp = $this->_radiologicalWork_helper->getCountStatus("15");
        //
        $db->workPermit_count_hos = $this->_workPermit_helper->getCountStatus("5");

        // GEM Direct pending counts by role (HOS, IIBCC Chairman, Vetter, etc).
        // Consumed by the approver dashboard cards.
        $gem_direct_pending = $this->_gem_direct_helper->getPendingCounts(
            SmartAuthHelper::getLoggedInId()
        );
        $db->gem_direct_hos_pending      = $gem_direct_pending->hos_pending ?? 0;
        $db->gem_direct_chairman_pending = $gem_direct_pending->chairman_pending ?? 0;
        $db->gem_direct_vetter_pending   = $gem_direct_pending->vetter_pending ?? 0;
        $db->gem_direct_user_pending     = $gem_direct_pending->user_pending ?? 0;
        $db->gem_direct_payment_pending  = $gem_direct_pending->payment_pending ?? 0;
        $db->gem_direct_payment_hos_pending = $this->_gem_direct_payment_helper->getHosPendingCount();

        // custom complaint types dashboard
        $db->custom = $this->get_common_complaints_dashboard();

        $this->response($db);
    }

    private function getTypeChart($year)
    {
        $sql = "show_chart=1";
        $data =  $this->_doc_type_helper->getAllData($sql);
        $label_data = [];
        //  var_dump($data);
        foreach ($data as $obj) {
            $db = new \stdClass();
            $db->label = $obj->doc_type;
            $db->data = $this->_doc_helper->getTypeCount($year, $obj->ID);
            $label_data[] = $db;
        }
        return $label_data;
    }
    /**
     * 
     */
    public function getCountsByYear()
    {
        $db = new \stdClass();
        // dashboard cards
        $db->docs =  $this->_doc_helper->getCountByYear($this->params["year"]);
        $db->electrical = $this->_electrical_helper->getCountByYear($this->params["year"]);
        $db->meetcount = $this->_meetroom_helper->getCountByYear($this->params["year"]);
        $db->telephone = $this->_telephone_helper->getCountByYear($this->params["year"]);
        $db->network = $this->_network_helper->getCountByYear($this->params["year"]);
        $db->mechanical = $this->_mechanical_helper->getCountByYear($this->params["year"]);
        $db->workshop = $this->_workshop_helper->getCountByYear($this->params["year"]);
        $db->acvShutDown = $this->_acv_shutdown_helper->getCountByYear($this->params["year"]);
        $db->elecShutDown = $this->_elec_shutdown_helper->getCountByYear($this->params["year"]);
        //
        $db->mbook_entry = $this->_mbookEntry_helper->getCountByYear($this->params["year"]);
        $db->mbook_issue = $this->_mbookIssue_helper->getCountByYear($this->params["year"]);
        $db->temporaryAdvance = $this->_temporaryAdvance_helper->getCountByYear($this->params["year"]);
        $db->radiologicalWork = $this->_radiologicalWork_helper->getCountByYear($this->params["year"]);
        $db->workPermit = $this->_workPermit_helper->getCountByYear($this->params["year"]);
        //
        $db->rfidCard = $this->_rfidCard_helper->getCountByYear($this->params["year"]);
        // publication chart
        $db->reportCount = $this->_doc_helper->reportCount($this->params["year"]);
        $db->journalCount = $this->_doc_helper->journalCount($this->params["year"]);
        $db->conferenceCount = $this->_doc_helper->conferenceCount($this->params["year"]);

        $db->type_data = $this->getTypeChart($this->params["year"]);


        $this->response($db);
    }
}
