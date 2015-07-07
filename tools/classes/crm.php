<?php

/*************************************
 * Class crm
 * handles the Insightly stuff
 * provides a higher level API than
 * Insightly gives us.
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
        $org = $this->insightly->getOrganization($id);
        return $org->ORGANISATION_NAME;

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

        $ids=array();

// get all of the ids and call the API once - too slow otherwise
        foreach ($proj->LINKS as $l) {
            array_push($ids, $l->CONTACT_ID);
        }

// Get details for ids
        $contacts = $this->insightly->getContacts($array);

        return $this->parseContactList($contacts);

    }

#####
##
## Take a contact list and return the bits we want
## including looking the company name from the id
##
#####
    public function parseContactList($contacts)
    {

        $name=array();
        $org=array();
        $email=array();

        foreach ($contacts as $c) {

// build the easy bit

            $id=$c->CONTACT_ID;
            $name[$id] => $c->FIRST_NAME . " " . $c->LAST_NAME;

// Get the email if we have one

            $cinfo=$c->CONTACTINFOS;
            foreach ($cinfo as $con) {
                if ($con->TYPE=="EMAIL"  && $con->LABEL=="WORK") {
                    $email[$id] = $con->DETAIL;
                }
            }
        }

// Now get the company name

// get all of the ids and call the API once - too slow otherwise
// Slight problem is that some people don't have a company and the API
// will blow up if we give it an invalid company so we need to have a list
// of company ids mapped to contacts

        $ids=array();

        foreach ($contacts as $c) {

            if (is_numeric($c->DEFAULT_LINKED_ORGANISATION)) {
                array_push($ids, $c->DEFAULT_LINKED_ORGANISATION);
            }

        }

// now we have our list of org ids - call API

        $orgs=$this->insightly->getOrganizations($ids);

// gives us a list of orgs - strip out names
                    foreach ($proj->LINKS as $l) {
                        array_push($ids, $l->CONTACT_ID);
                    }

            // Get details for ids
                    $contacts = $this->insightly->getContacts($array);

                    return $this->parseContactList($contacts);



            array_push($ret, $array);

        }

        return $ret;

    }

    public function parseContactListOLD($contacts)
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