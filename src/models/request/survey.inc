<?php

class Survey {
    var $FirstName;
    var $LastName;
    var $EmailID;
    var $Phone;
    var $GroupName;
    var $GroupDesc;

    function __construct($firstName, $lastName, $email, $phone, $groupName, $groupDesc) {
        $this->FirstName = $firstName;
        $this->LastName = $lastName;
        $this->EmailID = $email;
        $this->Phone = $phone;
        $this->GroupName = $groupName;
        $this->GroupDesc = $groupDesc;
    }
}
?>