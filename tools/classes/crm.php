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
* get name, id, company by tag *
*******************************/
    public function getContactsbyTag($tag)
    {

        $array = array(
            "tag" => $tag,
        );

        $contacts = $this->insightly->getContacts($array);

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

/***********************************
* get name, id, company by project *
***********************************/
    public function getContactsbyProject($projectId)
    {

        $proj = $this->insightly->getProject($projectId);

        $ret=array();

        foreach ($proj->LINKS as $l) {
            array_push($ret, $l->CONTACT_ID);
        }

     return $ret;

    }

}
