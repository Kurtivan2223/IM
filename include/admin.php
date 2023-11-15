<?php
class Admin
{
    public static function post_handler()
    {
        if(!empty($_POST['submit']))
        {
            self::addFlight();
        }
    }

    public static function addFlight()
    {
        if($_POST['submit'] != 'addfilght'
        || empty($_POST['flightName'])
        || empty($_POST['fSched'])
        || empty($_POST['pcount'])
        || empty($_POST['Origin'])
        || empty($_POST['Distination'])
        || empty($_POST['BoardTime'])
        || empty($_POST['DepartureTime'])
        || empty($_POST['fare']
        || empty($_POST['status'])))
        {
            return false;
        }

        do
        {
            $flightID = bin2hex(random_bytes(20));
            $query = Database::$connection->prepare("SELECT ID FROM `Flight` WHERE ID = :id");
            $query->bindParam(':id', $flightID, PDO::PARAM_STR);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        }while(!empty($data));

        $query = Database::$connection->prepare("INSERT INTO `Flight` VALUES(:id, :fname, :fsched, :pcount, :origin, :distination, :btime, :dtime, :fare, :status)");
        $query->execute(array(
            ":id" => $flightID,
            ":fname" => $_POST["flightName"],
            ":fsched" => $_POST["fSched"],
            ":pcount" => $_POST["pcount"],
            ":origin" => $_POST["origin"],
            ":distination" => $_POST["Distination"],
            ":btime" => $_POST["BoardTime"],
            ":dtime" => $_POST["DepartureTime"],
            ":fare" => $_POST["fare"],
            ":status" => $_POST["status"]
        ));

        success_msg("Flight Added.");
    }

    public static function getFlightMixed()
    {
        $query = Database::$connection->prepare("SELECT * FROM `Flight`");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["ID"]))
        {
            return $data;
        }

        return false;
    }

    public static function getFlightActive()
    {
        $query = Database::$connection->prepare("SELECT * FROM `Flight` WHERE Active = Y");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["ID"]))
        {
            return $data;
        }

        return false;
    }

    public static function getFlightNotActive()
    {
        $query = Database::$connection->prepare("SELECT * FROM `Flight` WHERE Active = N");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["ID"]))
        {
            return $data;
        }

        return false;
    }

    public static function getUsers()
    {
        $query = Database::$connection->prepare("SELECT * FROM `Account`");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["ID"]))
        {
            return $data;
        }

        return false;
    }

    public static function getUserCount()
    {
        $query = Database::$connection->prepare("SELECT COUNT(*) FROM `Account`");
        $query->execute();
        $data = $query->fetchColumn();

        if(!empty($data))
        {
            return $data;
        }

        return false;
    }

    public static function getFlightActiveCount()
    {
        $query = Database::$connection->prepare("SELECT COUNT(*) FROM `Flight` WHERE Active = Y");
        $query->execute();
        $data = $query->fetchColumn();

        if(!empty($data))
        {
            return $data;
        }

        return false;
    }

    public static function getSupportTicketCount()
    {
        $query = Database::$connection->prepare("SELECT COUNT(*) FROM `SupportTicket`");
        $query->execute();
        $data = $query->fetchColumn();

        if(!empty($data))
        {
            return $data;
        }

        return false;
    }

    public static function getSupportTicketData()
    {
        $query = Database::$connection->prepare("SELECT * FROM `SupportTicket`");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["TicketNo"]))
        {
            return $data;
        }

        return false;
    }

    public static function getNotResolvedSupportTicketData()
    {
        $query = Database::$connection->prepare("SELECT * FROM `SupportTicket` WHERE ResolvedIssue = No");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["TicketNo"]))
        {
            return $data;
        }

        return false;
    }

    public static function getResolvedSupportTicketData()
    {
        $query = Database::$connection->prepare("SELECT * FROM `SupportTicket` WHERE ResolvedIssue = Yes");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["TicketNo"]))
        {
            return $data;
        }

        return false;
    }

    public static function getBookingData()
    {
        $query = Database::$connection->prepare("SELECT * FROM `Booking`");
        $query->execute();
        $data = $query->fetchAll();

        if(!empty($data[0]["BookID"]))
        {
            return $data;
        }

        return false;
    }

    public static function getBookingDataByEmail(String $email)
    {
        $query = Database::$connection->prepare("SELECT ID FROM `Account` WHERE Email = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        $query = Database::$connection->prepare("SELECT * FROM `Booking` WHERE AccID = :accountid");
        $query->bindParam(":accountID", $data, PDO::PARAM_STR);
        $data = $query->fetchAll();

        if(!empty($data["BookID"]))
        {
            return $data;
        }

        return false;
    }

    public static function getBookingDataByUsername(String $username)
    {
        $query = Database::$connection->prepare("SELECT ID FROM `Account` WHERE Username = :username");
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        $query = Database::$connection->prepare("SELECT * FROM `Booking` WHERE AccID = :accountid");
        $query->bindParam(":accountID", $data, PDO::PARAM_STR);
        $data = $query->fetchAll();

        if(!empty($data["BookID"]))
        {
            return $data;
        }

        return false;
    }
}