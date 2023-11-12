<?php
class Admin
{
    private $handle;

    public function __construct()
    {
        $this->handle = new Database();
    }

    public function getFlightMixed()
    {
        $query = $this->handle->prepare("SELECT * FROM `Flight`");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["ID"]))
        {
            return $data;
        }

        return false;
    }

    public function getFlightActive()
    {
        $query = $this->handle->prepare("SELECT * FROM `Flight` WHERE Active = Y");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["ID"]))
        {
            return $data;
        }

        return false;
    }

    public function getFlightNotActive()
    {
        $query = $this->handle->prepare("SELECT * FROM `Flight` WHERE Active = N");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["ID"]))
        {
            return $data;
        }

        return false;
    }

    public function getUsers()
    {
        $query = $this->handle->prepare("SELECT * FROM `Account`");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["ID"]))
        {
            return $data;
        }

        return false;
    }

    public function getUserCount()
    {
        $query = $this->handle->prepare("SELECT COUNT(*) FROM `Account`");
        $query->execute();
        $data = $query->fetchColumn();

        if(!empty($data))
        {
            return $data;
        }

        return false;
    }

    public function getFlightActiveCount()
    {
        $query = $this->handle->prepare("SELECT COUNT(*) FROM `Flight` WHERE Active = Y");
        $query->execute();
        $data = $query->fetchColumn();

        if(!empty($data))
        {
            return $data;
        }

        return false;
    }

    public function getSupportTicketCount()
    {
        $query = $this->handle->prepare("SELECT COUNT(*) FROM `SupportTicket`");
        $query->execute();
        $data = $query->fetchColumn();

        if(!empty($data))
        {
            return $data;
        }

        return false;
    }

    public function getSupportTicketData()
    {
        $query = $this->handle->prepare("SELECT * FROM `SupportTicket`");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["TicketNo"]))
        {
            return $data;
        }

        return false;
    }

    public function getNotResolvedSupportTicketData()
    {
        $query = $this->handle->prepare("SELECT * FROM `SupportTicket` WHERE ResolvedIssue = No");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["TicketNo"]))
        {
            return $data;
        }

        return false;
    }

    public function getResolvedSupportTicketData()
    {
        $query = $this->handle->prepare("SELECT * FROM `SupportTicket` WHERE ResolvedIssue = Yes");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["TicketNo"]))
        {
            return $data;
        }

        return false;
    }

    public function getBookingData()
    {
        $query = $this->handle->prepare("SELECT * FROM `Booking`");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["BookID"]))
        {
            return $data;
        }

        return false;
    }

    public function getBookingDataByEmail(String $email)
    {
        $query = $this->handle->prepare("SELECT ID FROM `Account` WHERE Email = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        $query = $this->handle->prepare("SELECT * FROM `Booking` WHERE AccID = :accountid");
        $query->bindParam(":accountID", $data, PDO::PARAM_STR);
        $data = $query->fetchAll();

        if(!empty($data["BookID"]))
        {
            return $data;
        }

        return false;
    }

    public function getBookingDataByUsername(String $username)
    {
        $query = $this->handle->prepare("SELECT ID FROM `Account` WHERE Username = :username");
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        $query = $this->handle->prepare("SELECT * FROM `Booking` WHERE AccID = :accountid");
        $query->bindParam(":accountID", $data, PDO::PARAM_STR);
        $data = $query->fetchAll();

        if(!empty($data["BookID"]))
        {
            return $data;
        }

        return false;
    }
}