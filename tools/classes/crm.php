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

        echo "<h1>" . $proj->PROJECT_NAME . "</h1>\n\n";

        $ids=array();

// get all of the ids and call the API once - too slow otherwise
        foreach ($proj->LINKS as $l) {
            array_push($ids, $l->CONTACT_ID);
        }

        echo "<h2>Calling GetContacts with these ids</h2>\n\n";

        print_r($ids);


// Get details for ids

       $big_ids = array(
            "ids" => $ids,
        );

        $contacts = $this->insightly->getContacts($big_ids);

        return $this->parseContactList($contacts);

    }

#####
##
## Take a contact list and return the bits we want
## including looking up the company name from the id
##
#####
    public function parseContactList($contacts)
    {

        echo "<h2>Inside parseContactList - was given:</h2>\n\n";

        print_r($contacts);

        echo "<h3>-----</h3>";

        $name=array();
        $orgname=array();
        $email=array();

        foreach ($contacts as $c) {

// build the easy bit

            $id=$c->CONTACT_ID;
            $name[$id] = $c->FIRST_NAME . " " . $c->LAST_NAME;

// Get the email if we have one

            $cinfo=$c->CONTACTINFOS;
            foreach ($cinfo as $con) {
                if (strcasecmp($con->TYPE,"EMAIL")==0  && strcasecmp($con->LABEL,"WORK")==0) {
                    $email[$id] = $con->DETAIL;
                }

// Use personal if we don't have work

                if (!array_key_exists($id, $email)) {

                    if (strcasecmp($con->TYPE,"EMAIL")==0  && strcasecmp($con->LABEL,"Personal")==0) {
                        $email[$id] = $con->DETAIL;
                    }
                }
            }
        }

// Now get the company name

// get all of the ids and call the API once - too slow otherwise
// Slight problem is that some people don't have a company and the API
// will blow up if we give it an invalid company so we need to have a list
// of company ids mapped to contacts

        $orgids=array();            # list of org ids for the Insightly API call

        foreach ($contacts as $c) {

            if (is_numeric($c->DEFAULT_LINKED_ORGANISATION)) {
                array_push($orgids, $c->DEFAULT_LINKED_ORGANISATION);
            }

        }

// now we have our list of org ids - call API

       $big_ids = array(
            "ids" => $orgids,
        );

        print_r($big_ids);

        $orgs=$this->insightly->FixedgetOrganizations($big_ids);

        echo "<h1>--------<br>Rsponse from getorg</h1>\n\n";

        print_r($orgs);

        echo "<h1>----</h1>\n\n";

//  get the id to company name mappings out of the json

        $orgmap=array();

        foreach ($orgs as $org) {
            $orgmap[$org->ORGANISATION_ID] = $org->ORGANISATION_NAME;
        }

// ok, now go through the list again and pull out the company names

        foreach ($contacts as $c) {

            if (array_key_exists($c->DEFAULT_LINKED_ORGANISATION, $orgmap)) {
                $orgname[$c->CONTACT_ID] = $orgmap[$c->DEFAULT_LINKED_ORGANISATION];

            }

        }

// Finally - lets put the values together into groups and return them

        $ret=array();

        echo "<h3>Email list</h3>\n\n";
        print_r($email);
        echo "<h3>Org list</h3>\n\n";
        print_r($orgname);

        echo "<h3>....</h3>\n\n";

        foreach ($contacts as $c) {

            $array=array();
            $array['id']=$c->CONTACT_ID;
            $array['name']=$name[$c->CONTACT_ID];
            if (array_key_exists($c->CONTACT_ID, $orgname)) {
                $array['org']=$orgname[$c->CONTACT_ID];
            } else {
                $array['org']="Unknown";
            }
            if (array_key_exists($c->CONTACT_ID, $email)) {
                $array['email']=$email[$c->CONTACT_ID];
            } else {
                $array['email']="Unknown";
            }

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