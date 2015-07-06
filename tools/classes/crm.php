<?php

/*************************************
 * Class crm
 * handles the Insightly stuff
 *************************************/
class crm
{
    private $insightly;

/******************
* Constructor     *
******************/
    public function __construct()
    {
        require_once("config/db.php");
        require("insightly.php");

        $this->insightly = new Insightly(INSIGHTLY_KEY);

    }


/*******************************
* get name from comp id        *
*******************************/
    public function getOrganisationfromId($id)
    {
#        $org = $this->insightly->getOrganizations(array("ids" => array('0=\'$id\'')));
        $org = $this->insightly->getOrganization($id);
        return $org->ORGANISATION_NAME;

#        var_dump($org);

        #$contacts = $i->getContacts(array("ids" => array('FIRST_NAME=\'Brian\'')));
    }

/*******************************
* get name, company by id      *
*******************************/
    public function getContactbyId($id)
    {
        $c = $this->insightly->getContact($id);

        $orgname="Unknown";

        if (is_numeric($c->DEFAULT_LINKED_ORGANISATION)) {
            $orgname = $this->getOrganisationfromId($c->DEFAULT_LINKED_ORGANISATION);
        }

        $array = array(
            "id" => $id,
            "name" => $c->FIRST_NAME . " " . $c->LAST_NAME,
            "organisation" => $orgname,
        );

        $cinfo=$c->CONTACTINFOS;

        foreach ($cinfo as $con) {
            if (strcasecmp($con->TYPE,"EMAIL")==0  && strcasecmp($con->LABEL,"WORK")==0) {
                $array["email"] = $con->DETAIL;
            }
        }

        return $array;

    }






/*******************************
* get name, id, company by tag *
*******************************/
    public function getContactsbyTag($tag)
    {

        $array = array(
            "tag" => $tag,
        );

        $contacts = $this->insightly->getContacts($array);

        return $this->parseContactList($contacts);

    }

/***********************************
* get name, id, company by project *
***********************************/
    public function getContactsbyProject($projectId)
    {

        $proj = $this->insightly->getProject($projectId);

        $ret=array();

        $ids=array();

// get all of the ids and call the API once - too slow otherwise
        foreach ($proj->LINKS as $l) {
            array_push($ids, $l->CONTACT_ID);
        }

// Get details for ids
        $contacts = $this->insightly->getContacts($array);

        return $this->parseContactList($contacts);

    }


    public function parseContactList($contacts)
    {

        $ret=array();

        foreach ($contacts as $c) {

            $orgname="Unknown";

            if (is_numeric($c->DEFAULT_LINKED_ORGANISATION)) {
                $orgname = $this->getOrganisationfromId($c->DEFAULT_LINKED_ORGANISATION);
            }

            $array = array(
                "id" => $c->CONTACT_ID,
                "name" => $c->FIRST_NAME . " " . $c->LAST_NAME,
                "organisation" => $orgname,
            );

            $cinfo=$c->CONTACTINFOS;
            foreach ($cinfo as $con) {
                if ($con->TYPE=="EMAIL"  && $con->LABEL=="WORK") {
                    $array["email"] = $con->DETAIL;
                }
            }

            array_push($ret, $array);

        }

        return $ret;

    }


    public function test() {

       $ids = array(124650965, 124650937);

       $array = array(
            "ids" => $ids,
        );

        $contacts = $this->insightly->getContacts($array);

        var_dump($contacts);

    }

}